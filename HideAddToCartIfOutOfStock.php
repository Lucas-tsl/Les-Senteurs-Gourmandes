<?php 

// Masque le bouton "Ajouter au panier" si la variation est en rupture de stock
function custom_hide_add_to_cart_if_variation_out_of_stock() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Fonction pour afficher ou masquer le bouton
            function toggleAddToCartButton(variation) {
                if (variation && variation.is_in_stock === false) {
                    $('.woocommerce-variation-add-to-cart').hide();
                } else {
                    $('.woocommerce-variation-add-to-cart').show();
                }
            }

            // Cible tous les formulaires de variation
            $('form.variations_form').each(function() {
                const $form = $(this);

                // Quand une variation est trouvée
                $form.on('found_variation', function(event, variation) {
                    toggleAddToCartButton(variation);
                });

                // Quand aucune variation valide n'est trouvée (ex: reset)
                $form.on('reset_data', function() {
                    $('.woocommerce-variation-add-to-cart').show();
                });
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_hide_add_to_cart_if_variation_out_of_stock');
