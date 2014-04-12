<?php 
//声明对woocommerce支持
add_theme_support( 'woocommerce' );
// remove woocommer default css
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/* register menu */
add_action( 'after_setup_theme', 'delicious_menu_setup' );
if ( ! function_exists( 'delicious_menu_setup' ) ):
    	function delicious_menu_setup() { 
    		register_nav_menu( 'primary', __( 'Primary Menu', 'wptuts' ) );
    	} 
endif;

?>