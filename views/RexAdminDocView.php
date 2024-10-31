<?php
/*
 * The documentation/help view.
 *
 * Available variables:
 *  - $plugin_page - plugin page slug.
 *  - $wrap_class - name of css class to be added to the wrap div.
 */
?>

<div class="wrap <?php echo $wrap_class; ?>">
	<h2><?php esc_html_e('Documentation', 'realtor-express'); ?></h2>

	<h3><?php esc_html_e('Shortcodes:', 'realtor-express'); ?></h3>
	<p><strong><?php esc_html_e('TIP:', 'realtor-express'); ?></strong> <?php esc_html_e('Instead of using the shortcodes directly, use the do_shortcode() function inside a template file (e.g. single-rex_listing.php). This way the widget will be displayed on every listing and will save you the trouble of adding shortcodes manually for every listing.', 'realtor-express'); ?></p>
	<table class="rex-admin-table">
		<tr>
			<th><?php esc_html_e('Shortcode', 'realtor-express'); ?></th>
			<th><?php esc_html_e('Description', 'realtor-express'); ?></th>
			<th><?php esc_html_e('Parameters', 'realtor-express'); ?></th>
			<th><?php esc_html_e('Example', 'realtor-express'); ?></th>
		</tr>
		<tr>
			<td><code>[rex_gallery]</code></td>
			<td><?php esc_html_e('Displays the gallery for the current listing.', 'realtor-express'); ?></td>
			<td>
				width <span class="description">(<?php esc_html_e('gallery width in pixels', 'realtor-express'); ?>)</span> <br/>
				thumb <span class="description">(<?php esc_html_e('thumbnails width in pixels', 'realtor-express'); ?>)</span>
			</td>
			<td><code>[rex_gallery width=300 thumb=80]</code></td>
		</tr>
		<tr>
			<td><code>[rex_map]</code></td>
			<td><?php esc_html_e('Displays a Google map of the current listing.', 'realtor-express'); ?></td>
			<td>
				width <span class="description">(<?php esc_html_e('map width in pixels', 'realtor-express'); ?>)</span> <br/>
				height <span class="description">(<?php esc_html_e('height width in pixels', 'realtor-express'); ?>)</span>
			</td>
			<td><code>[rex_map width=400 height=300]</code></td>
		</tr>
		<tr>
			<td><code>[rex_multi_map]</code></td>
			<td><?php esc_html_e('Displays a Google map with all (or some) of the listings.', 'realtor-express'); ?></td>
			<td>
				limit <span class="description">(<?php esc_html_e('max number of listings to display', 'realtor-express'); ?>)</span>
				width <span class="description">(<?php esc_html_e('map width in pixels', 'realtor-express'); ?>)</span> <br/>
				height <span class="description">(<?php esc_html_e('height width in pixels', 'realtor-express'); ?>)</span>
			</td>
			<td><code>[rex_multi_map limit=50 width=400 height=300]</code></td>
		</tr>
		<tr>
			<td><code>[rex_details]</code></td>
			<td><?php esc_html_e('Displays a table with the current listing details.', 'realtor-express'); ?></td>
			<td>
				width <span class="description">(<?php esc_html_e('table width in pixels', 'realtor-express'); ?>)</span> <br/>
			</td>
			<td><code>[rex_details width=400]</code></td>
		</tr>
		<tr>
			<td><code>[rex_listings]</code></td>
			<td><?php esc_html_e('Displays a paginated list of listings.', 'realtor-express'); ?></td>
			<td>
				listings_per_page <span class="description">(<?php esc_html_e('number of listings to display per page', 'realtor-express'); ?>)</span> <br/>
			</td>
			<td><code>[rex_listings listings_per_page=20]</code></td>
		</tr>
	</table>

	<h3><?php esc_html_e('About listing types and styles:', 'realtor-express'); ?></h3>
	<p><?php esc_html_e( 'Use listing types and style to help users find what they\'re looking for. Examples of listing types include "Condo", "Duplex", and "Townhouse". Examples of listing styles include "Detached" and "Semi-detached". Once you add a type or a style, it will be available in the listings filter (search) widget. If you remove all the terms of either the types or the styles, the corresponding field will not be available in the filter widget.', 'realtor-express' ); ?></p>

	<h3><?php esc_html_e('Locale and price format', 'realtor-express'); ?></h3>
	<p><?php esc_html_e('Currency and price format are determined by the locale setting of the site. In order to change the locale, set the WPLANG constant in wp-config.php to the appropriate locale: ', 'realtor-express' ); ?>
		<code>define('WPLANG', '[locale]');</code>.
		<?php esc_html_e('For example, in order to get Realtor Express to use Canadian Dollars, set the WPLANG constant to "en_CA" and to get it to use British Pounds, set it to "en_GB". For more information visit ', 'realtor-express'); ?><a href="http://codex.wordpress.org/WordPress_in_Your_Language" target="_blank"><?php esc_html_e( 'WordPress in Your Language', 'realtor-express' ); ?></a></p>

	<h3><?php esc_html_e('Widgets', 'realtor-express'); ?></h3>
	<p><?php esc_html_e('Currently, Realtor Express provides 2 widgets: "REX Listing Filter" and "REX Multi Map". The listing filter provides a simple form to allow users to filter listings based on specific parameters such as number of bedrooms or listing type. The multi map displays a mini-map (Google Map) of the site listings.', 'realtor-express'); ?></p>
</div>