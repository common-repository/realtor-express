<?php
/**
 * @file
 * Options form template.
 */
?>
<div id="wcs-options-form" class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php esc_html_e('Realtor Express Options', 'realtor-express'); ?></h2>
	<form action="options.php" method="post">
		<?php do_settings_sections( 'rex-settings' ); ?>

		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'realtor-express'); ?>" />
		</p>

	<?php settings_fields( 'rex_settings' ); ?>
	</form>
</div><!-- wrap -->