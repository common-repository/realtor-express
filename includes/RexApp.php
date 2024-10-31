<?php
/**
 * Main REX application class
 */

class RexApp
{

  /**
   * Initialize application
   */
  public static function init()
  {
    require_once REX_PLUGIN_DIR . '/includes/RexInit.php';

    /* Load classes */
    add_action( 'init', array( 'RexInit', 'loadClasses' ) );

    /* Verify thumbnails support */
    add_action( 'init', array( 'RexInit', 'addThumbnailSupport' ) );
    
    /* Check version and run update procedures as necessary */
    add_action( 'init', array( 'RexInit', 'rexVersion' ) );

    /* Register activation hook */
    register_activation_hook( REX_MAIN_PLUGIN_FILE, array( 'RexInit', 'rexActivate' ) );

    /* I18n */
    add_action( 'plugins_loaded', array( 'RexInit', 'rexLoadPluginTextdomain' ) );

    /* Register shortcodes */
    add_action( 'init', array( 'RexInit', 'registerShortcodes' ) );

    /* Register widgets */
    add_action( 'widgets_init', array( 'RexInit', 'registerWidgets' ) );

    /* Enqueue styles and scripts */
    add_action( 'wp_enqueue_scripts', array( 'RexInit', 'queueStylesAndScripts' ) );
    add_action( 'admin_enqueue_scripts', array( 'RexInit', 'queueAdminStylesAndScripts' ) );

    /* Register listing post type */
    add_action( 'init', array( 'RexInit', 'registerListingPostType' ) );

    /* Take care of custom fields (meta boxes) of the listing post type */
    add_action( 'add_meta_boxes', array( 'RexListingController', 'addMetaBoxes' ) );
    add_action( 'save_post', array( 'RexListingController', 'saveData' ) );
    add_action( 'delete_post', array( 'RexListingController', 'deleteData' ) );

    /* Add admin pages */
    add_action( 'admin_menu', array( 'RexListingController', 'adminPagesCallback' ) );

    /* Register Rex settings */
    add_action( 'admin_menu', array( 'RexSettings', 'rex_settings' ) );

    /* Add search results filter
     * Optional Filters:
     * - posts_request
     * - posts_join
     * - posts_where
     * - query_vars
     * - the_posts
     */

    // Only add filter if submitted by rex filter.
    if ( $_GET['rex-filter'] == true ) {
      add_filter( 'posts_join', array( 'RexListing', 'rexFilterPostsJoin' ) );
      add_filter( 'posts_where', array( 'RexListing', 'rexFilterPostsWhere' ) );
      add_filter( 'the_posts', array( 'RexListing', 'rexFilterPosts' ) );
      add_filter( 'the_posts', array( 'RexListingController', 'customizeListingContent' ) );
    }
  }

  /**
   * Returns the current URL.
   */
  public static function getBaseUrl()
  {
    $server = esc_url( $_SERVER[SERVER_NAME] );
    $uri = esc_url( $_SERVER[REQUEST_URI] );

    $url = $server . $uri;

    return $url;
  }
}