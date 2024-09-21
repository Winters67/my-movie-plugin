<?php
// Fonction pour récupérer les films tendances depuis TMDb
function get_trending_movies($time_window = 'day')
{
    $bearer_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyMjJkNjNjZGRjMDY2ZDk5ZWQzZTgwNmQzMjY3MThjYSIsInN1YiI6IjYyNGVhNTRhYjc2Y2JiMDA2ODIzODc4YSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zuuBq1c63XpADl8SQ_c62hezeus7VibE1w5Da5UdYyo'; // Ton Bearer Token

    $url = 'https://api.themoviedb.org/3/trending/movie/' . $time_window;

    // Pause d'une seconde pour respecter la limitation d'API
    sleep(1);

    // Effectue la requête HTTP vers l'API TMDb
    $response = wp_remote_get($url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $bearer_token,
        ),
    ));

    // Vérifie si la requête a rencontré une erreur
    if (is_wp_error($response)) {
        return 'Erreur lors de la récupération des films : ' . $response->get_error_message(); // Gérer les erreurs
    }

    // Récupére le corps de la réponse
    $body = wp_remote_retrieve_body($response);

    // Convertir le JSON en tableau associatif PHP
    $data = json_decode($body, true);

    // Récupére les résultats des films
    $movies = $data['results'] ?? [];

    // Initialiser un tableau pour stocker les films avec leurs détails
    $movies_with_details = [];

    // Pour chaque film, récupérer les détails supplémentaires
    foreach ($movies as $movie) {
        $movie_id = $movie['id']; // Récupérer l'ID du film

        // Pause d'une seconde pour respecter la limitation d'API
        sleep(1);

        // URL pour récupérer les détails du film
        $detail_url = 'https://api.themoviedb.org/3/movie/' . $movie_id;

        // Requête pour les détails du film
        $detail_response = wp_remote_get($detail_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $bearer_token,
            ),
        ));

        // Vérifie si la requête pour les détails a rencontré une erreur
        if (!is_wp_error($detail_response)) {
            // Récupérer le corps de la réponse pour les détails
            $detail_body = wp_remote_retrieve_body($detail_response);

            // Convertir le JSON des détails en tableau associatif PHP
            $movie_details = json_decode($detail_body, true);

            // Ajouter les détails au tableau des films avec détails
            $movies_with_details[] = array_merge($movie, $movie_details);
        }
    }

    // Retourner les films avec leurs détails ou un tableau vide si rien n'est trouvé
    return $movies_with_details;
}
