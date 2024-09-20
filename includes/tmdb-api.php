<?php
// Fonction pour récupérer les films tendances depuis TMDb
function get_trending_movies($time_window = 'day')
{
    $bearer_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyMjJkNjNjZGRjMDY2ZDk5ZWQzZTgwNmQzMjY3MThjYSIsInN1YiI6IjYyNGVhNTRhYjc2Y2JiMDA2ODIzODc4YSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zuuBq1c63XpADl8SQ_c62hezeus7VibE1w5Da5UdYyo'; // Ton Bearer Token

    $url = 'https://api.themoviedb.org/3/trending/movie/' . $time_window;

    // Effectuer la requête HTTP vers l'API TMDb
    $response = wp_remote_get($url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $bearer_token,
        ),
    ));

    // Vérifie si la requête a rencontré une erreur
    if (is_wp_error($response)) {
        return 'Erreur lors de la récupération des films : ' . $response->get_error_message(); // Gérer les erreurs
    }

    // Récupérer le corps de la réponse
    $body = wp_remote_retrieve_body($response);

    // Convertir le JSON en tableau associatif PHP
    $data = json_decode($body, true);

    // Retourner les films ou un tableau vide si rien n'est trouvé
    return $data['results'] ?? [];
}
