<?php 
    /*banners_rotator_namespace
    Plugin Name: Banners
    Plugin URI: http://
    Description: Banner rotator
    Author: Autor
    Version: 0.1
    Author URI: http://
    */
define('WP_DEBUG', true);
if ( ! defined( 'ABSPATH' ) ) exit;

function banners_rotator_admin() {
	include('banners_rotator_admin.php'); // display upload form

	/***** Display images in banner category****/
	$media_query = new WP_Query(
	    array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'posts_per_page' => -1,
	    )
	);
	$list = array();
	foreach ($media_query->posts as $post) {
	    $category = get_post_meta($post->ID,'banner',true);
	    if($category == "banner") 
	    	$list[] = wp_get_attachment_url($post->ID);
	}
	
	$images_url = wp_json_encode($list);

	echo "<pre>";
	print_r($images_url);
	echo "</pre>";
}

function banners_rotator_admin_actions() {
    add_menu_page("Banner rotator", "Banner rotator", 1, "BannerRotator", "banners_rotator_admin");
}
 
add_action('admin_menu', 'banners_rotator_admin_actions'); //adding to menu


function wp_gear_manager_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
	wp_register_script( 'myscript', trailingslashit( plugin_dir_url(__FILE__) ) . 'js/admin_script.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'myscript' );
}

function wp_gear_manager_admin_styles() {
	wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts'); //adding javascript
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');  


//**** Adding image category field ******
function be_attachment_field_banner_category( $form_fields, $post ) {
    $form_fields['banner-category'] = array(
        'label' => 'Image Category',
        'input' => 'text',
        'value' => get_post_meta( $post->ID, 'banner', true ),
        'helps' => 'Add "banner" in categoriy field to display the image in banner section',
    );
    return $form_fields;
}
function be_attachment_field_banner_category_save( $post, $attachment ) {
    if( isset( $attachment['banner-category'] ) )
        update_post_meta( $post['ID'], 'banner', $attachment['banner-category'] );
    return $post;
}
add_filter( 'attachment_fields_to_edit', 'be_attachment_field_banner_category', 10, 2 );
add_filter( 'attachment_fields_to_save', 'be_attachment_field_banner_category_save', 10, 2 );


?>
