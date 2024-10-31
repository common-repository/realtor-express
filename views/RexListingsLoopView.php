<?php
/**
 * @file
 * Listings loop view.
 *
 * Available variables:
 *  - $listing
 *  - $permalink
 *  - $thumbnail
 *  - $floor_space_units
 *  - $last_class - if this is the last post, this variable will be 'last'.
 */
?>
<article id="listing-<?php echo get_the_ID(); ?>" class="rex-listing post <?php echo esc_attr( $last_class ); ?>">
	<header class="listing-header">
		<h1 class="rex-listing-title entry-title"><a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a></h1>
	</header>

	<div class="listing-content">
		<?php include REX_PLUGIN_DIR . '/views/RexListingContentView.php'; ?>
	</div>
</article>
