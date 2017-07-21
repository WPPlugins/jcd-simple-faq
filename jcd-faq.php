<?php
   /*
   Plugin Name: JCD Simple FAQ
   Plugin URI: http://www.jocoxdesign.co.uk/wordpress-plugins/jcd-simple-faq
   Description: JCD Simple FAQ is a lightweight plugin which adds FAQs to your WordPress site. Create questions easily via the dashboard and then display them via shortcode as a basic list or accordion. You have control over the output, including ordering, categorisation and styling. Let the theme inform the appearance of your FAQs so that they blend in seamlessly, or use one of the built in accordion skins.
   Version: 2.0
   Author: Jo Cox Design
   Author URI: http://www.jocoxdesign.co.uk
   License: GPL2
   */

/**********************************************************/
/* ENQUEUE SCRIPTS
/**********************************************************/
function jcdfaq_enqueue() {
    
    wp_register_style('jcdfaq-style', plugin_dir_url( __FILE__ ) . 'style.css');
    wp_enqueue_style('jcdfaq-style');
    
    wp_register_style('jcdfaq-fontawesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css');
    wp_enqueue_style('jcdfaq-fontawesome');
 
    wp_register_script('jcdfaq-js', plugin_dir_url( __FILE__ ) . 'js/faq.js', array('jquery-ui-accordion'), '', true);
    wp_enqueue_script('jcdfaq-js');
    
}

add_action( 'wp_enqueue_scripts', 'jcdfaq_enqueue' );

/**********************************************************/
/* REGISTER THE POST TYPE
/**********************************************************/
if (!function_exists('create_faq')) {
    function create_faq()
    {
        $faq_args = array(
            'label' => __('FAQs', 'jcd'),
            'singular_label' => __('FAQ', 'jcd'),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'taxonomies' => array('category'),
            'hierarchical' => true,
            'rewrite' => true,
            'menu_icon' => 'dashicons-format-status',
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'));
        register_post_type('faq', $faq_args);
    }

    add_action('init', 'create_faq');
}

/**********************************************************/
/* BASIC SHORTCODE
/**********************************************************/
function jcdfaq_basic($atts, $content = null) {
    
    extract(shortcode_atts(array(
        'theme' => '',
        'cat' => '',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'limit' => '9999'
    ), $atts));

	$posts = get_posts(array(
        'category_name' => $cat,
        'numberposts' => $limit,
		'orderby' => $orberby,
        'order' => $order,
        'post_type' => 'faq',
    ));
 
    $faq  = '<div id="faq">';
	foreach ( $posts as $post ) { 
		$faq .= sprintf(('<h3 class="question">%1$s</h3><div class="answer">%2$s</div>'),
			$post->post_title,
			wpautop($post->post_content)
		);
	}
	$faq .= '</div>';
 
    return $faq;
}

add_shortcode('faq', 'jcdfaq_basic' );

/**********************************************************/
/* ACCORDION SHORTCODE
/**********************************************************/
function jcdfaq_accordion($atts, $content = null) {
    
    extract(shortcode_atts(array(
        'theme' => '',
        'cat' => '',
        'orderby' => 'title',
        'order' => 'ASC',
        'limit' => '9999'
    ), $atts));

	$posts = get_posts(array(
        'category_name' => $cat,
        'numberposts' => $limit,
		'orderby' => $orberby,
        'order' => $order,
        'post_type' => 'faq',
    ));
 
    $faq  = '<div class="' . $theme . '" id="faq-accordion">';
    foreach ( $posts as $post ) {
        $faq .= sprintf(('<h3 class="accordion-toggle"><a href=""><i class="fa fa-angle-right"></i> %1$s</a></h3><div class="accordion-content">%2$s</div>'),
            $post->post_title,
            wpautop($post->post_content)
        );
    }
    $faq .= '</div>';
 
    return $faq;
}

add_shortcode('faq_accordion', 'jcdfaq_accordion' );

?>