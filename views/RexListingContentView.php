<?php
/**
 * @file
 * Defines the view of custom excerpt displayed on the listing search result
 *
 * Available variables:
 *  - $listing - the listing object.
 *  - $thumbnail - post thumbnail.
 *  - $permalink - link to the post.
 *  - $floor_space_units - Sqm or Sqft based on settings.
 */
?>

<div id="rex-listing-content">
	<div id="rex-listing-thumbnail" class="rex-listing-div">
		<a href="<?php echo esc_url( $permalink ); ?>"><?php echo $thumbnail; ?></a>
	</div>
	<div id="rex-listing-details" class="rex-listing-div">
		<ul>
			<li>
				<?php esc_html_e('Price: ', 'realtor-express'); ?>
				<?php echo esc_html( $listing->getPrice() ); ?>
			</li>
			<li>
				<?php esc_html_e('Bedrooms: ', 'realtor-express'); ?>
				<?php echo esc_html( $listing->bedrooms ); ?>
			</li>
			<li>
				<?php esc_html_e('Baths: ', 'realtor-express'); ?>
				<?php echo esc_html( $listing->baths ); ?>
			</li>
			<li>
				<?php esc_html_e('Floor Space: ', 'realtor-express'); ?>
				<?php echo esc_html( $listing->floor_space ) . " " . $floor_space_units; ?>
			</li>
		</ul>
	</div>
	<div class="float-breaker"></div>
</div>

