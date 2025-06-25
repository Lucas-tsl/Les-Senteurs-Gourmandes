<?php

add_filter( 'woocommerce_product_add_to_cart_text', 'custom_loop_button_text', 20, 2 );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'custom_single_button_text', 20, 2 );

function custom_loop_button_text( $text, $product ) {
    if ( $product->is_type( 'variable' ) ) {
        return 'Choisissez vos options';
    }
    return $text;
}

function custom_single_button_text( $text, $product ) {
    if ( $product->is_type( 'variable' ) ) {
        return 'Ajouter au panier';
    }
    return $text;
}
