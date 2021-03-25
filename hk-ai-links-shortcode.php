<?php


function hkail_most_click_shortcode_func( $atts ) {
  $atts = shortcode_atts( array(
		'numLinks' => '5',
    'text' => 'Här dyker dina mest klickade länkar upp.'
	), $atts );

	$ret = "<div class='hkail_most_click_wrapper' data-num-links='".$atts['numLinks']."'>".$atts['text']."</div>";
  return $ret;
}
add_shortcode( 'most_clicked_links', 'hkail_most_click_shortcode_func' );


function hkail_most_click_shortcode_v2_func( $atts ) {
  $atts = shortcode_atts( array(
    'numLinks' => '5',
    'text' => 'Här dyker dina mest klickade länkar upp.'
	), $atts );

	$ret = "<div class='hkail_most_click_wrapper_v2' data-num-links='".$atts['numLinks']."'>".$atts['text']."</div>";
  return $ret;
}
add_shortcode( 'hkail_most_clicked_links', 'hkail_most_click_shortcode_v2_func' );

function hkail_links_func( $atts ) {
  global $hkail_obj;

  $atts = shortcode_atts( array(
		'columns' => '2',
    'groups' => ''
	), $atts );
  return $hkail_obj->getLinkList($atts['columns'], $atts['groups']);
}
add_shortcode( 'hkail_links', 'hkail_links_func' );
