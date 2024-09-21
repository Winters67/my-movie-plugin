<?php
// Fonction pour enregistrer le type de post personnalisé « film »
function register_film_post_type()
{
    $labels = array(
        'name'               => 'Films',
        'singular_name'      => 'Film',
        'menu_name'          => 'Films',
        'name_admin_bar'     => 'Film',
        'add_new'            => 'Ajouter un nouveau',
        'add_new_item'       => 'Ajouter un nouveau film',
        'new_item'           => 'Nouveau film',
        'edit_item'          => 'Modifier le film',
        'view_item'          => 'Voir le film',
        'all_items'          => 'Tous les films',
        'search_items'       => 'Rechercher des films',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_menu'       => true,
        'menu_position'      => 5,
        'show_in_rest'       => true, // Activer l'éditeur Gutenberg
        'rewrite'            => array('slug' => 'films'),
        'capability_type'    => 'post', // Cela permet à tous les rôles qui peuvent gérer les posts d'accéder aux films
    );

    register_post_type('film', $args);
}
add_action('init', 'register_film_post_type');

function include_film_meta_in_search($query)
{
    if ($query->is_search() && !is_admin()) {
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key'     => 'release_date',
                'value'   => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
            array(
                'key'     => 'vote_average',
                'value'   => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
            array(
                'key'     => 'original_language',
                'value'   => $query->query_vars['s'],
                'compare' => 'LIKE'
            )
        );

        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'include_film_meta_in_search');
