<?php
/**
 * Shortcodes used in theme
 *
 * @package Essential
 * @since Essential 1.0
 */
 
add_shortcode('box', 'box');
function box($atts, $content = null) {
	return '<div class="essenital-shortcode box">' . $content . '</div>';
}

add_shortcode('hr', 'hr');
function hr($atts, $content = null) {
	return '<hr class="essenital-shortcode"/>';
}

add_shortcode('button', 'button');
function button($atts, $content = null) {
	extract(shortcode_atts(array("url" => '', "color" => ''), $atts));
	return '<a class="essenital-shortcode button ' . $color . '" href="' . $url . '">' . $content . '</a>';
}

add_shortcode('small_button', 'small_button');
function small_button($atts, $content = null) {
	extract(shortcode_atts(array("url" => '', "color" => ''), $atts));
	return '<a class="essenital-shortcode smal-button ' . $color . '" href="' . $url . '">' . $content . '</a>';
}

add_shortcode('big_button', 'big_button');
function big_button($atts, $content = null) {
	extract(shortcode_atts(array("url" => '', "color" => ''), $atts));
	return '<a class="essenital-shortcode  big-button ' . $color . '" href="' . $url . '">' . $content . '</a>';
}

add_shortcode('youtube', 'youtube');
function youtube($atts, $content = null) {
	extract(shortcode_atts(array("width" => '610', "height" => '420', "align" => ''), $atts));
	$video_code ='<div class="essenital-shortcode essential-video-'.$align.'" rel="showdowbox"><object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/' . $content . 'fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $content . '?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object></div> <div class="clearboth"></div>'; 
	if( is_ssl() )
            { $video_code = str_replace( 'http://', 'https://', $video_code ); }
	return $video_code;
}

add_shortcode('icon', 'icon');
function icon($atts, $content = null) {
	extract(shortcode_atts(array("character" => '', "color" => '', "size" => ''), $atts));
	return '<span data-icon="' . $character . '" style="color:' . $color . '; font-size:' . $size . '"></span>';
}

add_shortcode('two-column', 'two_column');
function two_column($atts, $content = null) {
	$output = do_shortcode($content);
	return '<div class="essenital-shortcode two-column">' . $output . '</div>';
}

add_shortcode('two-column-last', 'two_column_last');
function two_column_last($atts, $content = null) {
	$output = do_shortcode($content);
	return '<div class="essenital-short codetwo-column-last">' . $output . '</div><div class="clear"></div>';
}

add_shortcode('three-column', 'three_column');
function three_column($atts, $content = null) {
	$output = do_shortcode($content);
	return '<div class="essenital-shortcode three-column">' . $output . '</div>';
}

add_shortcode('three-column-last', 'three_column_last');
function three_column_last($atts, $content = null) {
	$output = do_shortcode($content);
	return '<div class="essenital-shortcode three-column-last">' . $output . '</div><div class="clear"></div>';
}

add_shortcode('four-column', 'four_column');
function four_column($atts, $content = null) {
	$output = do_shortcode($content);
	return '<div class="essenital-shortcode four-column">' . $output . '</div>';
}

add_shortcode('four-column-last', 'four_column_last');
function four_column_last($atts, $content = null) {
	$output = do_shortcode($content);
	return '<div class="essenital-shortcode four-column-last">' . $output . '</div><div class="clear"></div>';
}
add_shortcode('hidden', 'essential_hidden');
function essential_hidden($atts, $content = null) {
	$output = do_shortcode($content);
	extract(shortcode_atts(array("show" => ''), $atts));
	return '<div class="essential-hidden '.$show.'">' . $output . '</div>';
}