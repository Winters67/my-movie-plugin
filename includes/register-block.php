<?php

function register_film_block()
{
    // Enregistrer le script JavaScript du bloc
    wp_register_script(
        'film-block',
        plugins_url('../build/block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . '../build/block.js') // Ajoute une version basée sur la dernière modification
    );


    function enqueue_film_block_assets()
    {
        wp_enqueue_style(
            'film-block-css',
            plugins_url('../css/film-style.css', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . '../css/film-style.css') // Ajoute une version basée sur la dernière modification
        );
    }
    add_action('wp_enqueue_scripts', 'enqueue_film_block_assets');


    // Enregistrer le bloc côté PHP
    register_block_type('custom-plugin/film-block', array(
        'editor_script' => 'film-block',
        'render_callback' => 'render_film_block',
        'attributes' => array(
            'filmId' => array(
                'type' => 'number',
                'default' => 0
            ),
            'displayLatest' => array(
                'type' => 'boolean',
                'default' => true
            )
        )
    ));
}
add_action('init', 'register_film_block');

function render_film_block($attributes)
{
    if ($attributes['displayLatest']) {
        // Récupérer les 3 derniers films
        $films = get_posts(array(
            'post_type' => 'film',
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    } else {
        // Récupérer un film spécifique par son ID
        $films = get_posts(array(
            'post_type' => 'film',
            'p' => $attributes['filmId'],
        ));
    }

    if (empty($films)) {
        return '<p>Aucun film trouvé.</p>';
    }

    // Générer le HTML pour afficher chaque film
    $output = '<div class="film-block">';
    foreach ($films as $film) {
        $poster = get_post_meta($film->ID, 'poster_path', true);
        $release_date = get_post_meta($film->ID, 'release_date', true);
        $vote_average = get_post_meta($film->ID, 'vote_average', true);
        $vote_count = get_post_meta($film->ID, 'vote_count', true);
        $popularity = get_post_meta($film->ID, 'popularity', true);
        $original_language = get_post_meta($film->ID, 'original_language', true);
        $media_type = get_post_meta($film->ID, 'media_type', true);
        $genre_ids = get_post_meta($film->ID, 'genre_ids', true);
        $budget = get_post_meta($film->ID, 'budget', true);
        $revenue = get_post_meta($film->ID, 'revenue', true);
        $runtime = get_post_meta($film->ID, 'runtime', true);

        $output .= '<div class="film">';
        $output .= '<div class="film-poster"><img src="' . esc_url($poster) . '" alt="' . esc_attr($film->post_title) . '"></div>';
        $output .= '<div class="film-details">';
        $output .= '<h3>' . esc_html($film->post_title) . '</h3>';
        $output .= '<p>' . esc_html($film->post_content) . '</p>';
        $output .= '<p>Date de sortie : ' . esc_html($release_date) . '</p>';
        $output .= '<p>Note : ' . esc_html($vote_average) . ' (' . esc_html($vote_count) . ' votes)</p>';
        $output .= '<p>Popularité : ' . esc_html($popularity) . '</p>';
        $output .= '<p>Langue originale : ' . esc_html($original_language) . '</p>';
        $output .= '<p>Type de média : ' . esc_html($media_type) . '</p>';
        $output .= '<p>Genres : ' . esc_html($genre_ids) . '</p>';

        // Vérification des champs "budget" et "revenue" avant d'utiliser number_format
        $output .= '<p>Budget : ' . (!empty($budget) && is_numeric($budget) ? number_format($budget) . ' $' : 'Non spécifié') . '</p>';
        $output .= '<p>Revenu : ' . (!empty($revenue) && is_numeric($revenue) ? number_format($revenue) . ' $' : 'Non spécifié') . '</p>';
        $output .= '<p>Durée : ' . (!empty($runtime) ? esc_html($runtime) . ' minutes' : 'Non spécifié') . '</p>';

        $output .= '</div>'; // .film-details
        $output .= '</div>'; // .film
    }
    $output .= '</div>'; // .film-block

    return $output;
}
