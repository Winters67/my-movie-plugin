<?php
// includes/view-movies.php

// Ajouter un onglet dans le menu d'administration pour afficher les films
add_action('admin_menu', 'add_tmdb_movies_menu');

function add_tmdb_movies_menu()
{
    add_menu_page(
        'Films TMDb',       // Titre de la page
        'Voir les Films TMDb',  // Titre du menu
        'manage_options',   // Capacité requise pour accéder à cette page
        'view-tmdb-movies', // Slug de la page
        'display_tmdb_movies', // Fonction pour afficher le contenu de la page
        'dashicons-video-alt2',  // Icône du menu
        6                   // Position dans le menu
    );
}

// Fonction pour afficher les films récupérés et leur structure
function display_tmdb_movies()
{
    // Appelle la fonction qui récupère les films depuis l'API TMDb
    $movies = get_trending_movies();

    echo '<div class="wrap">';
    echo '<h1>Films tendances du jour</h1>';

    if (is_array($movies) && !empty($movies)) {
        echo '<h2>Structure des données API</h2>';
        echo '<pre>';
        print_r($movies); // Affiche la structure complète des films
        echo '</pre>';
    } else {
        echo '<p>Aucun film récupéré depuis l\'API TMDb.</p>';
    }

    echo '</div>';
}
