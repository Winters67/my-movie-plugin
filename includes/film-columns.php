<?php
// Ajouter des colonnes personnalisées à la liste des films
function set_custom_film_columns($columns)
{
    $columns['release_date'] = 'Date de sortie';
    $columns['vote_average'] = 'Note moyenne';
    $columns['vote_count'] = 'Nombre de votes';
    $columns['popularity'] = 'Popularité';
    $columns['original_language'] = 'Langue originale';
    $columns['media_type'] = 'Type de média';
    $columns['genre_ids'] = 'Genres';

    return $columns;
}
add_filter('manage_film_posts_columns', 'set_custom_film_columns');

// Récupérer et afficher les valeurs des colonnes personnalisées
function custom_film_column($column, $post_id)
{
    switch ($column) {
        case 'release_date':
            echo get_post_meta($post_id, 'release_date', true);
            break;
        case 'vote_average':
            echo get_post_meta($post_id, 'vote_average', true);
            break;
        case 'vote_count':
            echo get_post_meta($post_id, 'vote_count', true);
            break;
        case 'popularity':
            echo get_post_meta($post_id, 'popularity', true);
            break;
        case 'original_language':
            echo get_post_meta($post_id, 'original_language', true);
            break;
        case 'media_type':
            echo get_post_meta($post_id, 'media_type', true);
            break;
        case 'genre_ids':
            echo get_post_meta($post_id, 'genre_ids', true);
            break;
    }
}
add_action('manage_film_posts_custom_column', 'custom_film_column', 10, 2);
