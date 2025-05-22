<?php get_header(); ?>

<?php
    $recipe_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;

    if (!$recipe_id) {
        echo "Recette non trouvée ou type de post invalide.";
        exit;
    }

    $recipe = get_post($recipe_id);

    setup_postdata($recipe);

    if (!($recipe && $recipe->post_type === 'recipes')) {
        echo "ID de recette invalide.";
        exit;
    }

    $ingredients = get_post_meta($recipe_id, '_ingredients', true);
    $difficulty = get_post_meta($recipe_id, '_difficulty', true);
    $cost = get_post_meta($recipe_id, '_cost', true);
    $steps = explode("\n", get_post_field('post_content', $recipe_id));
    $duration = get_post_meta($recipe_id, '_duration', true);
    $count = 1;

    $images = get_children([
        'post_parent' => $recipe_id,
        'post_type' => 'attachment',
        'post_mime_type' => 'image'
    ]);

    //Taxonomy
    $meal_type = wp_get_post_terms($recipe_id, 'meal_type');
    $meal_type_value = !empty($meal_type) ? array_map(function($term) { return $term->slug; }, $meal_type) : [];
?>

<div class="recipeContainer">
    <form class="formRecipe row" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit_recipe_form">
        <input type="hidden" name="recipe_id" value="<?= $recipe_id; ?>">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>

        <div id="image-preview" class="mb-3">
            <div class="d-flex flex-wrap" id="image-preview-container" style="overflow-x: auto; white-space: nowrap;">
                
            </div>
        </div>

        <div id="image-preview" class="mb-3">
            <div class="d-flex flex-wrap" id="image-preview-container" style="overflow-x: auto; white-space: nowrap;"></div>
        </div>

        <div class="col-md-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" value="<?= esc_attr($recipe->post_title); ?>" placeholder="Entrez le nom de votre recette">
                <label for="name">Nom de la recette</label>
            </div>

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

            <div class="form-floating mb-3">
                <input type="time" class="form-control" id="duration" name="duration" value="<?= $duration ?>" />
                <label for="duration">Durée</label>
            </div>

            <hr class="my-4">

            <div class="form-floating mb-3" id="steps-list">
                <?php foreach($steps as $step) : ?>
                    <?php if(!empty(ltrim($step, "- "))): ?>
                    <div class="step mb-2">
                        <?= "<b>#" . $count . "</b> " . ltrim($step, "- ") ?>
                        <input type="hidden" class="form-control" id="step-input" name="steps[]" value="<?= ltrim($step, "- ") ?>" placeholder="Ajouter une étape">
                    </div>
                    <?php $count++ ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <input type="text" class="form-control" id="step-input" name="steps[]" placeholder="Ajouter une étape">
                <label for="step-input" id="steps-label">Etapes</label>
            </div>
            
            <div class="d-flex gap-3 mb-3">
                <button type="button" id="add-step" class="btn btn-primary">Ajouter étape</button>
                <button type="button" id="remove-step" class="btn btn-danger">Supprimer étape</button>
            </div>
        </div>

        <div class="col-md-6 border-start border-3 px-4">
            <div class="form-floating mb-3">
                <input type="file" id="images" name="images[]" accept="image/png, image/jpeg" multiple class="form-control">
                <label for="images">Images</label>
            </div>

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

<div class="form-floating mb-3">
    <label class="form-label-main-meal" for="meal_type">Type(s) de repas</label><br>

    <div class="meal-checkbox-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input custom-checkbox" id="breakfast" name="meal_type[]" value="breakfast" 
            <?= in_array('breakfast', $meal_type_value) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="breakfast">Petit-déjeuner</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input custom-checkbox" id="lunch" name="meal_type[]" value="lunch" 
            <?= in_array('lunch', $meal_type_value) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="lunch">Déjeuner</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input custom-checkbox" id="snack" name="meal_type[]" value="snack" 
            <?php echo in_array('snack', $meal_type_value) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="snack">Goûter</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input custom-checkbox" id="dinner" name="meal_type[]" value="dinner" 
            <?= in_array('dinner', $meal_type_value) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="dinner">Dîner</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input custom-checkbox" id="totry" name="meal_type[]" value="totry" 
            <?= in_array('totry', $meal_type_value) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="totry">À essayer</label>
        </div>
    </div>
</div>

            <hr class="my-4">

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
            <input class="btn btnSubmit" type="submit" value="Modifier">
        </div>
    </form>
</div>

