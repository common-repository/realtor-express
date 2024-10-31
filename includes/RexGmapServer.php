<?php
/**
 * @file
 * Handles ajax requests related to Google Maps.
 */

$dir = (__DIR__ == '__DIR__') ? dirname(__FILE__) : __DIR__;

require_once $dir . '/../../../../wp-config.php';
require_once $dir . '/../../../../wp-includes/functions.php';
require_once $dir . '/../../../../wp-includes/pluggable.php';

if ( isset( $_POST['addressString'] ) && !empty( $_POST['addressString'] ) && isset( $_REQUEST['wpnonce'] ) ) {
  $json = getListingJson( $_POST['addressString'], $_REQUEST['wpnonce'] );
  echo $json;
}

function getListingJson( $address_string, $nonce ) {
  $valid_request = wp_verify_nonce( $nonce, 'listing-ajax-validation' );
  if ($valid_request === false)
    return;
  
  $gmap_query = $_POST['addressString'];
  $json_reply = file_get_contents( $gmap_query );
  return $json_reply;
}