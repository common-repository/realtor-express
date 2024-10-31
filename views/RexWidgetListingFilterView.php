<?php
/**
 * @file
 * Listing filter form view.
 *
 * Available variables:
 *  - $select_plus_array - An array to be used in the bedrooms, baths select list.
 *  - $min_price - current min price search value.
 *  - $max_price - current max price search value.
 *  - $bedrooms - current bedrooms search value.
 *  - $baths - current baths search value.
 *  - $status_array - An array to be used in the status select list (e.g. For sale, for rent, etc...)
 *  - $status - current status search value.
 *  - $types_array - An array to be used in the types select list.
 *  - $type - current type search value.
 *  - $style_array - An array to be used in the styles select list.
 *  - $style - current style search value.
 */
?>

<form role="search" method="get" id="rex-searchform" action="<?php echo home_url( '/' ); ?>" >
	<div>
		<table id="rex-listing-filter-table">
			<tr>
				<td><?php esc_html_e('Price:'); ?></td>
				<td>
					<input type="text" value="<?php echo esc_attr( $min_price ); ?>" name="rex_filter_min_price" id="rex_filter_min_price" size="6" placeholder="<?php esc_attr_e('Min'); ?>" />
					<input type="text" value="<?php echo esc_attr( $max_price ); ?>" name="rex_filter_max_price" id="rex_filter_max_price" size="6" placeholder="<?php esc_attr_e('Max'); ?>" />
				</td>
			</tr>
			<tr>
				<td><label class="screen-reader-text" for="rex_filter_bedrooms"><?php esc_html_e('Bedrooms:'); ?></label></td>
				<td><?php RexHtml::generateSelectList( $select_plus_array, $bedrooms, array( 'name' => 'rex_filter_bedrooms' ), true ); ?></td>
			</tr>
			<tr>
				<td><label class="screen-reader-text" for="rex_filter_baths"><?php esc_html_e('Baths:'); ?></label></td>
				<td><?php RexHtml::generateSelectList( $select_plus_array, $baths, array( 'name' => 'rex_filter_baths' ), true ); ?></td>
			</tr>
			<?php if ( ! empty( $types_array ) ): ?>
			<tr>
				<td><label class="screen-reader-text" for="rex_filter_type"><?php esc_html_e('Type:'); ?></label></td>
				<td><?php RexHtml::generateSelectList( $types_array, $type, array( 'name' => 'rex_filter_type' ), true ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if ( ! empty( $style_array) ): ?>
			<tr>
				<td><label class="screen-reader-text" for="rex_filter_style"><?php esc_html_e('Style:'); ?></label></td>
				<td><?php RexHtml::generateSelectList( $style_array, $style, array( 'name' => 'rex_filter_style' ), true ); ?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td><label class="screen-reader-text" for="rex_filter_status"><?php esc_html_e('Status:'); ?></label></td>
				<td><?php RexHtml::generateSelectList( $status_array, $status, array( 'name' => 'rex_filter_status' ), true ); ?></td>
			</tr>
		</table>

		<div>&nbsp;</div>

		<input type="submit" id="" value="<?php esc_attr_e('Find Listings'); ?>" />
		<a id="rex-listing-filter-reset" href="" onclick="return false"><?php esc_html_e('Reset fields', 'realtor-express'); ?></a>
		<input type="hidden" name="rex-filter" value="true" />
		<input type="hidden" name="s" value="" />
	</div>
</form>