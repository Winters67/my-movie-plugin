<?php
/*
Plugin Name: My Movie Plugin
Description: Plugin pour récupérer les films via TMDb.
Version: 1.0
Author: Emmanuel
*/

// fichier nécessaire pour l'API TMDb
require_once plugin_dir_path(__FILE__) . 'includes/sync-movies.php';
require_once plugin_dir_path(__FILE__) . 'includes/tmdb-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/view-movies.php';


// / Ajouter un hook pour la synchronisation des films une fois WordPress initialisé

add_action('init', 'synchronize_movies_from_api');

function synchronize_movies_from_api()
{
    $movies_with_details = get_trending_movies();

    if (!empty($movies_with_details)) {
        store_movies_in_db($movies_with_details);
    } else {
        error_log('Erreur : aucun film récupéré ou erreur de l\'API.');
    }
}
