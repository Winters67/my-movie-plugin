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
