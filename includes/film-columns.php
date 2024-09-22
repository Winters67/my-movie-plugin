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
    $columns['genres'] = 'Genres';
    $columns['runtime'] = 'Durée (minutes)';
    $columns['budget'] = 'Budget';
    $columns['revenue'] = 'Revenu';
    $columns['production_companies'] = 'Compagnies de production';
    $columns['spoken_languages'] = 'Langues parlées';
    $columns['status'] = 'Statut';

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
        case 'genres':
            $genres = get_post_meta($post_id, 'genres', true);
            echo !empty($genres) ? $genres : 'Non spécifié';
            break;
        case 'runtime':
            $runtime = get_post_meta($post_id, 'runtime', true);
            echo !empty($runtime) ? $runtime . ' min' : 'Non spécifié';
            break;
        case 'budget':
            $budget = get_post_meta($post_id, 'budget', true);
            echo !empty($budget) ? number_format($budget) : 'Non spécifié';
            break;
        case 'revenue':
            $revenue = get_post_meta($post_id, 'revenue', true);
            echo !empty($revenue) ? number_format($revenue) : 'Non spécifié';
            break;
        case 'production_companies':
            $companies = get_post_meta($post_id, 'production_companies', true);
            echo !empty($companies) ? $companies : 'Non spécifié';
            break;
        case 'spoken_languages':
            $languages = get_post_meta($post_id, 'spoken_languages', true);
            echo !empty($languages) ? $languages : 'Non spécifié';
            break;
        case 'status':
            echo get_post_meta($post_id, 'status', true) ?: 'Non spécifié';
            break;
    }
}

add_action('manage_film_posts_custom_column', 'custom_film_column', 10, 2);

// Colonnes triables
function set_sortable_film_columns($columns)
{
    $columns['release_date'] = 'release_date';
    $columns['vote_average'] = 'vote_average';
    $columns['vote_count'] = 'vote_count';
    $columns['popularity'] = 'popularity';
    $columns['original_language'] = 'original_language';
    $columns['media_type'] = 'media_type';
    $columns['runtime'] = 'runtime';
    $columns['budget'] = 'budget';
    $columns['revenue'] = 'revenue';
    $columns['status'] = 'status';

    return $columns;
}
add_filter('manage_edit-film_sortable_columns', 'set_sortable_film_columns');

// Appliquer l'ordre de tri personnalisé
function custom_film_column_orderby($query)
{
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('release_date' === $orderby) {
        $query->set('meta_key', 'release_date');
        $query->set('orderby', 'meta_value');
    } elseif ('vote_average' === $orderby) {
        $query->set('meta_key', 'vote_average');
        $query->set('orderby', 'meta_value_num');
    } elseif ('vote_count' === $orderby) {
        $query->set('meta_key', 'vote_count');
        $query->set('orderby', 'meta_value_num');
    } elseif ('popularity' === $orderby) {
        $query->set('meta_key', 'popularity');
        $query->set('orderby', 'meta_value_num');
    } elseif ('original_language' === $orderby) {
        $query->set('meta_key', 'original_language');
        $query->set('orderby', 'meta_value');
    } elseif ('media_type' === $orderby) {
        $query->set('meta_key', 'media_type');
        $query->set('orderby', 'meta_value');
    } elseif ('runtime' === $orderby) {
        $query->set('meta_key', 'runtime');
        $query->set('orderby', 'meta_value_num');
    } elseif ('budget' === $orderby) {
        $query->set('meta_key', 'budget');
        $query->set('orderby', 'meta_value_num');
    } elseif ('revenue' === $orderby) {
        $query->set('meta_key', 'revenue');
        $query->set('orderby', 'meta_value_num');
    } elseif ('status' === $orderby) {
        $query->set('meta_key', 'status');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'custom_film_column_orderby');
