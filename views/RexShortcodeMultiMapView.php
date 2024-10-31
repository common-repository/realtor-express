<?php
/*
 * Shortcode Multi Map View.
 *
 * Available variables:
 *  - $width - map width
 *  - $height - map height
 *  - $locations_json_array - JSON with listings information to be used in updateMultiMap().
 */
?>

<script>
	window.onload = initMaps;
	function initShortcodeMultiMap() {
		updateMultiMap(<?php echo $locations_json_array; ?>, 'rex-multi-map', true)
	}
</script>

<div id="rex-multi-map"><?php esc_html_e('Map not available', 'realtor-express'); ?></div>