<script>
    function loadPreviewImages()
    {
        const images = [];
    <?php foreach ($images as $attachment_id => $attachment): ?>
        images.push({url: "<?= wp_get_attachment_image_url($attachment_id, 'thumbnail'); ?>"});
    <?php endforeach; ?>
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = '';

        if(images.length > 0){
            images.forEach(image => {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('img-container', 'position-relative', 'me-3', 'mb-3');
                let img = createPreviewImage(image.url)

                imgContainer.appendChild(img);
                previewContainer.appendChild(imgContainer);
            });
        }
    }

    function createPreviewImage(imageLink)
    {
        const previewContainer = document.getElementById('image-preview-container');

        const img = document.createElement('img');
        img.src = imageLink;
        img.classList.add('img-thumbnail');
        img.style.maxWidth = '150px';
        img.style.height = 'auto';

        return img
    }

    function createRemoveButtonPreviewImage()
    {
        const removeBtn = document.createElement('span');
        removeBtn.classList.add('remove-img');
        removeBtn.appendChild(document.createTextNode('X'));

        return removeBtn;
    }

    loadPreviewImages();
    
    document.getElementById('images').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = '';

        for (const file of files) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('img-container', 'position-relative', 'me-3', 'mb-3');

                const removeBtn = createRemoveButtonPreviewImage();

                imgContainer.appendChild(createPreviewImage(e.target.result));
                imgContainer.appendChild(removeBtn);
                previewContainer.appendChild(imgContainer);

                removeBtn.addEventListener('click', function() {
                    imgContainer.remove();
                    const dataTransfer = new DataTransfer();
                    Array.from(event.target.files).forEach(f => {
                        if (f.name !== file.name) {
                            dataTransfer.items.add(f);
                        }
                    });
                    event.target.files = dataTransfer.files;
                });
            }

            reader.readAsDataURL(file);
        }
    });

    let stepNumber = 1;

    document.getElementById('add-step').addEventListener('click', function() {
    const stepInput = document.getElementById('step-input');
    const stepValue = stepInput.value.trim();

    if (stepValue) {
        const stepContainer = document.getElementById('steps-list');
        
        const stepDiv = document.createElement('div');
        stepDiv.classList.add('step', 'mb-2');
        
        const stepNumberDiv = document.createElement('strong');
        stepNumberDiv.classList.add('step-number');
        stepNumberDiv.textContent = `${stepNumber}# `;
        
        const stepText = document.createElement('span');
        stepText.classList.add('step-text');
        stepText.textContent = stepValue;
        
        stepDiv.appendChild(stepNumberDiv);
        stepDiv.appendChild(stepText);
        
        stepContainer.insertBefore(stepDiv, stepInput);
        
        let stepInputField = document.createElement('input');
        stepInputField.setAttribute('type', 'hidden');
        stepInputField.setAttribute('name', 'steps[]');
        stepInputField.setAttribute('value', stepValue);
        document.querySelector('form').appendChild(stepInputField);

        stepInput.value = '';
        stepNumber++;

        // Masquer le label une fois qu'une étape est ajoutée
        const steps = document.querySelectorAll('.step');
        if (steps.length > 0) {
            document.getElementById('steps-label').style.display = 'none';
        }
    }
});


    document.getElementById('remove-step').addEventListener('click', function() {
        const steps = document.querySelectorAll('.step');
        console.log(steps);
        
        if (steps.length > 0) {
            steps[steps.length - 1].remove();
            stepNumber--;
        }

        const stepsRemaining = document.querySelectorAll('.step');
        if (stepsRemaining.length < 1) {
            document.getElementById('steps-label').style.display = 'block';
        }
    });

    document.getElementById('step-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('add-step').click();
        }
    });

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const ingredientList = document.getElementById('ingredient-list');
        
        const newIngredient = document.createElement('div');
        newIngredient.classList.add('ingredient-group', 'mb-3');
        
        const ingredientNameInput = document.createElement('input');
        ingredientNameInput.setAttribute('type', 'text');
        ingredientNameInput.classList.add('form-control', 'ingredient-name');
        ingredientNameInput.setAttribute('name', 'ingredient[]');
        
        const quantityDiv = document.createElement('div');
        quantityDiv.classList.add('input-group');
        
        const quantityInput = document.createElement('input');
        quantityInput.setAttribute('type', 'number');
        quantityInput.classList.add('form-control', 'ingredient-quantity');
        quantityInput.setAttribute('name', 'quantity[]');
        quantityInput.setAttribute('placeholder', 'Quantité');
        
        const unitSelect = document.createElement('select');
        unitSelect.classList.add('form-control', 'ingredient-unit');
        unitSelect.setAttribute('name', 'unit[]');
        
        const units = ['nb', 'cuillères', 'ml', 'cl', "L", "g", "Kg"];
        units.forEach(unit => {
            const option = document.createElement('option');
            option.setAttribute('value', unit);
            option.textContent = unit.charAt(0).toUpperCase() + unit.slice(1);
            unitSelect.appendChild(option);
        });
        
        quantityDiv.appendChild(quantityInput);
        quantityDiv.appendChild(unitSelect);
        
        newIngredient.appendChild(ingredientNameInput);
        newIngredient.appendChild(quantityDiv);
        
        ingredientList.appendChild(newIngredient);
    });

    document.getElementById('remove-ingredient').addEventListener('click', function() {
        const ingredients = document.querySelectorAll('.ingredient-group');
        if (ingredients.length > 1) {
            ingredients[ingredients.length - 1].remove();
        }
    });
</script>

<?php get_footer(); ?>
