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
	</div>
	<?php
	$hkail_obj->echoLinkOption();
	$hkail_obj->echoLinkList();
}

?>
