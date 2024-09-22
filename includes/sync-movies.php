<?php
// Fonction pour stocker les films dans la base de données WordPress
function store_movies_in_db($movies_with_details)
{
    error_log('Début de l\'insertion des films dans la base de données.');

    foreach ($movies_with_details as $movie) {
        $movie_id = $movie['id'];
        error_log('Traitement du film avec ID TMDb : ' . $movie_id);

        $existing_movie = get_posts(array(
            'post_type' => 'film',
            'meta_query' => array(
                array(
                    'key'     => 'tmdb_id',
                    'value'   => $movie_id,
                    'compare' => '='
                )
            )
        ));

        if (empty($existing_movie)) {
            error_log('Le film avec ID TMDb ' . $movie_id . ' n\'existe pas, insertion dans la base.');

            $post_id = wp_insert_post(array(
                'post_title'    => $movie['title'],
                'post_content'  => $movie['overview'],
                'post_type'     => 'film',
                'post_status'   => 'publish',
            ));

            if (!is_wp_error($post_id)) {
                // Insertion des métadonnées
                update_post_meta($post_id, 'tmdb_id', $movie['id']);
                update_post_meta($post_id, 'release_date', $movie['release_date']);
                update_post_meta($post_id, 'vote_average', $movie['vote_average']);
                update_post_meta($post_id, 'vote_count', $movie['vote_count']);
                update_post_meta($post_id, 'budget', $movie['budget']);
                update_post_meta($post_id, 'revenue', $movie['revenue']);
                update_post_meta($post_id, 'runtime', $movie['runtime']);
                update_post_meta($post_id, 'poster_path', 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']);
                update_post_meta($post_id, 'backdrop_path', 'https://image.tmdb.org/t/p/w500' . $movie['backdrop_path']);
                update_post_meta($post_id, 'popularity', $movie['popularity']);
                update_post_meta($post_id, 'original_language', $movie['original_language']);
                update_post_meta($post_id, 'status', $movie['status']);
                update_post_meta($post_id, 'tagline', $movie['tagline']);

                // Gestion des genres
                if (isset($movie['genres']) && is_array($movie['genres'])) {
                    $genre_names = [];
                    foreach ($movie['genres'] as $genre) {
                        $genre_names[] = $genre['name'];
                    }
                    update_post_meta($post_id, 'genres', implode(', ', $genre_names)); // Stockage des genres
                }

                // Gestion des compagnies de production
                if (isset($movie['production_companies']) && is_array($movie['production_companies'])) {
                    $company_names = [];
                    foreach ($movie['production_companies'] as $company) {
                        $company_names[] = $company['name'];
                    }
                    update_post_meta($post_id, 'production_companies', implode(', ', $company_names)); // Stockage des compagnies
                }

                // Gestion des langues parlées
                if (isset($movie['spoken_languages']) && is_array($movie['spoken_languages'])) {
                    $language_names = [];
                    foreach ($movie['spoken_languages'] as $language) {
                        $language_names[] = $language['name'];
                    }
                    update_post_meta($post_id, 'spoken_languages', implode(', ', $language_names)); // Stockage des langues
                }

                error_log('Film inséré avec succès. ID du post : ' . $post_id);
            } else {
                error_log('Erreur lors de l\'insertion du film avec ID TMDb ' . $movie_id . ' : ' . $post_id->get_error_message());
            }
        } else {
            // Si le film existe déjà, on le met à jour
            $post_id = $existing_movie[0]->ID;
            error_log('Le film avec ID TMDb ' . $movie_id . ' existe déjà, mise à jour des métadonnées.');

            $fields_to_update = array(
                'release_date' => $movie['release_date'],
                'vote_average' => $movie['vote_average'],
                'vote_count' => $movie['vote_count'],
                'budget' => $movie['budget'],
                'revenue' => $movie['revenue'],
                'runtime' => $movie['runtime'],
                'poster_path' => 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'],
                'backdrop_path' => 'https://image.tmdb.org/t/p/w500' . $movie['backdrop_path'],
                'popularity' => $movie['popularity'],
                'original_language' => $movie['original_language'],
                'status' => $movie['status'],
                'tagline' => $movie['tagline']
            );

            foreach ($fields_to_update as $meta_key => $value) {
                if (empty(get_post_meta($post_id, $meta_key, true))) {
                    update_post_meta($post_id, $meta_key, $value);
                }
            }

            // Mise à jour des genres
            if (isset($movie['genres']) && is_array($movie['genres'])) {
                $genre_names = [];
                foreach ($movie['genres'] as $genre) {
                    $genre_names[] = $genre['name'];
                }
                update_post_meta($post_id, 'genres', implode(', ', $genre_names));
            }

            // Mise à jour des compagnies de production
            if (isset($movie['production_companies']) && is_array($movie['production_companies'])) {
                $company_names = [];
                foreach ($movie['production_companies'] as $company) {
                    $company_names[] = $company['name'];
                }
                update_post_meta($post_id, 'production_companies', implode(', ', $company_names));
            }

            // Mise à jour des langues parlées
            if (isset($movie['spoken_languages']) && is_array($movie['spoken_languages'])) {
                $language_names = [];
                foreach ($movie['spoken_languages'] as $language) {
                    $language_names[] = $language['name'];
                }
                update_post_meta($post_id, 'spoken_languages', implode(', ', $language_names));
            }

            error_log('Mise à jour des métadonnées terminée pour le film avec ID TMDb ' . $movie_id);
        }
    }

    error_log('Fin de l\'insertion des films dans la base de données.');
}
