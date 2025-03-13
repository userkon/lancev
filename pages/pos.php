<?php
include '../includes/connection.php';
include '../includes/topp.php';

// QR Scanner functionality
$scanResult = '';
$scanError = '';

// Process QR scan data
if (isset($_POST['qr_scan_data'])) {
    $qrData = filter_input(INPUT_POST, 'qr_scan_data');
    
    // Parse QR code data (assuming format: productName:size:price)
    $scanParts = explode(':', $qrData);
    
    if (count($scanParts) >= 3) {
        $productName = $scanParts[0];
        $size = $scanParts[1];
        $price = floatval($scanParts[2]);
        $quantity = isset($_POST['scan_quantity']) ? intval($_POST['scan_quantity']) : 1;
        
        // Get product details from database using name
        $query = "SELECT PRODUCT_ID FROM product WHERE name = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $productName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $productId);
        
        if (mysqli_stmt_fetch($stmt)) {
            // Add product to cart with scanned information
            if (isset($_SESSION['pointofsale'])) {
                $found = false;
                foreach ($_SESSION['pointofsale'] as $key => $product) {
                    // If product with the same name and size already exists, update its quantity
                    if ($product['name'] == $productName && $product['size'] == $size) {
                        $_SESSION['pointofsale'][$key]['quantity'] += $quantity;
                        $found = true;
                        break;
                    }
                }
                // If product not found (same product, different size), add new entry
                if (!$found) {
                    $_SESSION['pointofsale'][] = array(
                        'id' => $productId,
                        'name' => $productName,
                        'quantity' => $quantity,
                        'size' => $size,
                        'price' => $price
                    );
                }
            } else {
                // If session doesn't have products yet, create the first one
                $_SESSION['pointofsale'][] = array(
                    'id' => $productId,
                    'name' => $productName,
                    'quantity' => $quantity,
                    'size' => $size,
                    'price' => $price
                );
            }
            $scanResult = "Product added successfully: $productName ($size)";
        } else {
            // If product not found in database but we have all the necessary information,
            // add it anyway as a custom product
            if (isset($_SESSION['pointofsale'])) {
                $found = false;
                foreach ($_SESSION['pointofsale'] as $key => $product) {
                    // If product with the same name and size already exists, update its quantity
                    if ($product['name'] == $productName && $product['size'] == $size) {
                        $_SESSION['pointofsale'][$key]['quantity'] += $quantity;
                        $found = true;
                        break;
                    }
                }
                // If product not found, add new entry
                if (!$found) {
                    $_SESSION['pointofsale'][] = array(
                        'id' => 0, // Use 0 for custom products
                        'name' => $productName,
                        'quantity' => $quantity,
                        'size' => $size,
                        'price' => $price
                    );
                }
            } else {
                // If session doesn't have products yet, create the first one
                $_SESSION['pointofsale'][] = array(
                    'id' => 0, // Use 0 for custom products
                    'name' => $productName,
                    'quantity' => $quantity,
                    'size' => $size,
                    'price' => $price
                );
            }
            $scanResult = "Custom product added: $productName ($size)";
        }
        mysqli_stmt_close($stmt);
    } else {
        $scanError = "Invalid QR code format. Expected format: productName:size:price";
    }
}

// Define price based on size (move this above the part where price is used)
$priceList = [
    'medium' => 29,
    'large' => 39
];

