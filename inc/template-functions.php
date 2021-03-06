<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package twentysixteen_indieweb
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function twentysixteen_body_classes( $classes ) {
        // Adds a class of custom-background-image to sites with a custom background image.	      
       	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';		
	}
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
		$classes[] = 'h-feed';
	} else {
		if ( 'page' !== get_post_type() ) {
				$classes[] = 'hentry';
				$classes[] = 'h-entry';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function twentysixteen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'twentysixteen_pingback_header' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function twentysixteen_post_classes( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );
	if ( ! is_singular() ) {
		if ( 'page' !== get_post_type() ) {
			// Adds a class for microformats v2
			$classes[] = 'h-entry';
			// add hentry to the same tag as h-entry
			$classes[] = 'hentry';
		}
	}
	return $classes;
}

add_filter( 'post_class', 'twentysixteen_post_classes' );

/**
 * Adds mf2 to avatar
 *
 * @param array             $args Arguments passed to get_avatar_data(), after processing.
 * @param int|string|object $id_or_email A user ID, email address, or comment object
 * @return array $args
 */
function twentysixteen_get_avatar_data($args, $id_or_email) {
	if ( ! isset( $args['class'] ) ) {
		$args['class'] = array();
	} 
	if ( ! in_array( 'u-featured', $args['class'] ) ) {
		$args['class'][] = 'u-photo';
	}
	return $args;
}

add_filter( 'get_avatar_data', 'twentysixteen_get_avatar_data', 11, 2 );

/**  
 * Wraps the_content in e-content
 *
 */
function twentysixteen_the_content( $content ) {
	$wrap = '<div class="entry-content e-content">';
	if ( empty( $content ) ) {
		return $content;
	}
	return $wrap . $content . '</div>';
}
add_filter( 'the_content', 'twentysixteen_the_content', 1 );

/**
 * Wraps the_excerpt in p-summary
 *
 */
function twentysixteen_the_excerpt( $content ) {
	$wrap = '<div class="entry-summary p-summary">';
	if ($content!="") {
		return $wrap . $content . '</div>';
	}
	return $content;
}
add_filter( 'the_excerpt', 'twentysixteen_the_excerpt', 1 );

