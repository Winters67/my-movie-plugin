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

        // Enregistrer la date de la dernière synchronisation dans les options WordPress
        update_option('last_movie_sync', current_time('mysql'));
    } else {
        error_log('Erreur : aucun film récupéré ou erreur de l\'API.');
    }
}

//Mettre à jour les films quotidiennement et automatiquement
// Ajouter l'événement Cron 
add_action('wp', 'schedule_daily_movie_sync');

function schedule_daily_movie_sync()
{
    if (!wp_next_scheduled('daily_movie_sync_event')) {
        wp_schedule_event(time(), 'daily', 'daily_movie_sync_event');
    }
}

// Associer la fonction de synchronisation à l'événement Cron
add_action('daily_movie_sync_event', 'synchronize_movies_from_api');

// Optionnel : supprimer l'événement lors de la désactivation du plugin
function myplugin_deactivation()
{
    wp_clear_scheduled_hook('daily_movie_sync_event');
}
register_deactivation_hook(__FILE__, 'myplugin_deactivation');

// Ajouter un message d'admin pour afficher la dernière synchronisation des films
add_action('admin_notices', 'display_last_movie_sync_notice');

function display_last_movie_sync_notice()
{
    // Récupérer la date de la dernière synchronisation
    $last_sync = get_option('last_movie_sync');

    if ($last_sync) {
        echo '<div class="notice notice-success is-dismissible">
            <p>La dernière synchronisation des films a été effectuée le : ' . esc_html($last_sync) . '</p>
        </div>';
    } else {
        echo '<div class="notice notice-error is-dismissible">
            <p>Aucune synchronisation des films n\'a été effectuée.</p>
        </div>';
    }
}
