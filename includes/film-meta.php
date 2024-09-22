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
    // Ajouter un champ nonce pour la vérification de sécurité
    wp_nonce_field('film_save_meta_box_data', 'film_meta_box_nonce');

    // Récupérer les valeurs des champs personnalisés
    $release_date = get_post_meta($post->ID, 'release_date', true);
    $vote_average = get_post_meta($post->ID, 'vote_average', true);
    $poster_path = get_post_meta($post->ID, 'poster_path', true);
    $popularity = get_post_meta($post->ID, 'popularity', true);
    $original_language = get_post_meta($post->ID, 'original_language', true);
    $media_type = get_post_meta($post->ID, 'media_type', true);
    $genre_ids = get_post_meta($post->ID, 'genre_ids', true);

    // Affichage des champs dans la meta box
    echo '<label for="release_date">Date de sortie :</label>';
    echo '<input type="date" id="release_date" name="release_date" value="' . esc_attr($release_date) . '" /><br/><br/>';

    echo '<label for="vote_average">Note moyenne :</label>';
    echo '<input type="number" id="vote_average" name="vote_average" step="0.1" value="' . esc_attr($vote_average) . '" /><br/><br/>';

    echo '<label for="poster_path">URL de l\'affiche :</label>';
    echo '<input type="text" id="poster_path" name="poster_path" value="' . esc_attr($poster_path) . '" /><br/><br/>';

    echo '<label for="popularity">Popularité :</label>';
    echo '<input type="number" id="popularity" name="popularity" step="0.1" value="' . esc_attr($popularity) . '" /><br/><br/>';

    echo '<label for="original_language">Langue originale :</label>';
    echo '<input type="text" id="original_language" name="original_language" value="' . esc_attr($original_language) . '" /><br/><br/>';

    echo '<label for="media_type">Type de média :</label>';
    echo '<input type="text" id="media_type" name="media_type" value="' . esc_attr($media_type) . '" /><br/><br/>';

    echo '<label for="genre_ids">Genres (séparés par des virgules) :</label>';
    echo '<input type="text" id="genre_ids" name="genre_ids" value="' . esc_attr($genre_ids) . '" /><br/><br/>';
}

// Sauvegarder les champs personnalisés
function save_film_meta_box_data($post_id)
{
    // Vérification du nonce
    if (!isset($_POST['film_meta_box_nonce']) || !wp_verify_nonce($_POST['film_meta_box_nonce'], 'film_save_meta_box_data')) {
        return;
    }

    // Vérification des permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sauvegarder les champs personnalisés
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
        if (isset($_POST[$field])) {
            // Assurez-vous que les données saisies soient bien nettoyées et sauvegardées
            $sanitized_value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $sanitized_value);

            // Log pour vérifier que les données sont bien mises à jour
            error_log('Mise à jour de ' . $field . ' pour le film ' . $post_id . ' avec la valeur : ' . $sanitized_value);
        }
    }
}
add_action('save_post', 'save_film_meta_box_data');
