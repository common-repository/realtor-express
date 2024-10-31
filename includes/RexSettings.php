<?php
/**
 * @file
 * Defines the RexSettings class.
 */
class RexSettings
{
  public static function renderSettingsPage()
  {
    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
      $settings = get_settings_errors();
      if ( ! empty( $settings) ) {
        foreach ( $settings as $value ) {
          RexHtml::show_wp_message( $value['message'], $value['type'] );
        }
      }
    }

    require_once REX_PLUGIN_DIR . '/views/RexSettingsView.php';
  }

  /* Register all settings sections and fields. */
  public static function rex_settings()
  {
    /* Add General Settings */
    add_settings_section(
      'rex_general_settings',
      __( 'General Settings', 'realtor-express' ),
      array('RexSettings', 'general_settings_section'),
      'rex-settings'
    );

    $title = __( 'Floor space units', 'realtor-express' );

    add_settings_field(
    	'rex_floor_space_units',
    	"$title:<br/>",
      array('RexSettings', 'floor_space_units_callback' ),
    	'rex-settings',
    	'rex_general_settings'
    );

    /* Register general settings */
    register_setting( 'rex_settings', 'rex_floor_space_units' );
  }

  /* Render General Settings section */
  public static function general_settings_section()
  {
    /* Set default values */
    add_option( 'rex_floor_space_units', 'metric' );
  }

  public static function floor_space_units_callback()
  {
    $metric_checked = get_option( 'rex_floor_space_units' ) == 'metric' ? "checked='checked'" : '';
    $imperial_checked = get_option( 'rex_floor_space_units' ) == 'imperial' ? "checked='checked'" : '';

    echo "<input type='radio' name='rex_floor_space_units' value='metric' $metric_checked /> " . __( 'Metric', 'realtor-express' ) . "<br/>";
    echo "<input type='radio' name='rex_floor_space_units' value='imperial' $imperial_checked /> " . __( 'Imperial', 'realtor-express' );
  }
}