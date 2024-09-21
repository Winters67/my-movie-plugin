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
                update_post_meta($post_id, 'tmdb_id', $movie['id']);
                update_post_meta($post_id, 'release_date', $movie['release_date']);
                update_post_meta($post_id, 'vote_average', $movie['vote_average']);
                update_post_meta($post_id, 'vote_count', $movie['vote_count']);
                update_post_meta($post_id, 'poster_path', 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']);
                update_post_meta($post_id, 'backdrop_path', 'https://image.tmdb.org/t/p/w500' . $movie['backdrop_path']);
                update_post_meta($post_id, 'popularity', $movie['popularity']);
                update_post_meta($post_id, 'original_language', $movie['original_language']);
                update_post_meta($post_id, 'media_type', $movie['media_type']);
                update_post_meta($post_id, 'genre_ids', implode(', ', $movie['genre_ids']));
                error_log('Film inséré avec succès. ID du post : ' . $post_id);
            } else {
                error_log('Erreur lors de l\'insertion du film avec ID TMDb ' . $movie_id . ' : ' . $post_id->get_error_message());
            }
        } else {
            // Le film existe déjà, vérifier avant de mettre à jour
            $post_id = $existing_movie[0]->ID; // Récupérer l'ID du post existant

            // Ne mettre à jour que si la valeur est vide dans la base de données
            if (empty(get_post_meta($post_id, 'release_date', true))) {
                update_post_meta($post_id, 'release_date', $movie['release_date']);
            }
            if (empty(get_post_meta($post_id, 'vote_average', true))) {
                update_post_meta($post_id, 'vote_average', $movie['vote_average']);
            }
            if (empty(get_post_meta($post_id, 'vote_count', true))) {
                update_post_meta($post_id, 'vote_count', $movie['vote_count']);
            }
            if (empty(get_post_meta($post_id, 'popularity', true))) {
                update_post_meta($post_id, 'popularity', $movie['popularity']);
            }
            if (empty(get_post_meta($post_id, 'original_language', true))) {
                update_post_meta($post_id, 'original_language', $movie['original_language']);
            }
            if (empty(get_post_meta($post_id, 'media_type', true))) {
                update_post_meta($post_id, 'media_type', $movie['media_type']);
            }
            if (empty(get_post_meta($post_id, 'genre_ids', true))) {
                update_post_meta($post_id, 'genre_ids', implode(', ', $movie['genre_ids']));
            }
            if (empty(get_post_meta($post_id, 'poster_path', true))) {
                update_post_meta($post_id, 'poster_path', 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']);
            }
            if (empty(get_post_meta($post_id, 'backdrop_path', true))) {
                update_post_meta($post_id, 'backdrop_path', 'https://image.tmdb.org/t/p/w500' . $movie['backdrop_path']);
            }

            error_log('Le film avec ID TMDb ' . $movie_id . ' existe déjà, mise à jour uniquement des métadonnées non présentes.');
        }
    }

    error_log('Fin de l\'insertion des films dans la base de données.');
}
