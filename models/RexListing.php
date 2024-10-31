<?php
/**
 * @file
 * Defines the RexListing model class.
 */

class RexListing
{
  private $data = array();
  private $listing_id;
  private $is_new = TRUE;

  // Database rows
  private $listing_number; // VARCHAR (255)
  private $status; // (sold, leased, for sale, for rent)
  private $price; // (localized)
  private $bedrooms; // DECIMAL (2, 1)
  private $baths;
  private $floor_space; // Localized
  private $year_built;
  private $address; // Typed-in address
  private $formatted_address; // Returned from Gmaps
  private $locality; // City or town
  private $location;
  private $country;
  private $postal_code;
  
  // Constructor
  public function __construct( $post_id )
  {
    global $wpdb;

    $this->listing_id = $post_id;
    $this->data['listing_id'] = $post_id;

    // Load current information into ivars, if it already exists in the db.
    $table_name = self::tableName();
    $sql = $wpdb->prepare( "SELECT * FROM $table_name WHERE listing_id = %d", $this->listing_id );
    $row = $wpdb->get_row( $sql );

    // Check is this is a new listing or an existing one.
    if ( $row ) {
      $this->is_new = FALSE;

      foreach ( $row as $key => $value ) {
        if ( property_exists( __CLASS__, $key ) )
          $this->$key = $value;
      }
    }
  }

  // Returns the table name for this model.
  public static function tableName()
  {
    global $wpdb;
    return $wpdb->prefix . 'rex_listings';
  }

  public function displayShortcodeError( $shortcode)
  {
    printf( __('REX_ERROR: The shortcode %s should only be used within a single listing context.', 'realtor-express') . '<br/>', $shortcode );
  }

  /**
   * Listing setters.
   */
  public function __set( $name, $value )
  {
    // Setter actions.
    switch ( $name ) {
      case 'price':
      case 'floor_space':
      case 'year_built':
        // Remove everything but numbers
        $value = preg_replace("/[^0-9]/", "", $value );
        $this->data[$name] = $value;
        break;
      case 'address':
        // Query Google Maps for location infromation.
        $gmap_address = RexGmap::parseAddress( $value );
        if ( $gmap_address->status == 'ZERO_RESULTS' || count( $gmap_address->results ) != 1 ) {
          // Problem with address parsing.
          $this->data[$name] = NULL;
          break;
        }

        // Store original address.
        $this->data[$name] = $value;

        // Store Google Maps location information in db.
        $this->data['formatted_address'] = $gmap_address->results[0]->formatted_address;
        $this->data['location'] = serialize( (array) $gmap_address->results[0]->geometry->location );

        $locality = NULL;
        $country = NULL;
        $postal_code = NULL;

        foreach ( $gmap_address->results[0]->address_components as $component ) {
          if (in_array( 'locality', $component->types)) {
             $locality = $component->long_name;
          }
          if (in_array( 'country', $component->types)) {
             $country = $component->long_name;
          }
          if (in_array( 'postal_code', $component->types)) {
             $postal_code = $component->long_name;
          }
        }

        $this->data['locality'] = $locality;
        $this->data['country'] = $country;
        $this->data['postal_code'] = $postal_code;
        break;
      default:
        $this->data[$name] = $value;
        break;
    }
  }

	/**
   * Listing getters.
   */
  public function __get( $name )
  {
    // Getter actions
    switch ( $name ) {
      case 'price':
      case 'floor_space':
        $output = number_format( $this->$name );
        return $output;
      case 'bedrooms':
      case 'baths':
        $output = preg_replace( '/\.0$/', '', $this->$name );
        return $output;
      case 'locality':
      case 'country':
      case 'postal_code':
        return ( $this->$name != NULL ) ? $this->$name : 'n/a';
      default:
        return $this->$name;
    }
  }

  // Save data to dabatase.
  public function save()
  {
    global $wpdb;
    $table_name = self::tableName();

    $rows_inserted = $wpdb->insert( self::tableName(), $this->data );

    if ( $rows_inserted == 0 )
      $rows_updated = $wpdb->update( self::tableName(), $this->data, array( 'listing_id' => $this->listing_id ) );
  }

