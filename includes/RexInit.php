<?php
/**
 * @file
 * Defines the initializer class for REX
 */

class RexInit
{
  /**
   * Handles the version checking and update procedures.
   */
  public static function rexVersion()
  {
    global $wpdb;
    $version = get_option( 'rex_version' );
    
    if ( $version < REX_VERSION) {
      
      /* 
       * [UPDATE PROCEDURES] 
       */
      
      update_option( 'rex_version', REX_VERSION);
    }
  }
  
  /* Runs rex_version() when REX is activated */
  public static function rexActivate()
  {
    self::rexVersion();
    self::rexDbDelta();
    self::resgiterTaxonomies();
    self::addDefaultTaxonomyTerms();
  }
  
  public static function rexLoadPluginTextdomain()
  {
    load_plugin_textdomain( 'realtor-express', false, 'realtor-express/languages' );
  }
  
  public static function loadClasses()
  {
    /* Load includes */
    require_once REX_PLUGIN_DIR . '/includes/RexHtml.php';
    require_once REX_PLUGIN_DIR . '/includes/RexSettings.php';
    require_once REX_PLUGIN_DIR . '/includes/RexGmap.php';
    
    /* Load controllers */
    require_once REX_PLUGIN_DIR . '/controllers/RexListingController.php';
    
    /* Load models */
    require_once REX_PLUGIN_DIR . '/models/RexListing.php';
  }
  
  /**
   * Load REX's non-admin styles and scripts.
   */
  public static function queueStylesAndScripts()
  {
    /* Load REX script */
    wp_register_script( 'rex', REX_PLUGIN_URL . '/js/rex.js', array('jquery') );
    wp_enqueue_script( 'rex' );
    
    /* Load lightbox */
    wp_register_script( 'lightbox2', REX_PLUGIN_URL . '/plugins/lightbox/js/lightbox.js', array('jquery') );
    wp_enqueue_script( 'lightbox2' );
    wp_register_style( 'lightbox2_stylesheet', REX_PLUGIN_URL . '/plugins/lightbox/css/lightbox.css' );
    wp_enqueue_style( 'lightbox2_stylesheet' );
    
    /* Load Google Maps script */
    wp_register_script( 'google_maps', 'https://maps.googleapis.com/maps/api/js?sensor=false' );
    wp_enqueue_script( 'google_maps' );
    wp_register_script( 'rex_gmap', REX_PLUGIN_URL . '/js/gmap.js' );
    wp_enqueue_script( 'rex_gmap' );
    
    /* Load styles */
    wp_register_style( 'rex_stylesheet', REX_PLUGIN_URL . '/css/style.css' );
    wp_enqueue_style( 'rex_stylesheet' );
  
    /* Register jQuery */
    // wp_enqueue_script( 'jquery' );
  
    /* Load REX script */
    // wp_register_script( 'rex', REX_PLUGIN_URL . '/js/rex.js', array('jquery') );
    // wp_enqueue_script( 'rex' );
  }
  
  /**
   * Load REX's admin styles and scripts.
   */
  public static function queueAdminStylesAndScripts()
  {
    /* Load REX admin script */
    wp_register_script( 'admin_rex', REX_PLUGIN_URL . '/js/admin_rex.js', array('jquery') );
    wp_enqueue_script( 'admin_rex' );
    
    /* Load Google Maps script */
    wp_register_script( 'google_maps', 'https://maps.googleapis.com/maps/api/js?sensor=false' );
    wp_enqueue_script( 'google_maps' );
    wp_register_script( 'rex_gmap', REX_PLUGIN_URL . '/js/gmap.js' );
    wp_enqueue_script( 'rex_gmap' );
    
    /* Load lightbox */
    wp_register_script( 'lightbox2', REX_PLUGIN_URL . '/plugins/lightbox/js/lightbox.js', array( 'jquery' ) );
    wp_enqueue_script( 'lightbox2' );
    wp_register_style( 'lightbox2_stylesheet', REX_PLUGIN_URL . '/plugins/lightbox/css/lightbox.css' );
    wp_enqueue_style( 'lightbox2_stylesheet' );
    
    /* Load admin styles */
    wp_register_style( 'rex_admin_stylesheet', REX_PLUGIN_URL . '/css/admin_style.css' );
    wp_enqueue_style( 'rex_admin_stylesheet' );
  }
  
