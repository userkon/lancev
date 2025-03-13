<?php
include '../includes/connection.php';
?>
<!-- Page Content -->
<div class="col-lg-12">
    <?php
    // Gather POST data
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $desc = mysqli_real_escape_string($db, $_POST['description']);
    $cat = mysqli_real_escape_string($db, $_POST['category']);
    $recipe = isset($_POST['recipe']) ? (int)$_POST['recipe'] : null; // Use recipe_id
    $dats = date('Y-m-d'); // Current date
    $qty = 1; // Default quantity is 1
    $on_hand = 1; // Default "ON_HAND" value is 1

    switch ($_GET['action']) {
        case 'add':
            for ($i = 0; $i < $qty; $i++) {
                // Generate PRODUCT_CODE
                $prefix = "PROD";
                $query = "SELECT MAX(PRODUCT_ID) AS last_id FROM product";
                $result = mysqli_query($db, $query) or die('Error fetching last PRODUCT_ID: ' . mysqli_error($db));
                $row = mysqli_fetch_assoc($result);
                $last_id = $row['last_id'] ? $row['last_id'] : 0;
                $incremented_id = str_pad((int)$last_id + 1, 3, '0', STR_PAD_LEFT); // Format as 3 digits
                $product_code = $prefix . $incremented_id;

                // Insert product into the database
                $query = "INSERT INTO product
                          (PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, recipe_id, QTY_STOCK, ON_HAND, category_id, DATE_STOCK_IN)
                          VALUES (NULL, '{$product_code}', '{$name}', '{$desc}', {$recipe}, {$qty}, {$on_hand}, {$cat}, '{$dats}')";

                mysqli_query($db, $query) or die('Error inserting product into Database: ' . mysqli_error($db));
            }
            break;
    }
    ?>
    <script type="text/javascript">window.location = "product.php";</script>
</div>

<?php
include '../includes/footer.php';
?>