  // Delete record from database.
  public function delete()
  {
    global $wpdb;

    $table_name = RexListing::tableName();
    $sql = $wpdb->prepare( "DELETE FROM $table_name WHERE listing_id = %d", $this->listing_id );
    $rows_deleted = $wpdb->query( $sql );
  }

  /**
   * Returns an array where each key contains both the thumbnail and the medium urls.
   */
  public function getListingGallery()
  {
    $images = array();

    $args = array(
    	'post_type' => 'attachment',
    	'numberposts' => -1,
    	'post_status' => null,
    	'post_parent' => $this->listing_id
    );

    $attachments = get_posts($args);

    $pattern = '/^image\/[a-z]+/';
    foreach ( $attachments as $key => $attachment ) {
      if ( !preg_match( $pattern, $attachment->post_mime_type ) )
        return;

      $src_thumb = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
      $src_medium = wp_get_attachment_image_src( $attachment->ID, 'medium' );
      $src_large = wp_get_attachment_image_src( $attachment->ID, 'full' );

      $images[$key]['thumb'] = $src_thumb[0];
      $images[$key]['medium'] =  $src_medium[0];
      $images[$key]['large'] =  $src_large[0];
    }

    return $images;
  }

  /**
   * Returns a thumbnails gallery HTML for the current listing.
   */
  public function getGalleryThumbsHtml()
  {
    $gallery_images = $this->getListingGallery();

    $output = "";
    foreach ( $gallery_images as $image ) {
      $output .= "<a href=" . $image['medium'] . " rel='lightbox[gallery]'><img src=" . $image['thumb'] . " width='75' /></a> ";
    }
    return $output;
  }

  /**
   * Returns true if gallery is empty.
   */
  public function isGalleryEmpty()
  {
    $gallery_images = $this->getListingGallery();
    return ( empty( $gallery_images ) ) ? true : false;
  }

  /**
   * Returns the location array of the lising.
   */
  public function getLocation()
  {
    global $wpdb;
    $table_name = $this->tableName();
    $sql = $wpdb->prepare( "SELECT location FROM $table_name WHERE listing_id = %d", $this->listing_id );
    $result = $wpdb->get_var( $sql );
    if ( $result )
      return unserialize( $result );
  }

  /**
   * Returns the listing latitude. false if not available.
   */
  public function getLatitude()
  {
    $location_array = $this->getLocation();
    return !empty( $location_array['lat'] ) ? $location_array['lat'] : false;
  }

  /**
   * Returns the listing longitude. false if not available.
   */
  public function getLongitude()
  {
    $location_array = $this->getLocation();
    return !empty( $location_array['lng'] ) ? $location_array['lng'] : false;
  }

  /**
   * Returns the listing formatted price.
   */
  public function getPrice()
  {
    setlocale(LC_MONETARY, get_locale());
    $my_local_settings = localeconv();
    return $my_local_settings['currency_symbol'] . number_format( $this->price );
  }

  public static function generateLocalAddressStrings()
  {
    $local_request_failed_str = esc_html( __( 'Request failed. Please check your connection and try again.', 'realtor-express') );
    $local_invalid_address_str = esc_html( __( 'Invalid address', 'realtor-express' ) );
    $local_multiple_address_str = esc_html( __( 'Multiple results found. Please use a valid Google Maps address.', 'realtor-express' ) );
    $local_valid_address_str = esc_html( __( 'Valid address', 'realtor-express' ) );
    $local_no_gallery_images = esc_html( __( 'Use the standard media uploader to attach images to this listing.', 'realtor-express' ) );

    $output = "<div id='rex-request-failed' style='display: none;'>$local_request_failed_str</div>";
    $output .= "<div id='rex-invalid-address' style='display: none;'>$local_invalid_address_str</div>";
    $output .= "<div id='rex-multiple-addresses' style='display: none;'>$local_multiple_address_str</div>";
    $output .= "<div id='rex-valid-address' style='display: none;'>$local_valid_address_str</div>";
    $output .= "<div id='rex-no-gallery-images' style='display: none;'>$local_no_gallery_images</div>";

    echo $output;
  }

