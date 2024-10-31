<?php
/*
 * Shortcode Map View.
 *
 * Available variables:
 *	- $listing - listing object
 *  - $width - map width
 *  - $height - map height
 */
?>

<script>
	window.onload = initShortcodeMap;
	function initShortcodeMap() {
		updateRexAdminGmap(<?php echo esc_js( $listing->getLatitude() ); ?>, <?php echo esc_js( $listing->getLongitude() ); ?>, 'rex-map', <?php echo esc_js( $width ); ?>, <?php echo esc_js( $height ); ?>);
	}
</script>

<div id="rex-map"><?php esc_html_e('Map not available', 'realtor-express'); ?></div>
