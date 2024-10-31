<?php
  /*
   * The listings view form.
   *
   * Available vars:
   *  - $listing - listing object.
   *  - $status_array - array of possible status to be used in the status select list.
   *  - $my_local_settings - array of local settings such as currency symbol.
   *  - $floor_space_units - Sqm or Sqft based on settings.
   */
?>
<span id="plugin-url" class="hiddenjs"><?php echo REX_PLUGIN_URL; ?></span>
<div id="listing-form-left" class="listing-form-col">
	<table id="rex-listing-form">
		<tr>
			<!-- Address Field -->
			<td><label for="rex_address"><?php esc_html_e("Address:", 'realtor-express' ); ?></label></td>
			<td><textarea cols="30" rows="3" name="rex_address" id="rex_address"><?php echo esc_html( $listing->address ); ?></textarea></td>
		</tr>
		<tr>
			<td><?php esc_html_e('Address Status:', 'realtor-express'); ?></td>
			<td id="rex-address-status"><?php esc_html_e('No address', 'realtor-express'); ?></td>
		</tr>

		<tr>
			<!-- Listing Field -->
			<td><label for="rex_listing_number"><?php esc_html_e("Listing Number:", 'realtor-express' ); ?></label></td>
			<td><input type="text" id="rex_listing_number" name="rex_listing_number" value="<?php echo esc_attr( $listing->listing_number ); ?>" size="20" /></td>
		</tr>
		<tr>
			<!-- Status Field -->
			<td><label for="rex_status"><?php esc_html_e("Status:", 'realtor-express' ); ?></label></td>
			<td><?php RexHtml::generateSelectList( $status_array, $listing->status, array( 'name' => 'rex_status' ), true ); ?></td>
		</tr>
		<tr>
			<!-- Price Field -->
			<td><label for="rex_price"><?php esc_html_e("Price:", 'realtor-express' ); ?></label></td>
			<td><?php echo $my_local_settings['currency_symbol']; ?> <input type="text" id="rex_price" name="rex_price" value="<?php echo esc_attr( $listing->price ); ?>" size="10" /> <?php echo $my_local_settings['int_curr_symbol']; ?></td>
		</tr>
		<tr>
			<!-- Bedrooms Field -->
			<td><label for="rex_bedrooms"><?php esc_html_e("Bedrooms:", 'realtor-express' ); ?></label></td>
			<td><?php RexHtml::generateSelectList( range( 1, 20, 0.5 ), $listing->bedrooms, array( 'name' => 'rex_bedrooms' ) ); ?></td>
		</tr>
		<tr>
			<!-- Baths Field -->
			<td><label for="rex_baths"><?php esc_html_e("Baths:", 'realtor-express' ); ?></label></td>
			<td><?php RexHtml::generateSelectList( range( 1, 20, 0.5 ), $listing->baths, array( 'name' => 'rex_baths' ) ); ?></td>
		</tr>
		<tr>
			<!-- Floor Space Field -->
			<td><label for="rex_floor_space"><?php esc_html_e("Floor Space:", 'realtor-express' ); ?></label></td>
			<td><input type="text" id="rex_floor_space" name="rex_floor_space" value="<?php echo esc_attr( $listing->floor_space ); ?>" size="10" /> <?php echo esc_html( $floor_space_units ); ?></td>
		</tr>
		<tr>
			<!-- Year Build Field -->
			<td><label for="rex_year_built"><?php esc_html_e("Year Built:", 'realtor-express' ); ?></label></td>
			<td><input type="text" id="rex_year_built" name="rex_year_built" value="<?php echo esc_attr( $listing->year_built ); ?>" size="5" /></td>
		</tr>

		<tr>
			<td><a href="#" id="rex-media-uploader-link" class="thickbox add_media" onclick="return false;">Images:</a></td>
		<?php if ( $listing->isGalleryEmpty() ): ?>
			<td id="rex-listing-gallery"><?php esc_html_e( 'Use the standard media uploader to attach images to this listing.', 'realtor-express' ); ?></td>
		<?php else: ?>
			<td id="rex-listing-gallery">
				<?php echo $listing->getGalleryThumbsHtml(); ?>
			</td>
			<?php endif; ?>
		</tr>
	</table>
</div> <!--end of listing-form-left-->

<div id="listing-form-right" class="listing-form-col">
	<table>
		<!-- Address Information (Gmaps) -->
		<tr>
			<td><?php esc_html_e("Formatted Address:", 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->formatted_address ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e("Locality:", 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->locality ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e("Country:", 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->country ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e("Postal Code:", 'realtor-express' ); ?></td>
			<td><?php echo esc_html( $listing->postal_code ); ?></td>
		</tr>
		<?php if ($listing->getLatitude() != false && $listing->getLongitude() != false): ?>
		<tr>
			<td><?php esc_html_e("Latitude:", 'realtor-express' ); ?></td>
			<td id="rex-listing-lat"><?php echo esc_html( $listing->getLatitude() ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e("Longitude:", 'realtor-express' ); ?></td>
			<td id="rex-listing-lng"><?php echo esc_html( $listing->getLongitude() ); ?></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td><?php esc_html_e("Map:", 'realtor-express' ); ?></td>
			<td><div id="rex-admin-map">Location data not available.</div></td>
		</tr>
	</table>
</div> <!--end of listing-form-right-->

<div class="float-breaker"></div>
<?php RexListing::generateLocalAddressStrings(); ?>
<?php wp_nonce_field('listing-ajax-validation', 'listing-ajax-validation'); ?>