<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check if 'action' is set in the URL and equals 'add'
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    // Retrieve recipe name and ingredients data
    $recipe_name = $_POST['recipe_name'];
    $ingredients = $_POST['INGREDIENTS_ID'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit_id'];

    // Generate custom recipe code (RCP + 4-digit number)
    $recipeCodeQuery = "SELECT MAX(CAST(SUBSTRING(recipe_code, 4) AS UNSIGNED)) AS max_code FROM recipes";
    $result = mysqli_query($db, $recipeCodeQuery);

    if (!$result) {
        die('Error executing recipe code query: ' . mysqli_error($db));
    }

    $row = mysqli_fetch_assoc($result);
    $max_code = isset($row['max_code']) ? $row['max_code'] + 1 : 1;
    $recipe_code = 'RCP' . str_pad($max_code, 4, '0', STR_PAD_LEFT);

    // Debugging: Check if the recipe code was generated
    echo "Generated Recipe Code: $recipe_code <br>";

    // Insert recipe into the recipes table
    $recipeQuery = "INSERT INTO recipes (recipe_name, recipe_code) VALUES ('$recipe_name', '$recipe_code')";
    if (!mysqli_query($db, $recipeQuery)) {
        die('Error inserting recipe: ' . mysqli_error($db));
    }

    // Get the ID of the newly inserted recipe
    $recipe_id = mysqli_insert_id($db);

    // Insert ingredients, quantities, and units into the recipe_ingredients table
    for ($i = 0; $i < count($ingredients); $i++) {
        $ingredient_id = $ingredients[$i];
        $quantity = $quantities[$i];
        $unit_id = $units[$i];

        // Insert into recipe_ingredients table
        $ingredientQuery = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit_id) 
                            VALUES ('$recipe_id', '$ingredient_id', '$quantity', '$unit_id')";
        if (!mysqli_query($db, $ingredientQuery)) {
            die('Error inserting ingredient: ' . mysqli_error($db));
        }

        // Convert recipe quantity to stock unit quantity
        $stock_quantity_used = convertToStockUnit($quantity, $unit_id, $ingredient_id);

        // Update the stock quantity in the ingredients table (subtracting the used quantity)
        $updateStockQuery = "UPDATE ingredients 
                             SET ING_QUANTITY = ING_QUANTITY - '$stock_quantity_used' 
                             WHERE INGREDIENTS_ID = '$ingredient_id'";

        if (!mysqli_query($db, $updateStockQuery)) {
            die('Error updating stock: ' . mysqli_error($db));
        }
    }

    // Redirect back to the recipe page with a success message
    echo "<script type='text/javascript'>
        alert('Recipe added successfully!');
        window.location = 'recipe.php';
    </script>";
    exit();
}

// Fetch ingredients for dropdown
$ingredientsSql = "SELECT INGREDIENTS_ID, ING_NAME FROM ingredients ORDER BY ING_NAME ASC";
$ingredientsResult = mysqli_query($db, $ingredientsSql) or die("Bad SQL: $ingredientsSql");

$ingredientsDropdown = "<select class='form-control' name='INGREDIENTS_ID[]' required>
                            <option disabled selected hidden>Select Ingredient</option>";
while ($row = mysqli_fetch_assoc($ingredientsResult)) {
    $ingredientsDropdown .= "<option value='" . $row['INGREDIENTS_ID'] . "'>" . $row['ING_NAME'] . "</option>";
}
$ingredientsDropdown .= "</select>";

// Fetch units for dropdown
$unitsSql = "SELECT unit_id, unit_name FROM units ORDER BY unit_name ASC";
$unitsResult = mysqli_query($db, $unitsSql) or die("Bad SQL: $unitsSql");

$unitsDropdown = "<select class='form-control' name='unit_id[]' required>
                    <option disabled selected hidden>Select Unit</option>";
while ($row = mysqli_fetch_assoc($unitsResult)) {
    $unitsDropdown .= "<option value='" . $row['unit_id'] . "'>" . $row['unit_name'] . "</option>";
}
$unitsDropdown .= "</select>";
?>



