<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check user type and redirect if necessary
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];
    if ($Aa == 'User') {
        ?>
        <script type="text/javascript">
            alert("Restricted Page! You will be redirected to POS");
            window.location = "pos.php";
        </script>
        <?php
    }
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Transaction Details</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="15%">Transaction Number</th>
                        <th width="15%">Transaction Date</th>
                        <th>Product</th>
                        <th width="10%"># of Items</th>
                        <th width="10%">Price</th>
                        <th width="11%">Action</th>
                    </tr>
                </thead>
                <tbody>

<?php
// Fetch transaction details with date
$query = 'SELECT td.TRANS_ID, t.DATE, td.PRODUCT, td.QTY, td.PRICE
          FROM transaction_details td
          JOIN transaction t ON td.TRANS_ID = t.TRANS_ID
          ORDER BY td.TRANS_ID ASC';
$result = mysqli_query($db, $query) or die(mysqli_error($db));

// Loop through transaction details and display them
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['TRANS_ID'] . '</td>';
    echo '<td>' . $row['DATE'] . '</td>';
    echo '<td>' . $row['PRODUCT'] . '</td>';
    echo '<td>' . $row['QTY'] . '</td>';
    echo '<td>' . $row['PRICE'] . '</td>';
    echo '<td align="right">
              <a type="button" class="btn btn-primary bg-gradient-primary" href="trans_view.php?action=edit&id=' . $row['TRANS_ID'] . '">
                  <i class="fas fa-fw fa-th-list"></i> View
              </a>
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
