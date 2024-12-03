<?php
add_action('woocommerce_single_product_summary', 'display_price_per_100ml_for_variations', 20);

function display_price_per_100ml_for_variations() {
    global $product;

    // Vérifier si le produit est de type variable
    if ($product->is_type('variable')) {
        // Créer un conteneur où sera affiché le prix pour 100 ml
        echo '<div id="price-per-100ml"><p class="price-per-unit">Sélectionnez une contenance pour voir le prix pour 100 ml.</p></div>';
    }
}
add_action('wp_footer', 'update_price_per_100ml_js');

function update_price_per_100ml_js() {
    if (is_product()) : ?>
        <script>
        jQuery(document).ready(function($) {
            function updatePricePer100ml() {
                // Récupérer le prix de la variation
                var variationPrice = $('.woocommerce-variation-price .woocommerce-Price-amount').text();

                // Récupérer la valeur de l'attribut "contenance"
                var variationVolume = $('select[name="attribute_pa_contenance"]').val(); // Si le nom inclut "pa_"
				// Nom exact de l'attribut

                if (variationPrice && variationVolume) {
                    // Convertir les valeurs en float
                    var price = parseFloat(variationPrice.replace(',', '.').replace('€', '').trim());
                    var volume = parseFloat(variationVolume);

                    if (volume && price) {
                        // Calculer le prix pour 100 ml
                        var pricePer100ml = (price / volume) * 100;
                        $('#price-per-100ml').html('<p class="price-per-unit">Prix pour 100 ml : ' + pricePer100ml.toFixed(2) + ' euros</p>');
                    } else {
                        $('#price-per-100ml').html('<p class="price-per-unit">Impossible de calculer le prix pour 100 ml.</p>');
                    }
                } else {
                    $('#price-per-100ml').html('<p class="price-per-unit">S&eacute;lectionnez une contenance pour voir le prix pour 100&nbsp;ml.</p>');
                }
            }

            // Écouter le changement de variation
            $('form.variations_form').on('woocommerce_variation_has_changed', updatePricePer100ml);

            // Appeler la fonction pour afficher le prix au chargement initial
            updatePricePer100ml();
        });
        </script>
    <?php endif;
}
?>
