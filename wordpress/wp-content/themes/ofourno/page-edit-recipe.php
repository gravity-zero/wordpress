<?php get_header(); ?>

<?php
// Récupérer l'ID de la recette
$recipe_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($recipe_id) {
    // Récupérer les données de la recette
    $recipe = get_post($recipe_id);

    // Vérifier si le post existe et qu'il s'agit bien d'une recette
    if ($recipe && $recipe->post_type === 'recipes') {
        // Récupérer les métadonnées
        $ingredients = get_post_meta($recipe_id, '_ingredients', true);
        $difficulty = get_post_meta($recipe_id, '_difficulty', true);
        $cost = get_post_meta($recipe_id, '_cost', true);
        $steps = get_post_meta($recipe_id, '_steps', true);

        // Récupérer les images attachées
        $images = get_children([
            'post_parent' => $recipe_id,
            'post_type' => 'attachment',
            'post_mime_type' => 'image'
        ]);
    } else {
        echo "Recette non trouvée ou type de post invalide.";
        exit;
    }
} else {
    echo "ID de recette invalide.";
    exit;
}
?>

<div class="ReceipContainer">
    <form class="formReceip row" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit_recipe_form">
        <input type="hidden" name="recipe_id" value="<?= $recipe_id; ?>">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>

        <!-- Image Preview Section -->
        <div id="image-preview" class="mb-3">
            <div class="d-flex flex-wrap" id="image-preview-container" style="overflow-x: auto; white-space: nowrap;">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $attachment_id => $attachment): ?>
                        <div class="img-container position-relative me-3 mb-3">
                            <img src="<?= wp_get_attachment_image_url($attachment_id, 'thumbnail'); ?>" class="img-thumbnail" style="max-width: 150px;">
                            <span class="remove-img position-absolute top-0 end-0 bg-danger text-white rounded-circle" style="padding: 5px; cursor: pointer;">&times;</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recipe Name -->
        <div class="col-md-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" value="<?= esc_attr($recipe->post_title); ?>" placeholder="Entrez le nom de votre recette">
                <label for="name">Nom de la recette</label>
            </div>

            <!-- Difficulty -->
            <div class="form-floating mb-3">
                <select class="form-control" id="difficulty" name="difficulty">
                    <option value="⭐" <?= selected($difficulty, '⭐'); ?>>⭐</option>
                    <option value="⭐⭐" <?= selected($difficulty, '⭐⭐'); ?>>⭐⭐</option>
                    <option value="⭐⭐⭐" <?= selected($difficulty, '⭐⭐⭐'); ?>>⭐⭐⭐</option>
                    <option value="⭐⭐⭐⭐" <?= selected($difficulty, '⭐⭐⭐⭐'); ?>>⭐⭐⭐⭐</option>
                    <option value="⭐⭐⭐⭐⭐" <?= selected($difficulty, '⭐⭐⭐⭐⭐'); ?>>⭐⭐⭐⭐⭐</option>
                </select>
                <label for="difficulty">Difficulté</label>
            </div>

            <hr class="my-4">

            <!-- Steps -->
            <div class="form-floating mb-3" id="steps-list">
                <input type="text" class="form-control" id="step-input" name="steps[]" placeholder="Ajouter une étape">
                <label for="step-input" id="steps-label">Etapes</label>
                <?php if (!empty($steps)): ?>
                    <ol>
                        <?php foreach ($steps as $step): ?>
                            <li><?= esc_html($step); ?></li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </div>
            
            <div class="d-flex gap-3 mb-3">
                <button type="button" id="add-step" class="btn btn-primary">Ajouter étape</button>
                <button type="button" id="remove-step" class="btn btn-danger">Supprimer étape</button>
            </div>
        </div>

        <div class="col-md-6 border-start border-3 px-4">
            <!-- Images Input -->
            <div class="form-floating mb-3">
                <input type="file" id="images" name="images[]" accept="image/png, image/jpeg" multiple class="form-control">
                <label for="images">Images</label>
            </div>

            <!-- Cost -->
            <div class="form-floating mb-3">
                <select class="form-control" id="cost" name="cost">
                    <option value="€" <?= selected($cost, '€'); ?>>€</option>
                    <option value="€€" <?= selected($cost, '€€'); ?>>€€</option>
                    <option value="€€€" <?= selected($cost, '€€€'); ?>>€€€</option>
                    <option value="€€€€" <?= selected($cost, '€€€€'); ?>>€€€€</option>
                    <option value="€€€€€" <?= selected($cost, '€€€€€'); ?>>€€€€€</option>
                </select>
                <label for="cost">Coût</label>
            </div>

            <hr class="my-4">

            <!-- Ingredients -->
            <div class="form-floating mb-3" id="ingredient-list">
                <?php if (!empty($ingredients)): ?>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <div class="ingredient-group mb-4">
                            <div class="col-xs-2">
                                <input type="text" class="form-control ingredient-name" name="ingredient[]" value="<?= esc_attr($ingredient['ingredient']); ?>" placeholder="Ingrédient">
                            </div>
                            <div class="input-group">
                                <input type="number" class="form-control ingredient-quantity" name="quantity[]" value="<?= esc_attr($ingredient['quantity']); ?>" placeholder="Quantité">
                                <select class="form-control ingredient-unit" name="unit[]" placeholder="Unité">
                                    <option value="nb" <?= selected($ingredient['unit'], 'nb'); ?>>Unité</option>
                                    <option value="cuillères" <?= selected($ingredient['unit'], 'cuillères'); ?>>Cuillères</option>
                                    <option value="ml" <?= selected($ingredient['unit'], 'ml'); ?>>ML</option>
                                    <option value="cl" <?= selected($ingredient['unit'], 'cl'); ?>>CL</option>
                                    <option value="L" <?= selected($ingredient['unit'], 'L'); ?>>L</option>
                                    <option value="g" <?= selected($ingredient['unit'], 'g'); ?>>Grammes</option>
                                    <option value="Kg" <?= selected($ingredient['unit'], 'Kg'); ?>>Kg</option>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-3 mb-3">
                <button type="button" id="add-ingredient" class="btn btn-primary">Ajouter ingrédient</button>
                <button type="button" id="remove-ingredient" class="btn btn-danger">Supprimer ingrédient</button>
            </div>
        </div>

        <div class="col-12 mt-3">
            <input class="btn btnSubmit" type="submit" value="Mettre à jour la recette">
        </div>
    </form>
</div>

<script>
// Ajoutez ici vos scripts JavaScript de gestion d'événements
</script>

<?php get_footer(); ?>