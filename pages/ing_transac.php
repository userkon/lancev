<?php
include '../includes/connection.php';
session_start();

// Check the action
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'add') {
        // Add Ingredient
        $ingcode = $_POST['ingcode'];
        $ingname = $_POST['ingname'];
        $unit = $_POST['unit'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $supplier = $_POST['supplier'];
        $datestock = $_POST['datestock'];

        // Generate the next ID for ingredients
        $query = "SELECT MAX(INGREDIENTS_ID) + 1 AS next_id FROM ingredients";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
        $next_id = $row['next_id'] ? $row['next_id'] : 1;

        // Insert the ingredient into the database
        $query = "INSERT INTO ingredients 
                  (INGREDIENTS_ID, INGREDIENTS_CODE, ING_NAME, UNIT, DESCRIPTION, ING_QUANTITY, SUPPLIER_ID, STOCK_DATE) 
                  VALUES 
                  ('$next_id', '$ingcode', '$ingname', '$unit', '$description', '$quantity', '$supplier', '$datestock')";

        if (mysqli_query($db, $query)) {
            // Redirect with a success status
            echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=success";</script>';
        } else {
            // Redirect with an error status
            echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=error";</script>';
        }

    } elseif ($action == 'edit') {
        // Edit Ingredient
        $id = $_POST['id']; // Ingredient ID
        $ingname = $_POST['name'];
        $quantity = $_POST['quantity'];
        $unit = $_POST['unit'];
        $supplier = $_POST['supplier']; // Add supplier ID from the form

        // Update query
        $query = "UPDATE ingredients 
                  SET ING_NAME = '$ingname', ING_QUANTITY = '$quantity', UNIT = '$unit', SUPPLIER_ID = '$supplier' 
                  WHERE INGREDIENTS_ID = '$id'";

        if (mysqli_query($db, $query)) {
            // Redirect with a success status
            echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=updated";</script>';
        } else {
            // Redirect with an error status
            echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=error";</script>';
        }
    } elseif ($action == 'delete') {
      // Delete Ingredient
      if (isset($_GET['id'])) {
          $id = $_GET['id']; // Ingredient ID to delete

          // Delete query
          $query = "DELETE FROM ingredients WHERE INGREDIENTS_ID = '$id'";
          
          if (mysqli_query($db, $query)) {
              // Redirect with a success status
              echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=deleted";</script>';
          } else {
              // Redirect with an error status
              echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=error";</script>';
          }
      } else {
          echo '<script type="text/javascript">window.location = "pro_ingredients.php?status=missing_id";</script>';
      }
  }
}
?>
