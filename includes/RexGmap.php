<?php
/*
 * @file
 * Defines the RexGmap class which is responsible for parsing address using Google Maps.
 */
class RexGmap
{
  /**
   * Returns an array of properties returned from querying the Google Maps API.
   * 
   * @param string $address
   * @return google maps object $gmap_address
   */
  public static function parseAddress( $address )
  {
    $address = preg_replace( "/[\r\n]/", " ", $address );
    $address = preg_replace( "/[^A-Za-z0-9 ]/", "", $address );
    $encoded_url = urlencode( $address );
    $final_query = str_replace( '++', '+', $encoded_url );
    $gmap_query = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $final_query . '&sensor=false';
    
    $json_reply = file_get_contents( $gmap_query );
    $gmap_address = json_decode( $json_reply );
      
    return $gmap_address;
  }
}