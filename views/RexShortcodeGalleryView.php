<?php
/*
 * Shortcode Gallery View.
 *
 * Available variables:
 *  - $listing - listing object
 *  - $gallery_images - images array
 */
?>

<div id="rex-gallery" style="width:<?php echo esc_attr( $width ); ?>;" >
  <span id="plugin-url" class="hiddenjs"><?php echo REX_PLUGIN_URL; ?></span>
	<?php foreach( $gallery_images as $image ): ?>
	<a href="<?php echo esc_attr( $image['large'] ); ?>" rel="lightbox[gallery]"><img src="<?php echo esc_attr( $image['thumb'] ); ?>" width="<?php echo esc_attr( $thumb_width ); ?>" /></a>
	<?php endforeach; ?>
</div>