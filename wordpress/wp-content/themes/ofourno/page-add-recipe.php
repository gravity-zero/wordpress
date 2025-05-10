<?php get_header(); ?>
<div class="ReceipContainer">
    <form class="formReceip row" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="new_recipe_form">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>
        

        <div id="image-preview" class="mb-3">
            <div class="d-flex flex-wrap" id="image-preview-container" style="overflow-x: auto; white-space: nowrap;"></div>
        </div>

        <div class="col-md-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Entrez le nom de votre recette">
                <label for="name">Nom de la recette</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-control" id="difficulty" name="difficulty">
                    <option value="⭐">⭐</option>
                    <option value="⭐⭐">⭐⭐</option>
                    <option value="⭐⭐⭐">⭐⭐⭐</option>
                    <option value="⭐⭐⭐⭐">⭐⭐⭐⭐</option>
                    <option value="⭐⭐⭐⭐⭐">⭐⭐⭐⭐⭐</option>
                </select>
                <label for="difficulty">Difficulté</label>
            </div>

            <hr class="my-4">

            <div class="form-floating mb-3" id="steps-list">
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
                <input type="file" id="images" name="images[]" accept="image/png, image/jpeg" multiple class="form-control" onchange="checkFileSize(this)">
                <label for="images">Images</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-control" id="cost" name="cost">
                    <option value="€">€</option>
                    <option value="€€">€€</option>
                    <option value="€€€">€€€</option>
                    <option value="€€€€">€€€€</option>
                    <option value="€€€€€">€€€€€</option>
                </select>
                <label for="cost">Coût</label>
            </div>

            <hr class="my-4">

            <div class="form-floating mb-3" id="ingredient-list">
                <div id="ingredient1" class="ingredient-group mb-4">
                    <div class="col-xs-2">
                        <input type="text" class="form-control ingredient-name" name="ingredient[]" placeholder="Ingrédient">
                    </div>
                    <div class="input-group">
                        <input type="number" class="form-control ingredient-quantity" name="quantity[]" placeholder="Quantité">
                        <select class="form-control ingredient-unit" name="unit[]" placeholder="Unité">
                            <option value="nb">Unité</option>
                            <option value="cuillères">Cuillères</option>
                            <option value="ml">ML</option>
                            <option value="cl">CL</option>
                            <option value="L">L</option>
                            <option value="g">Grammes</option>
                            <option value="Kg">Kg</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-3 mb-3">
                <button type="button" id="add-ingredient" class="btn btn-primary">Ajouter ingrédient</button>
                <button type="button" id="remove-ingredient" class="btn btn-danger">Supprimer ingrédient</button>
            </div>
        </div>

        <div class="col-12 mt-3">
            <input class="btn btnSubmit" type="submit" value="Soumettre">
        </div>
    </form>
</div>

<script>
    function checkFileSize(input) {
        const files = input.files;
        const maxSize = 2 * 1024 * 1024;

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxSize) {
                alert(`Le fichier ${files[i].name} dépasse la taille limite de 2 Mo.`);
                input.value = '';
                break;
            }
        }
    }

document.getElementById('images').addEventListener('change', function(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('image-preview-container');
    previewContainer.innerHTML = '';

    for (const file of files) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const imgContainer = document.createElement('div');
            imgContainer.classList.add('img-container', 'position-relative', 'me-3', 'mb-3');

            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-thumbnail');
            img.style.maxWidth = '150px';
            img.style.height = 'auto';

            const removeBtn = document.createElement('span');
            removeBtn.classList.add('remove-img');
            removeBtn.appendChild(document.createTextNode('X'));

            imgContainer.appendChild(img);
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
        }

        const steps = document.querySelectorAll('.step');
        if (steps.length > 0) {
            document.getElementById('steps-label').style.display = 'none';
        }
    });

    document.getElementById('remove-step').addEventListener('click', function() {
        const steps = document.querySelectorAll('.step');
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