  /**
   * Returns a JSON object with lat and lng values.
   *
   * @param array $filter
   * 	Filter by attributes: array( 'LIMIT' => '5', etc... ).
   *
   * @return string $json
   * 	JSON encoded string.
   */
  public static function getListingsLocationsJSON( array $filter )
  {
    global $wpdb;
    setlocale(LC_MONETARY, get_locale());
    $my_local_settings = localeconv();
    $listings_table = RexListing::tableName();
    $posts_table = $wpdb->prefix . 'posts';

    $sql_options = "";
    if ( array_key_exists( 'ORDER', $filter ) ) {
      switch ($filter['ORDER']) {
        case 'price_desc':
          $order = 'l.price DESC';
          break;
        case 'price_asc':
          $order = 'l.price ASC';
          break;
        default:
          $order = 'l.price DESC';
          break;
      }
      $sql_options .= "ORDER BY " . $order . " ";
    }
    if ( array_key_exists( 'LIMIT', $filter ) ) {
      $sql_options .= "LIMIT " . $filter['LIMIT'] . " ";
    }

    $sql = $wpdb->prepare( "SELECT * FROM $listings_table l INNER JOIN $posts_table p ON l.listing_id = p.ID WHERE p.post_status = 'publish' $sql_options", '' );
    $results = $wpdb->get_results( $sql );

    $listings = array();

    foreach ( $results as $listing ) {
      $unserialized = unserialize( $listing->location );
      $lat = $unserialized['lat'];
      $lng = $unserialized['lng'];
      $permalink = get_permalink( $listing->listing_id );
      $image = wp_get_attachment_image_src( get_post_thumbnail_id( $listing->listing_id ), 'thumbnail' );
      $floor_space_units = get_option( 'rex_floor_space_units', 'metric' ) == 'metric' ? __( 'Sqm', 'realtor-express' ) : __( 'Sqft', 'realtor-express' );

      $listings[$listing->listing_id] = array(
      	'lat' => $lat,
      	'lng' => $lng,
      	'listing_title' => $listing->post_title,
      	'link' => $permalink,
      	'image' => $image,
      	'price' => $my_local_settings['currency_symbol'] . number_format( $listing->price ),
      	'bedrooms' => preg_replace( '/\.0$/', '', $listing->bedrooms ),
      	'baths' => preg_replace( '/\.0$/', '', $listing->baths ),
      	'floor_space' => number_format( $listing->floor_space ),
      	'flr_space_units' => esc_js( $floor_space_units ),
      );
    }

    $json = json_encode( $listings );
    return $json;
  }

  /**
   * Filters the JOIN clause of the Listing Filter search results.
   */
  public static function rexFilterPostsJoin( $join )
  {
    global $wpdb;
    $posts_table = $wpdb->prefix . 'posts';
    $listings_table = $wpdb->prefix . 'rex_listings';

    $join .= "INNER JOIN $listings_table ON $posts_table.ID = $listings_table.listing_id ";
    return $join;
  }

  /**
   * Filters the WHERE clause of the Listing Filter search results.
   */
  public static function rexFilterPostsWhere( $where )
  {
    global $wpdb;
    $posts_table = $wpdb->prefix . 'posts';
    $listings_table = $wpdb->prefix . 'rex_listings';

    $where = str_replace( "$posts_table.post_type = 'post'", "$posts_table.post_type = 'rex_listing'", $where );

    $fields = array(
    	'rex_filter_bedrooms' => 'bedrooms',
    	'rex_filter_baths' => 'baths',
    	'rex_filter_min_price' => 'price',
    	'rex_filter_max_price' => 'price',
    	'rex_filter_status' => 'status',
    );

    foreach ( $fields as $name => $var ) {
      if ( isset( $_GET[$name] ) && !empty( $_GET[$name] ) && $_GET[$name] != '_all' ) {
        switch ( $name ) {
          case 'rex_filter_bedrooms':
          case 'rex_filter_baths':
            $value = preg_replace( "/[^0-9\+]/", "", $_GET[$name] );
            if ( strpos( $value, '+' ) !== false ) {
              $value = (int) str_replace( '+', '', $value );
              $where .= sprintf( " AND ($listings_table.$var >= %d) ", $value);
            }
            else {
              $where .= sprintf( " AND ($listings_table.$var = %d) ", $value);
            }
            break;
          case 'rex_filter_min_price':
            $min_price = (int) preg_replace('/[^0-9]/', '', $_GET['rex_filter_min_price'] );
            $where .= sprintf( " AND ($listings_table.$var >= %d) ", $min_price);
            break;
          case 'rex_filter_max_price':
            $max_price = (int) preg_replace('/[^0-9]/', '', $_GET['rex_filter_max_price'] );
            $where .= sprintf( " AND ($listings_table.$var <= %d) ", $max_price);
            break;
          case 'rex_filter_status':
            if ( array_key_exists( $_GET['rex_filter_status'], self::getStatusArray( true ) ) ) {
              $status = preg_replace('/[^a-zA-Z_-]/', '', $_GET['rex_filter_status'] );
              $where .= sprintf( " AND ($listings_table.$var = '%s') ", $status);
            }
            break;
        }

      }
    }

    return $where;
  }

