<?php

function register_film_block()
{
    // Enregistrer le script JavaScript du bloc
    wp_register_script(
        'film-block',
        plugins_url('../build/block.js', __FILE__), // Utilisation du bon chemin
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . '../build/block.js') // Ajoute une version basée sur la dernière modification
    );

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
    // Récupérer les films selon les attributs
    $films = $attributes['displayLatest'] ?
        get_posts(array('post_type' => 'film', 'posts_per_page' => 3)) :
        get_posts(array('post_type' => 'film', 'p' => $attributes['filmId']));

    // Vérification et rendu HTML
    if (empty($films)) {
        return 'Aucun film trouvé';
    }

    $output = '<div class="film-block">';
    foreach ($films as $film) {
        $poster = get_post_meta($film->ID, 'poster_path', true);
        $release_date = get_post_meta($film->ID, 'release_date', true);
        $vote_average = get_post_meta($film->ID, 'vote_average', true);
        $output .= '<div class="film">';
        $output .= '<h3>' . esc_html($film->post_title) . '</h3>';
        $output .= '<img src="' . esc_url($poster) . '" />';
        $output .= '<p>Date de sortie : ' . esc_html($release_date) . '</p>';
        $output .= '<p>Note : ' . esc_html($vote_average) . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
