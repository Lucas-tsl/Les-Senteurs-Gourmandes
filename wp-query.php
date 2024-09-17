<?php
// fonction vÃ©rification association parfums et note 
function term_associated_with_parfum($note_slug, $parfum_slug) {
    // Logique pour vÃ©rifier si la note (pa_mini_diag_note) est associÃ©e au parfum (pa_mini_diag_parfum)
    $args = [
        'post_type' => 'product',// recherche des produits appartenants au deux slugs en même temps grace au 'AND'
        'tax_query' => [
            'relation' => 'AND',
            [
                'taxonomy' => 'pa_mini_diag_parfum',
                'field' => 'slug',
                'terms' => $parfum_slug,
            ],
            [
                'taxonomy' => 'pa_mini_diag_note',
                'field' => 'slug',
                'terms' => $note_slug,
            ],
        ],
      ?>
    ];

    $query = new WP_Query($args);

    return $query->have_posts();
}