<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">
            Recipes
            <a href="#" data-toggle="modal" data-target="#addRecipeModal" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;">
                <i class="fas fa-fw fa-plus"></i>
            </a>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Recipe Code</th>
                        <th>Recipe Name</th>
                        <th>Ingredients | Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "
                        SELECT r.recipe_id, r.recipe_code, r.recipe_name,
                            GROUP_CONCAT(i.ING_NAME, ' | ', ri.quantity, ' ', u.unit_name SEPARATOR '<br>') AS recipe_details
                        FROM recipes r
                        LEFT JOIN recipe_ingredients ri ON r.recipe_id = ri.recipe_id
                        LEFT JOIN ingredients i ON ri.ingredient_id = i.INGREDIENTS_ID
                        LEFT JOIN units u ON ri.unit_id = u.unit_id
                        GROUP BY r.recipe_id
                        ORDER BY r.recipe_code;
                        ";

                    $result = mysqli_query($db, $query) or die(mysqli_error($db));

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['recipe_code']}</td>
                                <td>{$row['recipe_name']}</td>
                                <td>{$row['recipe_details']}</td>
                                <td align='right'>
                                    <a class='btn btn-warning btn-sm' href='#' data-toggle='modal' data-target='#editRecipeModal' onclick='loadEditData({$row['recipe_id']})'>Edit</a>
                                </td>
                              </tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Recipe Modal -->
<div class="modal fade" id="addRecipeModal" tabindex="-1" role="dialog" aria-labelledby="addRecipeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRecipeLabel">Add Recipe</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="recipe_transac.php?action=add">
                    <div class="form-group">
                        <input class="form-control" placeholder="Recipe Name" name="recipe_name" required>
                    </div>
                    <div id="ingredients-container">
                        <div class="form-group">
                            <label>Ingredient</label>
                            <?php echo $ingredientsDropdown; ?>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
                        </div>
                        <div class="form-group">
                            <label>Unit</label>
                            <?php echo $unitsDropdown; ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addIngredientRow()">Add Another Ingredient</button>
                    <hr>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i> Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function addIngredientRow() {
        const container = document.getElementById('ingredients-container');
        const ingredientRow = `
            <div class="form-group">
                <label>Ingredient</label>
                <?php echo $ingredientsDropdown; ?>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <?php echo $unitsDropdown; ?>
            </div>`;
        container.insertAdjacentHTML('beforeend', ingredientRow);
    }





    function loadEditData(recipeId) {
    // Clear existing ingredients
    const container = document.getElementById('edit-ingredients-container');
    container.innerHTML = '';

    // Fetch recipe details via AJAX
    fetch(`get_recipe_details.php?recipe_id=${recipeId}`)
        .then(response => response.json())
        .then(data => {
            // Populate the recipe name
            document.getElementById('edit-recipe-name').value = data.recipe_name;

            // Populate the ingredients
            data.ingredients.forEach(ingredient => {
                const ingredientRow = `
                    <div class="form-group">
                        <label>Ingredient</label>
                        <select class="form-control" name="INGREDIENTS_ID[]" required>
                            <option value="${ingredient.id}" selected>${ingredient.name}</option>
                            <?php echo $ingredientsDropdown; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control" name="quantity[]" value="${ingredient.quantity}" required>
                    </div>
                    <div class="form-group">
                        <label>Unit</label>
                        <select class="form-control" name="unit_id[]" required>
                            <option value="${ingredient.unit_id}" selected>${ingredient.unit_name}</option>
                            <?php echo $unitsDropdown; ?>
                        </select>
                    </div>`;
                container.insertAdjacentHTML('beforeend', ingredientRow);
            });

            // Update the form action with the recipe ID
            document.getElementById('editRecipeForm').action = `recipe_transac.php?action=edit&id=${recipeId}`;
        })
        .catch(error => {
            console.error('Error fetching recipe details:', error);
        });
}

function addEditIngredientRow() {
    const container = document.getElementById('edit-ingredients-container');
    const ingredientRow = `
        <div class="form-group">
            <label>Ingredient</label>
            <?php echo $ingredientsDropdown; ?>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
        </div>
        <div class="form-group">
            <label>Unit</label>
            <?php echo $unitsDropdown; ?>
        </div>`;
    container.insertAdjacentHTML('beforeend', ingredientRow);
}

</script>


<!-- Edit Recipe Modal -->
<div class="modal fade" id="editRecipeModal" tabindex="-1" role="dialog" aria-labelledby="editRecipeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRecipeLabel">Edit Recipe</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRecipeForm" method="post" action="recipe_transac.php?action=edit&id=">
                    <div class="form-group">
                        <label>Recipe Name</label>
                        <input class="form-control" id="edit-recipe-name" name="recipe_name" required>
                    </div>
                    <div id="edit-ingredients-container"></div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addEditIngredientRow()">Add Another Ingredient</button>
                    <hr>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i> Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>




<?php include '../includes/footer.php'; ?>
