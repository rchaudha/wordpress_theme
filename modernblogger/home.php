<?php

add_action( 'genesis_meta', 'modernbloggerpro_home_genesis_meta' );
/**
 * Add widget support for homepage.
 *
 */
function modernbloggerpro_home_genesis_meta() {

	if ( is_active_sidebar( 'slider' )) {
	
		add_action( 'genesis_before_loop', 'modernbloggerpro_home_loop_helper' );

	}
}

/** Remove the ad widget **/
remove_action( 'genesis_before_loop', 'adspace_before_loop' );

/**
 * Display widget content for home slider section
 *
 */
function modernbloggerpro_home_loop_helper() {

	if ( is_active_sidebar( 'slider' )) {

		echo '<div id="slider">';
		echo '<div class="slider">';
		dynamic_sidebar( 'slider' );
		echo '</div><!-- end .slider -->';
	        echo '</div><!-- end #slider -->';

	}

}

genesis();
