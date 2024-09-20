<?php
/*
Plugin Name: My Movie Plugin
Description: Plugin pour récupérer les films via TMDb.
Version: 1.0
Author: Emmanuel
*/

// fichier nécessaire pour l'API TMDb
require_once plugin_dir_path(__FILE__) . 'includes/tmdb-api.php';

// Ajouter un hook pour afficher les films récupérés dans une page d'administration
add_action('admin_menu', function () {
    add_menu_page('Tester les films', 'Test TMDb', 'manage_options', 'test-tmdb', function () {
        $movies = get_trending_movies();
        echo '<h1>Films tendances du jour</h1>';
        echo '<pre>';
        print_r($movies); // Afficher les films récupérés
        echo '</pre>';
    });
});
