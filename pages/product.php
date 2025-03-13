<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Restrict page for 'User' type
$query = 'SELECT ID, t.TYPE FROM users u JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));
//tanggal muna c user


// Fetch categories
$sql = "SELECT DISTINCT category_name, category_id FROM category ORDER BY category_name ASC";
$result = mysqli_query($db, $sql) or die("Bad SQL: $sql");

$categoryDropdown = "<select class='form-control' name='category' required>
    <option disabled selected hidden>Select Category</option>";
while ($row = mysqli_fetch_assoc($result)) {
    $categoryDropdown .= "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
}
$categoryDropdown .= "</select>";

// Fetch recipes
$sqlRecipes = "SELECT recipe_id, recipe_name FROM recipes ORDER BY recipe_name ASC";
$resultRecipes = mysqli_query($db, $sqlRecipes) or die("Bad SQL: $sqlRecipes");

$recipeDropdown = "<select class='form-control' name='recipe' required>
    <option disabled selected hidden>Select Recipe</option>";
while ($row = mysqli_fetch_assoc($resultRecipes)) {
    $recipeDropdown .= "<option value='" . $row['recipe_id'] . "'>" . $row['recipe_name'] . "</option>";
}
$recipeDropdown .= "</select>";
?>

<!-- Product List -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Products&nbsp;
            <a href="#" data-toggle="modal" data-target="#aModal" type="button" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;">
                <i class="fas fa-fw fa-plus"></i>
            </a>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Recipe</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = 'SELECT p.PRODUCT_ID, p.PRODUCT_CODE, p.NAME, p.DESCRIPTION, r.recipe_name, c.category_name 
                        FROM product p
                        JOIN category c ON p.category_id = c.category_id
                        LEFT JOIN recipes r ON p.recipe_id = r.recipe_id';

                    $result = mysqli_query($db, $query) or die(mysqli_error($db));

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['PRODUCT_CODE'] . '</td>';
                        echo '<td>' . $row['NAME'] . '</td>';
                        echo '<td>' . $row['DESCRIPTION'] . '</td>';
                        echo '<td>' . $row['recipe_name'] . '</td>';
                        echo '<td>' . $row['category_name'] . '</td>';
                        echo '<td align="right">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-primary bg-gradient-primary" href="pro_searchfrm.php?action=edit&id=' . $row['PRODUCT_CODE'] . '">
                                        <i class="fas fa-fw fa-list-alt"></i> Details
                                    </a>
                                    <div class="btn-group">
                                        <a type="button" class="btn btn-primary bg-gradient-primary dropdown no-arrow" data-toggle="dropdown" style="color:white;">... <span class="caret"></span></a>
                                        <ul class="dropdown-menu text-center" role="menu">
                                            <li>
                                                <a type="button" class="btn btn-warning bg-gradient-warning btn-block" style="border-radius: 0px;" href="pro_edit.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                                    <i class="fas fa-fw fa-edit"></i> Edit
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>

<!-- Add Product Modal -->
<div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="pro_transac.php?action=add">
                    <div class="form-group">
                        <input class="form-control" value="Automatically Generated" readonly>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Product Name" name="name" required>
                    </div>
                    <div class="form-group">
                        <textarea rows="5" class="form-control" placeholder="Description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <?php echo $recipeDropdown; ?>
                    </div>
                    <div class="form-group">
                        <?php echo $categoryDropdown; ?>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Save</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i>Reset</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

