<?php
// Fonction pour récupérer les films tendances depuis TMDb
function get_trending_movies($time_window = 'day')
{
    $bearer_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyMjJkNjNjZGRjMDY2ZDk5ZWQzZTgwNmQzMjY3MThjYSIsInN1YiI6IjYyNGVhNTRhYjc2Y2JiMDA2ODIzODc4YSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zuuBq1c63XpADl8SQ_c62hezeus7VibE1w5Da5UdYyo'; 

    $url = 'https://api.themoviedb.org/3/trending/movie/' . $time_window;

    sleep(1); // Pause d'une seconde pour respecter la limitation d'API

    $response = wp_remote_get($url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $bearer_token,
        ),
    ));

    if (is_wp_error($response)) {
        error_log('Erreur lors de la récupération des films : ' . $response->get_error_message());
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data['results'] ?? [];
}
