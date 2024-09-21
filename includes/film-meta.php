<?php
// Ajouter une meta box pour les champs personnalisés dans l'admin
function add_film_meta_boxes()
{
    add_meta_box(
        'film_details',
        'Détails du film',
        'display_film_meta_box',
        'film',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_film_meta_boxes');

// Afficher les champs dans l'admin
function display_film_meta_box($post)
{
    $release_date = get_post_meta($post->ID, 'release_date', true);
    $vote_average = get_post_meta($post->ID, 'vote_average', true);
    $poster_path = get_post_meta($post->ID, 'poster_path', true);
    $popularity = get_post_meta($post->ID, 'popularity', true);
    $original_language = get_post_meta($post->ID, 'original_language', true);
    $media_type = get_post_meta($post->ID, 'media_type', true);
    $genre_ids = get_post_meta($post->ID, 'genre_ids', true);

    // Affichage des champs dans la meta box
    echo '<label for="release_date">Date de sortie :</label>';
    echo '<input type="date" id="release_date" name="release_date" value="' . esc_attr($release_date) . '" />';
    echo '<br/><br/>';

    echo '<label for="vote_average">Note moyenne :</label>';
    echo '<input type="number" id="vote_average" name="vote_average" step="0.1" value="' . esc_attr($vote_average) . '" />';
    echo '<br/><br/>';

    echo '<label for="poster_path">URL de l\'affiche :</label>';
    echo '<input type="text" id="poster_path" name="poster_path" value="' . esc_attr($poster_path) . '" />';
    echo '<br/><br/>';

    echo '<label for="popularity">Popularité :</label>';
    echo '<input type="number" id="popularity" name="popularity" step="0.1" value="' . esc_attr($popularity) . '" />';
    echo '<br/><br/>';

    echo '<label for="original_language">Langue originale :</label>';
    echo '<input type="text" id="original_language" name="original_language" value="' . esc_attr($original_language) . '" />';
    echo '<br/><br/>';

    echo '<label for="media_type">Type de média :</label>';
    echo '<input type="text" id="media_type" name="media_type" value="' . esc_attr($media_type) . '" />';
    echo '<br/><br/>';

    echo '<label for="genre_ids">Genres :</label>';
    echo '<input type="text" id="genre_ids" name="genre_ids" value="' . esc_attr($genre_ids) . '" />';
    echo '<br/><br/>';
}

// Sauvegarder les champs personnalisés
function save_film_meta_box_data($post_id)
{
    $fields = [
        'release_date',
        'vote_average',
        'poster_path',
        'popularity',
        'original_language',
        'media_type',
        'genre_ids'
    ];

    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_film_meta_box_data');