  public function enqueueGmapsScripts() {
    /* Load Google Maps script */
    wp_enqueue_script( 'google_maps' );
    wp_enqueue_script( 'rex_gmap' );
  }
  
  public static function registerListingPostType()
  {
    self::registerPostType();
    self::resgiterTaxonomies();
  }
  
	/**
   * Creates the tables necessary for Realtor Express.
   */
  private static function rexDbDelta() {
    global $wpdb;
    $wpdb->show_errors();
  
    // Create tables SQL
    $sql = "CREATE TABLE " . $wpdb->prefix . "rex_listings (
    			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    			listing_id BIGINT(20) UNSIGNED NOT NULL,
    			listing_number VARCHAR (255) NOT NULL,
    			status VARCHAR (255) NOT NULL,
    			price INT (11) NOT NULL,
    			bedrooms DECIMAL(2,1) NOT NULL,
    			baths DECIMAL(2,1) NOT NULL,
    			floor_space INT(20) NOT NULL,
    			year_built INT(10),
    			address TEXT,
    			formatted_address TEXT,
    			locality VARCHAR(255),
    			location TEXT,
    			country VARCHAR(255),
    			postal_code VARCHAR(255),
    			PRIMARY KEY  (id),
    			UNIQUE KEY listing_id (listing_id)
    			);";
  
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }
  
  /**
   * Register shortcodes.
   */
  public static function registerShortcodes()
  {
    add_shortcode('rex_gallery', array( 'RexListingController', 'renderRexGallery' ) );
    add_shortcode('rex_map', array( 'RexListingController', 'renderRexMap' ) );
    add_shortcode('rex_multi_map', array( 'RexListingController', 'renderRexMultiMap' ) );
    add_shortcode('rex_details', array( 'RexListingController', 'renderRexDetails' ) );
    add_shortcode('rex_listings', array( 'RexListingController', 'renderRexListings' ) );
  }
  
  /**
   * Register widgets.
   */
  public static function registerWidgets()
  {
    /* Map Widget */
    require_once REX_PLUGIN_DIR . '/widgets/RexMultiMapWidget.php';
    register_widget( 'RexMultiMapWidget' );
    
    /* Listing Filter Widget */
    require_once REX_PLUGIN_DIR . '/widgets/RexListingFilterWidget.php';
    register_widget( 'RexListingFilterWidget' );
  }
  
  /**
  * Register custom taxonomies.
  */
  public static function resgiterTaxonomies()
  {
    $type_labels = array(
      'name' => _x( 'Listing Types', 'taxonomy general name', 'realtor-express' ),
      'singular_name' => _x( 'Listing Type', 'taxonomy singular name', 'realtor-express' ),
      'search_items' =>  __( 'Search Listing Types', 'realtor-express' ),
      'all_items' => __( 'All Listing Types', 'realtor-express' ),
      'parent_item' => __( 'Parent Listing Type', 'realtor-express' ),
      'parent_item_colon' => __( 'Parent Listing Type:', 'realtor-express' ),
      'edit_item' => __( 'Edit Listing Type', 'realtor-express' ),
      'update_item' => __( 'Update Listing Type', 'realtor-express' ),
      'add_new_item' => __( 'Add New Listing Type', 'realtor-express' ),
      'new_item_name' => __( 'New Listing Type Name', 'realtor-express' ),
      'menu_name' => __( 'Listing Type', 'realtor-express' ),
    );
  
    $type_args = array(
      'hierarchical' => true,
      'labels' => $type_labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'listing-type' ),
    );
  
    register_taxonomy( 'rex_listing_type', 'rex_listing', $type_args );
  
    $style_labels = array(
      'name' => _x( 'Listing Styles', 'taxonomy general name', 'realtor-express' ),
      'singular_name' => _x( 'Listing Style', 'taxonomy singular name', 'realtor-express' ),
      'search_items' =>  __( 'Search Listing Styles', 'realtor-express' ),
      'all_items' => __( 'All Listing Styles', 'realtor-express' ),
      'parent_item' => __( 'Parent Listing Style', 'realtor-express' ),
      'parent_item_colon' => __( 'Parent Listing Style:', 'realtor-express' ),
      'edit_item' => __( 'Edit Listing Style', 'realtor-express' ),
      'update_item' => __( 'Update Listing Style', 'realtor-express' ),
      'add_new_item' => __( 'Add New Listing Style', 'realtor-express' ),
      'new_item_name' => __( 'New Listing Style Name', 'realtor-express' ),
      'menu_name' => __( 'Listing Style', 'realtor-express' ),
    );
  
    $style_args = array(
      'hierarchical' => true,
      'labels' => $style_labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'style' ),
    );
  
    register_taxonomy( 'rex_listing_style', 'rex_listing', $style_args );
  }
  
  /**
   * Registers the listing custom post type.
   */
  public static function registerPostType()
  {
    $labels = array(
      'name' => _x( 'Listings', 'post type general name', 'realtor-express' ),
      'singular_name' => _x('Listing', 'post type singular name', 'realtor-express' ),
      'add_new' => _x( 'Add New', 'listing', 'realtor-express' ),
      'add_new_item' => __( 'Add New Listing', 'realtor-express' ),
      'edit_item' => __( 'Edit Listing', 'realtor-express' ),
      'new_item' => __( 'New Listing', 'realtor-express' ),
      'all_items' => __( 'All Listings', 'realtor-express' ),
      'view_item' => __( 'View Listing', 'realtor-express' ),
      'search_items' => __( 'Search Listings', 'realtor-express' ),
      'not_found' =>  __( 'No listings found', 'realtor-express' ),
      'not_found_in_trash' => __( 'No listings found in Trash', 'realtor-express' ),
      'parent_item_colon' => '',
      'menu_name' => __( 'Listings', 'realtor-express' ),
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'listings' ),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => 100,
      'supports' => array( 'title', 'editor', 'thumbnail' ),
      'taxonomies' => array( 'rex_listing_type' ),
    );
    register_post_type( 'rex_listing', $args );
  }
  
  public static function addDefaultTaxonomyTerms()
  {
    // If custom taxonomies are empty, we'll add some default values.
    global $wpdb;
  
    $table_name = $wpdb->prefix . 'term_taxonomy';
  
    /* ----------------- Listing Type Taxonomy --------------------- */
    $sql = "SELECT * FROM $table_name WHERE taxonomy = 'rex_listing_type'";
    $results = $wpdb->get_results( $sql );
  
    if ( empty( $results ) ) {
      // No rex_listing_type terms. Let's add default.
      wp_insert_term( 'Condo', 'rex_listing_type', $args = array( 'description' => 'Example of a listing type.' ) );
      wp_insert_term( 'Townhouse', 'rex_listing_type', $args = array( 'description' => 'Example of a listing type.' ) );
      wp_insert_term( 'Duplex', 'rex_listing_type', $args = array( 'description' => 'Example of a listing type.' ) );
    }
  
    /* ----------------- Style Taxonomy --------------------- */
    $sql = "SELECT * FROM $table_name WHERE taxonomy = 'style'";
    $results = $wpdb->get_results( $sql );
  
    if ( empty( $results ) ) {
      // No rex_listing_type terms. Let's add default.
      wp_insert_term( 'Detached', 'rex_listing_style', $args = array( 'description' => 'Example of a style.' ) );
      wp_insert_term( 'Semi-detached', 'rex_listing_style', $args = array( 'description' => 'Example of a style.' ) );
    }
  }
  
  public static function addThumbnailSupport()
  {
    if (!function_exists('get_the_post_thumbnail'))
      add_theme_support( 'post-thumbnails' );
  }
}