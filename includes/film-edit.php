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
    $popularity = get_post_meta($post->ID, 'popularity', true);
    $original_language = get_post_meta($post->ID, 'original_language', true);
    $media_type = get_post_meta($post->ID, 'media_type', true);

    echo '<label for="release_date">Date de sortie :</label>';
    echo '<input type="date" id="release_date" name="release_date" value="' . esc_attr($release_date) . '" /><br/><br/>';

    echo '<label for="vote_average">Note moyenne :</label>';
    echo '<input type="number" id="vote_average" name="vote_average" step="0.1" value="' . esc_attr($vote_average) . '" /><br/><br/>';

    echo '<label for="popularity">Popularité :</label>';
    echo '<input type="number" id="popularity" name="popularity" value="' . esc_attr($popularity) . '" /><br/><br/>';

    echo '<label for="original_language">Langue originale :</label>';
    echo '<input type="text" id="original_language" name="original_language" value="' . esc_attr($original_language) . '" /><br/><br/>';

    echo '<label for="media_type">Type de média :</label>';
    echo '<input type="text" id="media_type" name="media_type" value="' . esc_attr($media_type) . '" /><br/><br/>';
}

// Sauvegarder les champs personnalisés
function save_film_meta_box_data($post_id)
{
    if (array_key_exists('release_date', $_POST)) {
        update_post_meta($post_id, 'release_date', $_POST['release_date']);
    }
    if (array_key_exists('vote_average', $_POST)) {
        update_post_meta($post_id, 'vote_average', $_POST['vote_average']);
    }
    if (array_key_exists('popularity', $_POST)) {
        update_post_meta($post_id, 'popularity', $_POST['popularity']);
    }
    if (array_key_exists('original_language', $_POST)) {
        update_post_meta($post_id, 'original_language', $_POST['original_language']);
    }
    if (array_key_exists('media_type', $_POST)) {
        update_post_meta($post_id, 'media_type', $_POST['media_type']);
    }
}
add_action('save_post', 'save_film_meta_box_data');
