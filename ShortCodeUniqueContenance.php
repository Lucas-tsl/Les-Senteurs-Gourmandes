<?php
// Shortcode pour afficher les variations 15ml uniquement
add_shortcode('variations_15ml', 'afficher_variations_15ml');

function afficher_variations_15ml() {
    ob_start();

    // Requête pour récupérer toutes les variations visibles
    $args = array(
        'post_type'     => 'product_variation',
        'post_status'   => 'publish',
        'posts_per_page'=> -1,
        'meta_query'    => array(
            array(
                'key'     => 'attribute_pa_contenance',
                'value'   => '15ml', // Vérifie bien le slug exact dans les attributs
                'compare' => '='
            ),
        ),
    );

    $variations = new WP_Query($args);

    if ($variations->have_posts()) {
        echo '<ul class="produits-variations-15ml">';
        while ($variations->have_posts()) {
            $variations->the_post();

            $product = wc_get_product(get_the_ID());

            if (!$product || !$product->is_visible()) continue;

            $product_link = get_permalink($product->get_parent_id()) . '?attribute_pa_contenance=15ml';

            echo '<li class="produit-variation">';
            echo '<a href="' . esc_url($product_link) . '">';
            echo $product->get_image(); // image de variation ou produit parent
            echo '<h3>' . esc_html($product->get_name()) . '</h3>';
            echo '<span class="price">' . $product->get_price_html() . '</span>';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Aucune variation 15 ml trouvée.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

?>
<style>
  
ul.produits-variations-15ml {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
  list-style: none;
  padding: 0;
  margin: 2rem 0;
}

.produit-variation {
  border-radius: 16px;
  overflow: hidden;
  padding: 1rem;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 0 0 rgba(0,0,0,0);
  background: transparent; /* <- fond transparent */
}

.produit-variation:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.produit-variation img {
  max-width: 100%;
  height: auto;
  border-radius: 12px;
  margin-bottom: 1rem;
}

.produit-variation h3 {
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 0.5rem;
  color: #333;
}

.produit-variation .price {
  font-size: 1rem;
  font-weight: 500;
  color: #000000;
}

/* Responsive */
@media (max-width: 1024px) {
  ul.produits-variations-15ml {
		gap: 0rem;
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  ul.produits-variations-15ml {
		gap : 0rem;
    grid-template-columns: 2,1fr;
  }
}

</style>
