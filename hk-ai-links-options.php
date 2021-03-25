<?php
add_action('admin_init', 'hkail_options_init' );
add_action('admin_menu', 'hkail_options_add_page');

// Init plugin options to white list our options
function hkail_options_init() {
	register_setting( 'hkail_options_options', 'hkail_options', 'hkail_options_validate' );
}

// Add menu page
function hkail_options_add_page() {
	add_options_page('AI Links Settings', 'AI Links Settings', 'manage_options', 'hkail_options', 'hkail_options_do_page') ;
}



// Draw the menu page itself
function hkail_options_do_page() {
	global $wpdb;
	global $hkail_obj;
	?>
	<div class="wrap">
		<h2>Settings for AI links</h2>
		<?php /*
		<form id="form_hkail_options" method="post" action="options.php">


			<?php settings_fields('hkail_options_options'); ?>
			<?php $options = get_option('hkail_options');
			//echo "<br>update_categories  : " . $options["update_categories"];
			//echo "<br>enable_cron  : " . $options["enable_cron"];
			//echo "<br>update_products  : " . $options["update_products"];
			?>
			<p><label for="hkail_options[hkail_iframe_src]">Iframe att bädda in</label><br/>
			<input size="80" type="text" name="hkail_options[hkail_iframe_src]" value="<?php echo $options['hkail_iframe_src']; ?>" /></p>
			<p><label for="hkail_options[hkail_iframe_src]">Avatar / Ikon</label><br/>
			<input size="80" type="text" name="hkail_options[hkail_avatar_src]" value="<?php echo $options['hkail_avatar_src']; ?>" /></p>
			<p><label for="hkail_options[hkail_iframe_src]">Chat-text (som alt-text eller om ingen avatar är inlagd)</label><br/>
			<input size="80" type="text" name="hkail_options[hkail_button_title]" value="<?php echo $options['hkail_button_title']; ?>" /></p>
			<p><label for="hkail_options[hkail_bubble_text]">Bubble text</label><br/>
			<input size="80" type="text" name="hkail_options[hkail_bubble_text]" value="<?php echo $options['hkail_bubble_text']; ?>" /></p>


			<?php submit_button(); ?>
		</form> */ ?>
	</div>
	<?php
	//$hkail_obj->createDatabases();
	$hkail_obj->echoLinkOption();
	$hkail_obj->echoLinkList();

}



?>
