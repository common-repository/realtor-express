<?php
/*
Plugin Name: Realtor Express
Plugin URI: http://pulsarwebdesign.com/realtor-express
Description: Easy listings management for realtors.
Version: 1.0.4
Author: Pulsar Web Design
Author URI: http://pulsarwebdesign.com
License: GPL2

Copyright 2011  Pulsar Web Design  (email : ty@pulsarwebdesign.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* This version does not use late static binding and can work on PHP 5.2 and higher */

/*
 * Define constants
 */
define('REX_VERSION', '1.0.4');

if ( ! defined( 'REX_PLUGIN_BASENAME' ) )
	define( 'REX_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'REX_PLUGIN_NAME' ) )
	define( 'REX_PLUGIN_NAME', trim( dirname( REX_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'REX_PLUGIN_DIR' ) )
	define( 'REX_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . REX_PLUGIN_NAME );

if ( ! defined( 'REX_PLUGIN_URL' ) )
	define( 'REX_PLUGIN_URL', WP_PLUGIN_URL . '/' . REX_PLUGIN_NAME );

if ( ! defined( 'REX_MAIN_PLUGIN_FILE' ) )
  define( 'REX_MAIN_PLUGIN_FILE' , WP_PLUGIN_DIR . '/' . REX_PLUGIN_BASENAME );

require_once REX_PLUGIN_DIR . '/includes/RexApp.php';

RexApp::init();