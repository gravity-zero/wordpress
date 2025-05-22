<?php
require_once __DIR__ . '/service/utils.php';

    $register_svg = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/user-plus.svg');
    $login_svg = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/log-in.svg');
    $logout_svg = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/log-out.svg');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body>
    <section class="header d-flex justify-content-between align-items-center">

        <div class="icon">
            <a href="/"><img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/favicon.png" alt="ofourno logo" /></a>
        </div>

        <div class="search-bar">
            <form method="get" id="recipe-search-form">
                <input type="hidden" name="action" value="search_recipes"/>
                <input type="text" name="query" id="recipe-search-input" placeholder="Rechercher une recette..." value="" autocomplete="off"/>
            </form>
            <div id="search-results"></div>
        </div>

    <?php if (is_user_logged_in()) : ?>
        <?php global $current_user; wp_get_current_user(); ?>
            <?php if($current_user->ID !== 0): ?>
            <div class="button">
            <a class="button" href="<?= wp_logout_url('/') ?>"><span class="header-icon-inline logout-icon"><?= $logout_svg ?></span></a>
            </div>
            <?php endif ?>
    <?php else : ?>
        <div class="me-4">
            <a class="button me-4" href="/register"><span class="header-icon-inline register-icon"><?= $register_svg ?></span></a>
            <a class="button" href="/login"><span class="header-icon-inline login-icon"><?= $login_svg ?></span></a>
        </div>
    <?php endif; ?>
    </section>
   
<script defer>
    const searchForm = document.getElementById('recipe-search-form');
    const searchInput = document.getElementById('recipe-search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.value = '';

    searchInput.addEventListener('keydown', async function(e) {
        
        if (e.key === "Backspace" || e.key === "ArrowUp" || e.key === "ArrowDown") {
            return;
        }

        if (searchInput.value.trim().length > 1) {
            const formData = new FormData(searchForm);

            const query = await fetch("<?= admin_url('admin-ajax.php'); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams(formData),
            });

            const response = await query.json();

            searchResults.innerHTML = '';
            searchResults.style.border = '1px solid #ddd';

            if (response.success && response.data.length > 0) {
                const ul = document.createElement('ul');
                ul.classList.add('search-results', 'list');

                response.data.forEach(recipe => {
                    const li = document.createElement('li');
                    li.classList.add('search-result', 'items');

                    const link = document.createElement('a');
                    link.href = recipe.message.url;
                    link.classList.add('search-result', 'link');

                    const title_img_div = document.createElement('div');
                    title_img_div.classList.add('search-result', 'title-img');

                    const img = document.createElement('img');
                    img.src = recipe.message.image_url;
                    img.alt = recipe.message.title;
                    img.classList.add('search-result', 'image');
                    title_img_div.appendChild(img)

                    const h3 = document.createElement('h3');
                    h3.textContent = recipe.message.title;
                    h3.classList.add('search-result', 'title');
                    title_img_div.appendChild(h3);

                    const right_div_block = document.createElement('div');
                    right_div_block.classList.add('search-result', 'right-block');

                    const meal_div = document.createElement('div');
                    meal_div.classList.add('search-result', 'recipe-taxonomies');

                    recipe.message.meal_types.forEach((meal_type) => {
                        const span = document.createElement('span');
                        span.textContent = meal_type;
                        span.classList.add('search-result', 'taxonomy-tag');
                        meal_div.appendChild(span)
                    });

                    const infos_div = document.createElement('div');
                    infos_div.classList.add('search-result', 'recipe-infos');
                    
                    const difficulty = document.createElement('p');
                    difficulty.textContent = `Difficulté : ${recipe.message.difficulty}`;
                    difficulty.classList.add('search-result', 'difficulty');

                    const duration = document.createElement('p');
                    duration.textContent = `Durée : ${recipe.message.duration}`;
                    duration.classList.add('search-result', 'duration');

                    const cost = document.createElement('p');
                    cost.textContent = `Coût : ${recipe.message.cost}`;
                    cost.classList.add('search-result', 'cost');

                    
                    infos_div.append(difficulty, duration, cost);

                    right_div_block.append(meal_div, infos_div);

                    link.append(title_img_div, right_div_block);
                    li.appendChild(link);
                    ul.appendChild(li);
                });

                searchResults.appendChild(ul);
            }
        }
    });

</script>