// Check if Add to order button has been submitted
if (isset($_POST['addpos'])) {
    $productId = filter_input(INPUT_GET, 'id');
    $productName = filter_input(INPUT_POST, 'name');
    $quantity = filter_input(INPUT_POST, 'quantity');
    $size = filter_input(INPUT_POST, 'size');  // Capture size from the form
    
    // Get the price based on the size
    $price = isset($priceList[$size]) ? $priceList[$size] : 0;
    
    // Check if the product already exists in the session (by both product ID and size)
    if (isset($_SESSION['pointofsale'])) {
        $found = false;
        foreach ($_SESSION['pointofsale'] as $key => $product) {
            // If product with the same ID and size already exists, update its quantity
            if ($product['id'] == $productId && $product['size'] == $size) {
                $_SESSION['pointofsale'][$key]['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        // If product not found (same product, different size), add new entry
        if (!$found) {
            $_SESSION['pointofsale'][] = array(
                'id' => $productId,
                'name' => $productName,
                'quantity' => $quantity,
                'size' => $size,  // Add the size to the session
                'price' => $price  // Store the price
            );
        }
    } else {
        // If session doesn't have products yet, create the first one
        $_SESSION['pointofsale'][] = array(
            'id' => $productId,
            'name' => $productName,
            'quantity' => $quantity,
            'size' => $size,  // Add the size to the session
            'price' => $price  // Store the price
        );
    }
}

// Handle product deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $productId = filter_input(INPUT_GET, 'id');
    $size = filter_input(INPUT_GET, 'size');  // Capture size for deletion
    foreach ($_SESSION['pointofsale'] as $key => $product) {
        if ($product['id'] == $productId && $product['size'] == $size) {
            unset($_SESSION['pointofsale'][$key]);
        }
    }
    $_SESSION['pointofsale'] = array_values($_SESSION['pointofsale']);  // Re-index the array
}
?>

<!-- QR Code Scanner Section -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header py-2">
                <h4 class="m-1 text-lg text-primary">QR Code Scanner</h4>
            </div>
            <div class="card-body">
                <?php if ($scanResult): ?>
                    <div class="alert alert-success"><?php echo $scanResult; ?></div>
                <?php endif; ?>
                
                <?php if ($scanError): ?>
                    <div class="alert alert-danger"><?php echo $scanError; ?></div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div id="qr-reader" style="width: 100%"></div>
                    </div>
                    <div class="col-md-6">
                        <form method="post" id="qr-form">
                            <div class="form-group">
                                <label for="qr_scan_data">QR Code Data:</label>
                                <input type="text" id="qr_scan_data" name="qr_scan_data" class="form-control" placeholder="Scan QR code or enter manually">
                                <small class="form-text text-muted">Format: ProductName:Size:Price (Example: Cappuccino:medium:29.00)</small>
                            </div>
                            <div class="form-group">
                                <label for="scan_quantity">Quantity:</label>
                                <input type="number" id="scan_quantity" name="scan_quantity" class="form-control" value="1" min="1">
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Process QR Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Generator for products -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header py-2">
                <h4 class="m-1 text-lg text-primary">QR Code Generator</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form id="qr-generator-form">
                            <div class="form-group">
                                <label for="product-select">Select Product:</label>
                                <select id="product-select" class="form-control">
                                    <option value="">Select a product</option>
                                    <?php
                                    $productQuery = "SELECT PRODUCT_ID, name FROM product ORDER BY name";
                                    $productResult = mysqli_query($db, $productQuery);
                                    while ($product = mysqli_fetch_assoc($productResult)): ?>
                                        <option value="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Size:</label>
                                <select id="qr-size" class="form-control">
                                    <option value="medium">Medium - $<?php echo $priceList['medium']; ?></option>
                                    <option value="large">Large - $<?php echo $priceList['large']; ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="custom-price">Custom Price (optional):</label>
                                <input type="number" id="custom-price" class="form-control" step="0.01" placeholder="Leave empty to use default">
                            </div>
                            <button type="button" id="generate-qr" class="btn btn-success mt-2">Generate QR Code</button>
                        </form>
                    </div>
                    <div class="col-md-6 text-center">
                        <div id="qr-container">
                            <div id="qrcode"></div>
                            <p id="qr-info" class="mt-2"></p>
                            <button id="print-qr" class="btn btn-secondary mt-2" style="display: none;">Print QR Code</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-0">
            <div class="card-header py-2">
                <h4 class="m-1 text-lg text-primary">Category</h4>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <?php 
                    // Fetch categories from the database
                    $categoryQuery = "SELECT category_id, category_name FROM category ORDER BY category_name";
                    $categoryResult = mysqli_query($db, $categoryQuery) or die('Error fetching categories: ' . mysqli_error($db));

                    // Loop through categories to create the tabs
                    $first = true; // Flag to set the first tab as active
                    while ($category = mysqli_fetch_assoc($categoryResult)): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $first ? 'active' : ''; ?>" href="#category<?php echo $category['category_id']; ?>" data-toggle="tab">
                                <?php echo $category['category_name']; ?>
                            </a>
                        </li>
                    <?php 
                    $first = false; // After the first tab, set this flag to false
                    endwhile; ?>
                </ul>

                <!-- Tab Content Area -->
                <div class="tab-content">
                    <?php 
                    // Fetch categories again to loop through for their products
                    mysqli_data_seek($categoryResult, 0);  // Reset the result pointer
                    $first = true; // Reset the flag
                    while ($category = mysqli_fetch_assoc($categoryResult)):
                        $categoryId = $category['category_id'];
                        $categoryName = $category['category_name'];
                        
                        // Fetch products for this category
                        $productQuery = "SELECT PRODUCT_ID, name FROM product WHERE category_id = $categoryId ORDER BY name";
                        $productResult = mysqli_query($db, $productQuery) or die('Error fetching products: ' . mysqli_error($db));
                    ?>
                        <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="category<?php echo $categoryId; ?>">
                            <h5><?php echo $categoryName; ?></h5>
                            <div class="row">
                                <?php while ($product = mysqli_fetch_assoc($productResult)): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                                <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                                    <label for="quantity-<?php echo $product['PRODUCT_ID']; ?>">Quantity:</label>
                                                    <input type="number" id="quantity-<?php echo $product['PRODUCT_ID']; ?>" name="quantity" value="1" min="1" required>
                                                    
                                                    <!-- Size Selection Buttons -->
                                                    <div class="mt-2">
                                                        <label>Size:</label><br>
                                                        <button type="button" class="btn btn-secondary btn-sm size-btn" data-size="medium">Medium</button>
                                                        <button type="button" class="btn btn-secondary btn-sm size-btn" data-size="large">Large</button>
                                                    </div>
                                                    
                                                    <!-- Hidden input for size -->
                                                    <input type="hidden" name="size" id="size-<?php echo $product['PRODUCT_ID']; ?>" value="medium">
                                                    
                                                    <button type="submit" name="addpos" class="btn btn-success btn-sm mt-2">Add to Order</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php
                        $first = false; // After the first tab, set this flag to false
                    endwhile;
                    ?>
                </div>
                <!-- END TAB CONTENT AREA -->
            </div>
        </div>
    </div>
</div>

<div style="clear:both"></div>  
<br />  

<div class="card shadow mb-4 col-md-12">
    <div class="card-header py-3 bg-white">
        <h4 class="m-2 font-weight-bold text-primary">Order</h4>
    </div>
    <div class="row">    
        <div class="card-body col-md-9">
            <div class="table-responsive">
                <table class="table">    
                    <tr>  
                        <th width="50%">Product Name</th>  
                        <th width="20%">Quantity</th>  
                        <th width="15%">Size</th> 
                        <th width="15%">Price</th> 
                        <th width="10%">Action</th>  
                    </tr>  

                    <?php  
                    if (!empty($_SESSION['pointofsale'])):  
                        foreach ($_SESSION['pointofsale'] as $product): 
                    ?>  
                    <tr>  
                        <td>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </td>  

                        <td>
                            <?php echo $product['quantity']; ?>
                        </td>  

                        <td>
                            <?php echo isset($product['size']) ? htmlspecialchars($product['size']) : 'N/A'; ?>
                        </td>  

                        <td>
                            <?php echo isset($product['price']) ? number_format($product['price'], 2) : 'N/A'; ?>
                        </td>

                        
                        <td>
                            <a href="pos.php?action=delete&id=<?php echo $product['id']; ?>&size=<?php echo urlencode($product['size']); ?>">
                                <div class="btn bg-gradient-danger btn-danger"><i class="fas fa-fw fa-trash"></i></div>
                            </a>
                        </td>  
                    </tr>
                    <?php  
                        endforeach;  
                    else:  
                        echo "<tr><td colspan='5'>No products in the order yet.</td></tr>";
                    endif;
                    ?>  
                </table>
                
            </div>
            
        </div> 
    </div>
    <?php
include 'posside.php'; 
include '../includes/footer.php'; 
?>
</div>

<!-- Add these scripts to the end of your file, before the closing body tag -->
<script src="https://unpkg.com/html5-qrcode@2.2.1/dist/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>

<script>
    // Fixed JavaScript to handle size button selection
    document.querySelectorAll('.size-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var size = this.getAttribute('data-size'); // Get the size (medium or large)
            var form = this.closest('form'); // Get the closest form element
            var sizeInput = form.querySelector('input[name="size"]'); // Get the hidden size input
            sizeInput.value = size; // Update the hidden input with the selected size

            // Optional: Update button styles to highlight selected size
            form.querySelectorAll('.size-btn').forEach(function (btn) {
                btn.classList.remove('btn-primary'); // Remove active class
                btn.classList.add('btn-secondary'); // Reset to secondary
            });
            this.classList.remove('btn-secondary'); // Remove secondary class
            this.classList.add('btn-primary'); // Highlight selected button
        });
    });

    // QR Code Scanner
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`QR Code detected: ${decodedText}`);
        document.getElementById('qr_scan_data').value = decodedText;
        // Optional: auto-submit the form on successful scan
        document.getElementById('qr-form').submit();
    }

    function onScanFailure(error) {
        // Handle scan failure (silent)
    }

    // Initialize QR Code Scanner
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        { fps: 10, qrbox: { width: 250, height: 250 } },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    // FIXED QR Code Generator
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('generate-qr').addEventListener('click', function() {
            const productName = document.getElementById('product-select').value;
            const size = document.getElementById('qr-size').value;
            const customPrice = document.getElementById('custom-price').value;
            
            if (!productName) {
                alert('Please select a product');
                return;
            }

            // Get the price based on size or custom price
            let price;
            if (customPrice && customPrice > 0) {
                price = parseFloat(customPrice).toFixed(2);
            } else {
                // Get price from the selected option text
                const sizeElement = document.getElementById('qr-size');
                const selectedOption = sizeElement.options[sizeElement.selectedIndex];
                const priceText = selectedOption.text.split('$')[1];
                price = parseFloat(priceText).toFixed(2);
            }

            // Format: productName:size:price
            const qrData = `${productName}:${size}:${price}`;
            
            // Clear previous QR code
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';
            
            // Generate QR code
            try {
                new QRCode(qrContainer, {
                    text: qrData,
                    width: 200,
                    height: 200,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                // Show product info
                document.getElementById('qr-info').innerHTML = `<strong>${productName}</strong><br>Size: ${size}<br>Price: $${price}`;
                
                // Show print button
                document.getElementById('print-qr').style.display = 'inline-block';
                
                console.log("QR code generated successfully for:", qrData);
            } catch (error) {
                console.error("Error generating QR code:", error);
                document.getElementById('qr-info').innerHTML = "Error generating QR code. Please try again.";
            }
        });

        // Print QR Code
        document.getElementById('print-qr').addEventListener('click', function() {
            const printWindow = window.open('', '_blank');
            const qrImage = document.getElementById('qrcode').querySelector('img');
            
            if (!qrImage) {
                alert('No QR code has been generated yet.');
                return;
            }
            
            const qrImageSrc = qrImage.src;
            const productInfo = document.getElementById('qr-info').innerHTML;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>QR Code Print</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; }
                        .qr-print { margin: 20px auto; }
                        img { max-width: 300px; }
                        .info { margin-top: 10px; }
                    </style>
                </head>
                <body>
                    <div class="qr-print">
                        <img src="${qrImageSrc}" alt="QR Code">
                        <div class="info">${productInfo}</div>
                    </div>
                    <script>
                        window.onload = function() { 
                            setTimeout(function() { 
                                window.print(); 
                            }, 500);
                        }
                    </script>
                </body>
                </html>
            `);
            
            printWindow.document.close();
        });
    });
</script>