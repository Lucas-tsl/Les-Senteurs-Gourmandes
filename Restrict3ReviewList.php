<?php

// 1. Limiter les avis affichés à 3 (sauf si ?afficher_tous_les_avis est présent)
add_filter('comments_array', 'limiter_avis_woocommerce', 10, 2);
function limiter_avis_woocommerce($comments, $post_id) {
    if (!is_product()) return $comments;
    if (isset($_GET['afficher_tous_les_avis']) || (defined('DOING_AJAX') && DOING_AJAX)) {
        return $comments;
    }
    return array_slice($comments, 0, 3);
}

// 2. Affiche le bouton "Voir plus d’avis" après la liste si plus de 3 avis
add_action('comment_form_before', 'afficher_bouton_voir_plus_avis');
function afficher_bouton_voir_plus_avis() {
    if (!is_product()) return;

    global $product;
    $total_avis = get_comments_number($product->get_id());
    if ($total_avis <= 3) return;
    ?>
    <div id="voir-plus-avis-container" style="text-align:center; margin: 20px 0;">
        <button id="voir-plus-avis" style="padding:10px 20px; cursor:pointer;">Voir plus d’avis</button>
    </div>
    <?php
}

// 3. JavaScript pour charger dynamiquement tous les avis
add_action('wp_footer', 'js_charger_avis_ajax', 99);
function js_charger_avis_ajax() {
    if (!is_product()) return;

    // Crée une URL propre du produit sans ancre ni query
    global $wp;
    $product_url = home_url(add_query_arg(array(), $wp->request));
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const bouton = document.getElementById('voir-plus-avis');
        if (bouton) {
            bouton.addEventListener('click', function () {
                bouton.disabled = true;
                bouton.textContent = 'Chargement...';

                const url = '<?php echo esc_url($product_url); ?>?afficher_tous_les_avis=1';

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const nouveauxAvis = doc.querySelector('#reviews .commentlist');
                        const listeAvis = document.querySelector('#reviews .commentlist');

                        if (listeAvis && nouveauxAvis) {
                            listeAvis.innerHTML = nouveauxAvis.innerHTML;
                            bouton.style.display = 'none';
                        } else {
                            bouton.textContent = 'Aucun avis trouvé';
                            bouton.disabled = false;
                            console.warn('Erreur : élément .commentlist introuvable.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur AJAX :', error);
                        bouton.textContent = 'Erreur, réessayer';
                        bouton.disabled = false;
                    });
            });
        }
    });
    </script>
    <?php
}

?>