	/**
   * Filters the actual posts (listings) of the Listing Filter search results.
   */
  public static function rexFilterPosts( $posts )
  {
    $no_match = array();

    foreach ( $posts as $index => $post ) {
      $skip = array();
      // Filter type and style fields.
      $fields = array(
      	'rex_filter_type' => 'rex_listing_type',
      	'rex_filter_style' => 'rex_listing_style'
      );

      foreach ( $fields as $field => $taxonomy ) {
        if ( isset( $_GET[$field] ) && !empty( $_GET[$field] ) && $_GET[$field] != '_all' ) {
          $has_term = has_term( $_GET[$field], $taxonomy, $post );
          if ( ! $has_term ) {
            $no_match[$index] = $post->ID;
            $skip[] = true;
          }
        }
      }
    }

    // array_unique( $no_match );
    foreach ( $no_match as $index => $post_id ) {
      unset( $posts[$index] );
    }

    return array_values( $posts );
  }

  /**
   * Returns the status array to be used in the select list of the listings post type.
   *
   * @param boolean $front_end
   * 	If true (used for front end) sold and leased will be removed.
   */
  public static function getStatusArray( $front_end = false )
  {
    $status_array = array(
        'for_sale' => __( 'For Sale', 'realtor-express' ),
        'for_rent' => __( 'For Rent', 'realtor-express' ),
        'sold' => __( 'Sold', 'realtor-express' ),
        'leased' => __( 'Leased', 'realtor-express' ),
    );

    if ( $front_end ) {
      unset( $status_array['sold'] );
      unset( $status_array['leased'] );
    }

    return $status_array;
  }

  /**
   * A helper function which returns and array such as array( '1', '1+', '2', '2+', ... )
   *
   * @param int $length
   * @return array $array;
   */
  public static function generatePlusArray( $length )
  {
    $array = array( '_all' => __( 'All', 'realtor-express' ) );
    for ( $i = 1; $i <= $length; $i++ ) {
      $array[$i] = $i;
      $array[$i . '+'] = $i . '+';
    }
    return $array;
  }

  /**
   * Generate a select list style array for the selected taxonomy.
   * 
   * @param string $taxonomy
   * @return the select list array.
   */
  public static function generateTaxonomySelectArray( $taxonomy )
  {
    $array = array( '_all' => __( 'All', 'realtor-express' ) );
    $terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );

    if ( empty( $terms ) )
      return array();
    
    foreach ( $terms as $term ) {
      // TODO: prepare array.
      $array[$term->slug] = $term->name;
    }
    return $array;
  }
  
  /**
   * Returns the thumbnail for the listing but takes into account sold or leased status and adds
   * necessary classes to be used later in javascript.
   * 
   * @param int $post_id
   * @param int $size
   * 	(pixels) This parameter will be used for both width and height.
   */
  public static function getListingThumb( $post_id, $size )
  {
    $thumbnail = get_the_post_thumbnail( $post_id, array( $size, $size ) );
    
    if ( ! empty( $thumbnail ) ) {
      $listing = new RexListing( $post_id );
      switch ( $listing->status ) {
        case 'sold':
          $thumbnail = '<div class="sold-wrapper ribbon-wrapper">' . $thumbnail . '</div>';
          break;
        case 'leased':
          $thumbnail = '<div class="leased-wrapper ribbon-wrapper">' . $thumbnail . '</div>';
          break;
      }
    }
    return $thumbnail;
  }
}