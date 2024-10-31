<?php
/**
 * @file
 * Handles ajax requests related to the listing gallery.
 */

require_once __DIR__ . '/../../../../wp-config.php';
require_once __DIR__ . '/../../../../wp-includes/functions.php';
require_once __DIR__ . '/../../../../wp-includes/pluggable.php';
require_once __DIR__ . '/../models/RexListing.php';

if ( isset( $_POST['postID'] ) && !empty( $_POST['postID']) && isset( $_REQUEST['wpnonce'] ) ) {
  $html = getGalleryHtml( $_POST['postID'], $_REQUEST['wpnonce'] );
  echo $html;
}

function getGalleryHtml( $post_id, $nonce ) {
  $valid_request = wp_verify_nonce( $nonce, 'listing-ajax-validation' );
  if ($valid_request === false)
    return;
  
  global $wpdb;
  $post_id = preg_replace("/[^0-9]/", "", $_POST['postID'] );
  $listing = new RexListing( $post_id );
  if ( ! $listing instanceof RexListing )
    return;
    
  $gallery_images = $listing->getListingGallery();
  if ( empty( $gallery_images ) )
    return;
  
  $output = $listing->getGalleryThumbsHtml();
  				
  return $output;
}