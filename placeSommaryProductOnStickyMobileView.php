
<style>
/* Disposition tablette et mobile fiche produit */
@media only screen and (max-width: 1200px) {
    .colonne-gauche, .colonne-droite {
        display: flex; 
    }

    .colonne-gauche > div:first-child {
        order: 1; 
    }

    .colonne-droite > div:first-child {
        order: 2; 
    }

    .colonne-gauche > div:not(:first-child) {
        order: 3; 
    }
	.colonne-gauche, .colonne-droite {
    display: flex;
    flex-direction: column;
}

}

/* Fin Disposition tablette et mobile fiche produit */


</style>

function deplacer_colonne_droite_mobile() {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.innerWidth <= 768) {
                const description = document.querySelector(".product-description");
                const colonneDroite = document.querySelector(".colonne-droite");

                if (description && colonneDroite) {
                    description.parentNode.insertBefore(colonneDroite, description.nextSibling);
                }
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'deplacer_colonne_droite_mobile');

