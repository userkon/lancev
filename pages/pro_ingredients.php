<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Redirect restricted users
$query = 'SELECT ID, t.TYPE FROM users u JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = '.$_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));
// may user type pero tanggal muna

// Fetch supplier data for the "Add Ingredient" form
$supQuery = "SELECT supplier_id, company_name FROM supplier";
$supResult = mysqli_query($db, $supQuery) or die(mysqli_error($db));

// Generate the supplier dropdown
$sup = '<select class="form-control" name="supplier" required>';
$sup .= '<option disabled selected hidden>Select Supplier</option>';
while ($supRow = mysqli_fetch_assoc($supResult)) {
    $sup .= '<option value="' . $supRow['supplier_id'] . '">' . htmlspecialchars($supRow['company_name']) . '</option>';
}
$sup .= '</select>';


// Fetch ingredients data
$sql = "
      SELECT i.INGREDIENTS_ID, i.INGREDIENTS_CODE, i.ING_NAME, i.ING_QUANTITY, i.UNIT, 
            COALESCE(c.category_name, 'Uncategorized') AS CATEGORY_NAME, 
            COALESCE(s.company_name, 'No Supplier') AS SUPPLIER_NAME,
            i.supplier_id
      FROM ingredients i
      LEFT JOIN category c ON i.category_id = c.category_id
      LEFT JOIN supplier s ON i.supplier_id = s.supplier_id
      ORDER BY i.INGREDIENTS_ID ASC";

$result = mysqli_query($db, $sql) or die("Bad SQL: $sql");
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">
            Ingredients
            <a href="#" data-toggle="modal" data-target="#aModal" class="btn btn-primary bg-gradient-primary">
                <i class="fas fa-fw fa-plus"></i>
            </a>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Supplier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['INGREDIENTS_CODE']); ?></td>
                        <td><?= htmlspecialchars($row['ING_NAME']); ?></td>
                        <td><?= htmlspecialchars($row['ING_QUANTITY']); ?></td>
                        <td><?= htmlspecialchars($row['UNIT']); ?></td>
                        <td><?= htmlspecialchars($row['SUPPLIER_NAME']); ?></td>
                        <td>
                        <button 
                            class="btn btn-warning btn-sm edit-ingredient-button" 
                            data-toggle="modal" 
                            data-target="#editIngredientModal" 
                            data-id="<?= $row['INGREDIENTS_ID']; ?>"
                            data-code="<?= $row['INGREDIENTS_CODE']; ?>"
                            data-name="<?= htmlspecialchars($row['ING_NAME']); ?>"
                            data-quantity="<?= $row['ING_QUANTITY']; ?>"
                            data-unit="<?= $row['UNIT']; ?>"
                            data-supplier-id="<?= htmlspecialchars($row['supplier_id']); ?>">
                            Edit
                        </button>
                        <a href="ing_transac.php?action=delete&id=<?= $row['INGREDIENTS_ID']; ?>" 
                          class="btn btn-danger btn-sm" 
                          onclick="return confirm('Are you sure you want to delete this ingredient?')">
                          Delete
                        </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Ingredient Modal -->
<div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Ingredient</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="ing_transac.php?action=add">
                    <div class="form-group">
                        <input class="form-control" placeholder="Ingredient Code" name="ingcode" required readonly>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Ingredient Name" name="ingname" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="unit" required>
                            <option disabled selected hidden>Select Unit</option>
                            <option value="kg">Kilogram</option>
                            <option value="g">Grams</option>
                            <option value="lt">Liter</option>
                            <option value="ml">Milliliters</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea rows="5" cols="50" class="form-control" placeholder="Description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="number" min="1" max="999999999" class="form-control" placeholder="Quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <?php echo $sup; ?>
                    </div>
                    <div class="form-group">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" placeholder="Date Stock In" name="datestock" required>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i> Save</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i> Reset</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Fetch supplier data for the "Edit Ingredient" form
$supQuery = "SELECT supplier_id, company_name FROM supplier";
$supResult = mysqli_query($db, $supQuery) or die(mysqli_error($db));

// Generate the supplier dropdown
$supDropdown = '<select class="form-control" name="supplier" id="editSupplier" required>';
$supDropdown .= '<option disabled selected hidden>Select Supplier</option>';
while ($supRow = mysqli_fetch_assoc($supResult)) {
    $supDropdown .= '<option value="' . $supRow['supplier_id'] . '">' . htmlspecialchars($supRow['company_name']) . '</option>';
}
$supDropdown .= '</select>';
?>

<!-- Edit Ingredient Modal -->
<div class="modal fade" id="editIngredientModal" tabindex="-1" role="dialog" aria-labelledby="editIngredientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIngredientModalLabel">Edit Ingredient</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="ing_transac.php?action=edit">
                    <input type="hidden" id="editIngredientId" name="id">

                    <div class="form-group">
                        <label>Ingredient Name</label>
                        <input type="text" class="form-control" id="editIngredientName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control" id="editIngredientQuantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label>Unit</label>
                        <select class="form-control" id="editIngredientUnit" name="unit" required>
                            <option value="kg">Kilogram</option>
                            <option value="g">Gram</option>
                            <option value="lt">Liter</option>
                            <option value="ml">Milliliter</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Supplier</label>
                        <?php echo $supDropdown; ?> <!-- This will now display the supplier dropdown -->
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success">Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   // Populate Edit Ingredient Modal with data
document.querySelectorAll('.edit-ingredient-button').forEach(button => {
    button.addEventListener('click', function () {
        document.getElementById('editIngredientId').value = this.getAttribute('data-id');
        document.getElementById('editIngredientName').value = this.getAttribute('data-name');
        document.getElementById('editIngredientQuantity').value = this.getAttribute('data-quantity');
        document.getElementById('editIngredientUnit').value = this.getAttribute('data-unit');
        
        // Get the supplier_id from the button's data attribute
        const supplierId = this.getAttribute('data-supplier-id');
        const supplierDropdown = document.getElementById('editSupplier');
        
        // Loop through the options and select the matching supplier_id
        for (let i = 0; i < supplierDropdown.options.length; i++) {
            if (supplierDropdown.options[i].value == supplierId) {
                supplierDropdown.selectedIndex = i;
                break;
            }
        }
    });
});

</script>


<?php include '../includes/footer.php'; ?>
