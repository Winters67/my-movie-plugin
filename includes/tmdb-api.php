<?php
// Fonction pour récupérer les films tendances depuis TMDb
function get_trending_movies($time_window = 'day')
{
    // Clé de cache pour stocker les films tendances
    $cache_key = 'trending_movies_' . $time_window;

    // Vérifier s'il existe déjà une version en cache
    $cached_movies = get_transient($cache_key);
    if ($cached_movies) {
        return $cached_movies;
    }

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
    $movies = $data['results'] ?? [];

    // Récupération des détails pour chaque film
    $detailed_movies = [];
    foreach ($movies as $movie) {
        $movie_details = get_movie_details($movie['id'], $bearer_token);
        if ($movie_details) {
            $detailed_movies[] = $movie_details;
        }
    }

    // Mettre les résultats dans le cache pour 6 heures (21600 secondes)
    set_transient($cache_key, $detailed_movies, 6 * HOUR_IN_SECONDS);

    return $detailed_movies;
}

// Fonction pour récupérer les détails d'un film spécifique depuis TMDb
function get_movie_details($movie_id, $bearer_token)
{
    $url = 'https://api.themoviedb.org/3/movie/' . $movie_id;

    sleep(1); // Pause d'une seconde pour respecter la limitation d'API

    $response = wp_remote_get($url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $bearer_token,
        ),
    ));

    if (is_wp_error($response)) {
        error_log('Erreur lors de la récupération des détails du film ID : ' . $movie_id . ' - ' . $response->get_error_message());
        return null;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Traitement spécifique des genres
    if (isset($data['genres']) && is_array($data['genres'])) {
        $genre_names = [];
        foreach ($data['genres'] as $genre) {
            $genre_names[] = $genre['name'];
        }
        // Stocker les genres sous forme de liste de noms séparés par des virgules
        $data['genre_list'] = implode(', ', $genre_names);
    } else {
        $data['genre_list'] = 'Non spécifié'; // Par défaut, si aucun genre n'est trouvé
    }

    return $data ?? null;
}
