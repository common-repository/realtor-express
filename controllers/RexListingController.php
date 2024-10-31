<?php
/**
 * @file
 * Defines the RexListingController class.
 */

class RexListingController
{
  public function addMetaBoxes()
  {
    add_meta_box( 'rex-listing-information', __('Listing Information', 'realtor-express'), array('RexListingController', 'listingInformationCallback'), 'rex_listing', 'normal', 'high');
  }

  public function saveData( $post_id )
  {
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['rex_listing_info_nonce'], plugin_basename( __FILE__ ) ) ) {
      // TODO: wp_die(with message)
      return;
    }

    // Check permissions
    // TODO: Create custom permissions
    if ( $_POST['post_type'] == 'rex_listing' )
    {
      if ( !current_user_can( 'edit_page', $post_id ) )
        return;
    }
    else
    {
      if ( !current_user_can( 'edit_post', $post_id ) )
        return;
    }

    // OK, we're authenticated: we need to find and save the data
    // First, let's create a RexListing object.
    $listing = new RexListing( $post_id );

    // Load data into instance vars and into $data array to be used in the view.
    $listing->listing_number = $_POST['rex_listing_number'];
    $listing->status = $_POST['rex_status'];
    $listing->price = $_POST['rex_price'];
    $listing->bedrooms = $_POST['rex_bedrooms'];
    $listing->baths = $_POST['rex_baths'];
    $listing->floor_space = $_POST['rex_floor_space'];
    $listing->year_built = $_POST['rex_year_built'];
    $listing->address = $_POST['rex_address'];

    // Ssave to database.
    $listing->save();
  }

  /**
   * Delete additional listing information when post is deleted.
   *
   * @param int $post_id
   */
  public function deleteData( $post_id )
  {
    $listing = new RexListing( $post_id );
    $listing->delete();
  }

  /**
   * Add sub pages.
   */
  public function adminPagesCallback()
  {
    add_submenu_page( 'edit.php?post_type=rex_listing', 'Documentation', 'Documentation', 'manage_options', 'documentation', array( 'RexListingController', 'docPageCallback' ) );
    add_submenu_page( 'edit.php?post_type=rex_listing', 'Settings', 'Settings', 'manage_options', 'settings', array( 'RexSettings', 'renderSettingsPage' ) );
  }

  /**
   * Render listing form.
   *
   * @param int $post
   * 	Post ID.
   */
  public function listingInformationCallback( $post )
  {
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'rex_listing_info_nonce' );

    $listing = new RexListing( $post->ID );
    $status_array = RexListing::getStatusArray();

    setlocale(LC_MONETARY, get_locale());
    $my_local_settings = localeconv();
    $floor_space_units = get_option( 'rex_floor_space_units', 'metric' ) == 'metric' ? __( 'Sqm', 'realtor-express' ) : __( 'Sqft', 'realtor-express' );

    require_once REX_PLUGIN_DIR . '/views/RexAdminListingView.php';
  }

  /**
   * Render help/documentation page.
   */
  public function docPageCallback()
  {
    global $plugin_page;
    $wrap_class = 'rex-' . $plugin_page;

    require_once REX_PLUGIN_DIR . '/views/RexAdminDocView.php';
  }

  /**
   * Renders a listing gallery (shortcode callback).
   */
  public static function renderRexGallery( $atts )
  {
    global $id;
    $listing = new RexListing( $id );

    if ( $listing->is_new == true ) {
      $listing->displayShortcodeError( '[rex_gallery]' );
      return;
    }

    $gallery_images = $listing->getListingGallery();
    $width = $atts['width'] . 'px';
    $thumb_width = $atts['thumb'] . 'px';

    // Capture view output into buffer to be returned to shortcode caller.
    ob_start();
    require_once REX_PLUGIN_DIR . '/views/RexShortcodeGalleryView.php';
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
  }

  /**
   * Renders a listing map (shortcode callback).
   */
  public static function renderRexMap( $atts )
  {
    global $id;
    $listing = new RexListing( $id );

    if ( $listing->is_new == true ) {
      $listing->displayShortcodeError( '[rex_map]' );
      return;
    }

    $width = isset( $atts['width'] ) ? $atts['width'] : 400;
    $height = isset( $atts['height'] ) ? $atts['height'] : 300;

    // Capture view output into buffer to be returned to shortcode caller.
    ob_start();
    require_once REX_PLUGIN_DIR . '/views/RexShortcodeMapView.php';
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
  }

  /**
   * Renders the multi map (shortcode callback).
   */
  public static function renderRexMultiMap( $atts )
  {
    $limit = isset( $atts['limit'] ) ? $atts['limit'] : 50;
    $width = isset( $atts['width'] ) ? $atts['width'] : 400;
    $height = isset( $atts['height'] ) ? $atts['height'] : 300;

    $locations_json_array = RexListing::getListingsLocationsJSON( array("LIMIT" => $limit ) );

    // Capture view output into buffer to be returned to shortcode caller.
    ob_start();
    require_once REX_PLUGIN_DIR . '/views/RexShortcodeMultiMapView.php';
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
  }

  /**
   * Renders the listing details (shortcode callback).
   */
  public static function renderRexDetails( $atts )
  {
    global $id;
    $listing = new RexListing( $id );

    if ( $listing->is_new == true ) {
      $listing->displayShortcodeError( '[rex_details]' );
      return;
    }

    $floor_space_units = get_option( 'rex_floor_space_units', 'metric' ) == 'metric' ? __( 'Sqm', 'realtor-express' ) : __( 'Sqft', 'realtor-express' );
    $width = $atts['width'];

    if ($width)
      $table_styles = "style='width:" . $width . "px;'";

    ob_start();
    require_once REX_PLUGIN_DIR . '/views/RexShortcodeDetailsView.php';
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
  }

  /**
   * Renders the REX multi listings view (shortcode callback).
   */
  public static function renderRexListings( $atts )
  {
    $listings_per_page = isset( $atts['listings_per_page'] ) ? $atts['listings_per_page'] : 20;
    $output = '';

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $args = array(
    	'posts_per_page' => $listings_per_page, 
    	'paged' => $paged, 
    	'post_type' => 'rex_listing'
    );
    
    query_posts( $args ); 
    
    $floor_space_units = get_option( 'rex_floor_space_units', 'metric' ) == 'metric' ? __( 'Sqm', 'realtor-express' ) : __( 'Sqft', 'realtor-express' );
    
    // Pagination.
    global $wp_query;
    $pagination = paginate_links( array(
    	'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
    	'format' => '?paged=%#%',
    	'current' => max( 1, get_query_var('paged') ),
    	'total' => $wp_query->max_num_pages
    ) );
    
    $number_of_posts = $wp_query->post_count;
    
    ob_start();
    if ( have_posts() ) {
      $i = 1;
      while (have_posts()) {
        the_post();
        /*
        * Define sub-view variables (RexListingContentView).
        */
        $thumbnail = RexListing::getListingThumb( get_the_ID(), 100 );
        $listing = new RexListing( get_the_ID() );
        $permalink = get_permalink( $post->ID );
        $last_class = ( $i == $number_of_posts ) ? 'last' : '';
        
        include REX_PLUGIN_DIR . '/views/RexListingsLoopView.php';
        $i++;
      }
      echo '<div id="rex-pagination">' . $pagination . '</div>';
    }
    else {
      _e( 'No Listings Found', 'realtor-express' );
    }
     
    $output .= ob_get_contents();
    ob_end_clean();
    
    // Reset Query 
    wp_reset_query();
    
    return $output;
  }

  public static function customizeListingContent( $posts )
  {
    $floor_space_units = get_option( 'rex_floor_space_units', 'metric' ) == 'metric' ? __( 'Sqm', 'realtor-express' ) : __( 'Sqft', 'realtor-express' );
    
    foreach ( $posts as $index => $post ) {
      $listing = new RexListing( $post->ID );
      $thumbnail = RexListing::getListingThumb( $post->ID, 100 );
      $permalink = get_permalink( $post->ID );

      // Customize post content:
      ob_start();
      include REX_PLUGIN_DIR . '/views/RexListingContentView.php';
      $output = ob_get_contents();
      ob_end_clean();

      $posts[$index]->post_content = $output;
    }

    return $posts;
  }
}