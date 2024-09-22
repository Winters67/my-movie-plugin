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
    // Utiliser des nonce pour la sécurité
    wp_nonce_field(basename(__FILE__), 'film_meta_box_nonce');

    // Vérification que les métadonnées existent et récupération des valeurs
    $release_date = get_post_meta($post->ID, 'release_date', true) ?: '';
    $vote_average = get_post_meta($post->ID, 'vote_average', true) ?: '';
    $popularity = get_post_meta($post->ID, 'popularity', true) ?: '';
    $original_language = get_post_meta($post->ID, 'original_language', true) ?: '';
    $media_type = get_post_meta($post->ID, 'media_type', true) ?: '';

    // Nouveaux champs pour les informations supplémentaires
    $budget = get_post_meta($post->ID, 'budget', true) ?: '';
    $revenue = get_post_meta($post->ID, 'revenue', true) ?: '';
    $runtime = get_post_meta($post->ID, 'runtime', true) ?: '';

    // Affichage des champs
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

    echo '<label for="budget">Budget :</label>';
    echo '<input type="number" id="budget" name="budget" value="' . esc_attr($budget) . '" /><br/><br/>';

    echo '<label for="revenue">Revenu :</label>';
    echo '<input type="number" id="revenue" name="revenue" value="' . esc_attr($revenue) . '" /><br/><br/>';

    echo '<label for="runtime">Durée (minutes) :</label>';
    echo '<input type="number" id="runtime" name="runtime" value="' . esc_attr($runtime) . '" /><br/><br/>';
}


// Sauvegarder les champs personnalisés
function save_film_meta_box_data($post_id)
{
    $fields = [
        'release_date',
        'vote_average',
        'popularity',
        'original_language',
        'media_type',
        'budget',        // Nouveau champ budget
        'revenue',       // Nouveau champ revenue
        'runtime'        // Nouveau champ runtime
    ];

    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_film_meta_box_data');
