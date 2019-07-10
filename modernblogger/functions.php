<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Modern Blogger Pro' );
define( 'CHILD_THEME_URL', 'https://my.studiopress.com/themes/modernblogger' );
define( 'CHILD_THEME_VERSION', '2.0' );

/** Customize the credits */
add_filter('genesis_footer_creds_text', 'footer_creds_filter');
function footer_creds_filter( $creds ) {
    $creds = 'Copyright [footer_copyright]  &middot; <a href="https://prettydarncute.com"> Modern Blogger Pro Theme</a> By, <a href="https://prettydarncute.com">Pretty Darn Cute Design</a>';
    return $creds;
}

/** Register widget areas */
genesis_register_sidebar( array(
    'id'            => 'portfolioblurb',
    'name'          => __( 'Portfolio Blurb', 'modernbloggerpro' ),
    'description'   => __( 'This is a widget area that can be shown above showcase', 'modernbloggerpro' ),
) );

genesis_register_sidebar( array(
    'id'            => 'slider',
    'name'          => __( 'Slider Widget', 'modernbloggerpro' ),
    'description'   => __( 'This widget area is for the genesis slider', 'modernbloggerpro' ),
) );

genesis_register_sidebar( array(
    'id'            => 'adspace',
    'name'          => __( 'Ad Space', 'modernbloggerpro' ),
    'description'   => __( 'This is a widget area for ads directly above the content sidebar area', 'modernbloggerpro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'modernbloggerpro' ),
	'description' => __( 'This is the after entry section.', 'modernbloggerpro' ),
) );

/** Ad Space Widget Area */
add_action( 'genesis_before_loop', 'adspace_before_loop' );
   function adspace_before_loop() {
if ( is_page() ) {
		return;
	}
      genesis_widget_area( 'adspace', array(
          'before' => '<div class="adspace">',
          'after' => '</div>',
      ) );

}

add_action( 'genesis_entry_footer', 'modernbloggerpro_after_entry_widget'  ); 
function modernbloggerpro_after_entry_widget() {

    if ( ! is_singular( 'post' ) )
    	return;

    genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
    ) );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add new image size
add_image_size( 'home-featured', 700, 400, TRUE );
add_image_size( 'widget-featured', 300, 200, TRUE );
add_image_size( 'header-featured', 640, 80, TRUE );
add_image_size( 'portfolio-featured', 300, 200, TRUE );
add_image_size( 'grid-featured', 320, 200, TRUE );

/** Greg's Threaded Comment Numbering */
add_action ('genesis_before_comment', 'child_numbered_comments');
function child_numbered_comments () {
if (function_exists('gtcn_comment_numbering'))
echo gtcn_comment_numbering($comment->comment_ID, $args);
}

/** Customize the read more link */
add_filter( 'get_the_content_more_link', 'custom_read_more_link' );
function custom_read_more_link() {
    return '... <a class="more-link" href="' . get_permalink() . '">[ Read More ]</a>';
}

/** Change the number of portfolio items to be displayed (props Bill Erickson) */
add_action( 'pre_get_posts', 'modernbloggerpro_portfolio_items' );
function modernbloggerpro_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

/** Create portfolio custom post type */
add_action( 'init', 'portfolio_post_type' );
function portfolio_post_type() {
    register_post_type( 'portfolio',
        array(
            'labels' => array(
                'name' => __( 'Portfolio' ),
                'singular_name' => __( 'Portfolio' ),
            ),
            'exclude_from_search' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'public' => true,
            'rewrite' => array( 'slug' => 'portfolio' ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'genesis-seo' ),
        )
    );
}

/** Add support for color options */
add_theme_support( 'genesis-style-selector', array( 'theme-tealpink' => 'Teal & Pink','theme-goldblack' => 'Gold & Black', 'theme-blackpink' => 'Black & Pink','theme-brighthappy' => 'Bright & Happy', 'theme-boldfun' => 'Bold & Fun') );

// Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

add_filter( 'genesis_comment_list_args', 'childtheme_comment_list_args' );
/**
 * Change size of comment avatars.
 * 
 * Value is side length of square avatar, in pixels.
 * 
 * @author ipstenu
 * @link   https://www.studiopress.com/forums/topic/change-gravatar-size/
 * 
 * @param array $args Existing comment settings.
 * 
 * @return array Amended comment settings.
 */
function childtheme_comment_list_args( $args ) {
    $args['avatar_size'] = 100;
	return $args;
}

//* Modify the comment link text in comments
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter( $post_info ) {
	return '[post_date] By: [post_author][post_comments zero="comment" one="1 Comment" more="% Comments"]';
}

//* Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Add Support for Woo Commerce
add_theme_support( 'genesis-connect-woocommerce' );
add_theme_support( 'woocommerce' );

//* Change the WordPress read more link
function new_excerpt_more($more) {
       global $post;
	return '<a class="moretag" href="'. get_permalink($post->ID) . '"> [...] </a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Register responsive menu script
add_action( 'wp_enqueue_scripts', 'prefix_enqueue_scripts' );
/**
 * Enqueue responsive javascript
 * @author Ozzy Rodriguez
 * @todo Change 'prefix' to your theme's prefix
 */
function prefix_enqueue_scripts() {
 
	wp_enqueue_script( 'prefix-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); // Change 'prefix' to your theme's prefix
 
}

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 720,
	'height'          => 328,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );
