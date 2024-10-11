<?php
/*
Plugin Name: Custom Category Banner Block
Description: A Gutenberg block to display custom category banners from WooCommerce.
Version: 1.0
Author: Troteseil Lucas
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Enqueue block scripts and styles
function custom_category_banner_block_assets() {
    wp_enqueue_script(
        'custom-category-banner-block',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-editor', 'wp-components', 'wp-element' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
    );
}
add_action( 'enqueue_block_editor_assets', 'custom_category_banner_block_assets' );

// Register custom block
function custom_category_banner_block_register() {
    register_block_type( 'custom/category-banner', array(
        'render_callback' => 'custom_category_banner_block_render',
    ) );
}
add_action( 'init', 'custom_category_banner_block_register' );

// Block rendering callback
function custom_category_banner_block_render( $attributes ) {
    $category_id = isset( $attributes['categoryId'] ) ? intval( $attributes['categoryId'] ) : 0;
    
    if ( !$category_id ) {
        return '<p>No category selected.</p>';
    }

    // Fetch the category details (title, subtitle, image, etc.)
    $category = get_term( $category_id, 'product_cat' );

    if ( is_wp_error( $category ) ) {
        return '<p>Invalid category.</p>';
    }

    $category_name = $category->name;
    $banner_title = get_term_meta( $category_id, 'banner_title', true );
    $banner_subtitle = get_term_meta( $category_id, 'banner_subtitle', true );
    $banner_image_id = get_term_meta( $category_id, 'banner_image_id', true );
    $banner_image_url = wp_get_attachment_url( $banner_image_id );

    ob_start();
    ?>
    <div class="custom-category-banner">
        <h2><?php echo esc_html( $banner_title ? $banner_title : $category_name ); ?></h2>
        <p><?php echo esc_html( $banner_subtitle ); ?></p>
        <?php if ( $banner_image_url ): ?>
            <img src="<?php echo esc_url( $banner_image_url ); ?>" alt="<?php echo esc_attr( $category_name ); ?>">
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}
?>


// Add custom fields to WooCommerce product category taxonomy
function add_custom_category_fields( $term ) {
    $banner_title = get_term_meta( $term->term_id, 'banner_title', true );
    $banner_subtitle = get_term_meta( $term->term_id, 'banner_subtitle', true );
    $banner_image_id = get_term_meta( $term->term_id, 'banner_image_id', true );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="banner_title">Banner Title</label></th>
        <td>
            <input type="text" name="banner_title" id="banner_title" value="<?php echo esc_attr( $banner_title ); ?>" />
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="banner_subtitle">Banner Subtitle</label></th>
        <td>
            <input type="text" name="banner_subtitle" id="banner_subtitle" value="<?php echo esc_attr( $banner_subtitle ); ?>" />
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="banner_image_id">Banner Image</label></th>
        <td>
            <input type="hidden" name="banner_image_id" id="banner_image_id" value="<?php echo esc_attr( $banner_image_id ); ?>" />
            <button class="upload_image_button button">Upload Image</button>
        </td>
    </tr>
    <?php
}
add_action( 'product_cat_edit_form_fields', 'add_custom_category_fields' );

// Save the custom fields
function save_custom_category_fields( $term_id ) {
    if ( isset( $_POST['banner_title'] ) ) {
        update_term_meta( $term_id, 'banner_title', sanitize_text_field( $_POST['banner_title'] ) );
    }
    if ( isset( $_POST['banner_subtitle'] ) ) {
        update_term_meta( $term_id, 'banner_subtitle', sanitize_text_field( $_POST['banner_subtitle'] ) );
    }
    if ( isset( $_POST['banner_image_id'] ) ) {
        update_term_meta( $term_id, 'banner_image_id', absint( $_POST['banner_image_id'] ) );
    }
}
add_action( 'edited_product_cat', 'save_custom_category_fields' );

