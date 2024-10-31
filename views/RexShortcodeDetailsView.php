<?php
/*
 * Shortcode Map View.
 *
 * Available variables:
 *	- $listing - listing object
 *  - $floor_space_units - Sqm or Sqft based on settings.
 *  - $table_styles - table styles.
 */
?>
<div id="rex-details">
	<table class="rex-table" <?php echo $table_styles; ?>>
		<tr>
			<td><?php esc_html_e( 'Listing Number', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->listing_number ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Price', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->getPrice() ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Bedrooms', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->bedrooms ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Baths', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->baths ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Floor Space', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->floor_space ) . " " . $floor_space_units; ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Year Built', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->year_built ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Address', 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->formatted_address ); ?></td>
		</tr>
	</table>
</div>