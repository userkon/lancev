
<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Admin verification
$query = 'SELECT ID, t.TYPE FROM users u JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = '.$_SESSION['MEMBER_ID'].'';
$result = mysqli_query($db, $query) or die (mysqli_error($db));
      
while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];
    if ($Aa=='User'){
        ?>
        <script type="text/javascript">
            alert("Restricted Page! This is for ADMIN ONLY!");
            window.location = "pos.php";
        </script>
        <?php
    }                
}   
?>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    coffee: {
                        light: '#E6D7C3',
                        DEFAULT: '#8B4513',
                        dark: '#5D2906'
                    }
                }
            }
        }
    }
</script>

<div class="p-3">
    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- First Column -->
        <div class="space-y-6">
            <!-- Product Quantity Sold -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border-l-4 border-green-500">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-green-600 uppercase tracking-wide">Product Sold</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                <?php 
                                $query = "SELECT SUM(QTY) FROM transaction_details";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_array($result)) {
                                    echo number_format($row[0]);
                                }
                                ?> 
                                <span class="text-sm font-normal text-gray-600">Units</span>
                            </div>
                        </div>
                        <div class="rounded-full bg-green-100 p-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border-l-4 border-yellow-500">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-yellow-600 uppercase tracking-wide">Suppliers</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                <?php 
                                $query = "SELECT COUNT(*) FROM supplier";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_array($result)) {
                                    echo number_format($row[0]);
                                }
                                ?>
                                <span class="text-sm font-normal text-gray-600">Partners</span>
                            </div>
                        </div>
                        <div class="rounded-full bg-yellow-100 p-3">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Column -->
        <div class="space-y-6">
            <!-- Employees Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border-l-4 border-blue-500">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-blue-600 uppercase tracking-wide">Employees</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                <?php 
                                $query = "SELECT COUNT(*) FROM employee";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_array($result)) {
                                    echo number_format($row[0]);
                                }
                                ?>
                                <span class="text-sm font-normal text-gray-600">Team Members</span>
                            </div>
                        </div>
                        <div class="rounded-full bg-blue-100 p-3">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Accounts Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border-l-4 border-red-500">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-red-600 uppercase tracking-wide"> Accounts</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                <?php 
                                $query = "SELECT COUNT(*) FROM users WHERE TYPE_ID=2";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_array($result)) {
                                    echo number_format($row[0]);
                                }
                                ?>
                                <span class="text-sm font-normal text-gray-600">Registered </span>
                            </div>
                        </div>
                        <div class="rounded-full bg-red-100 p-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Column -->
        <div class="space-y-6">
            <!-- Products Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border-l-4 border-indigo-500">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-indigo-600 uppercase tracking-wide">Products</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                <?php 
                                $query = "SELECT COUNT(*) FROM product";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_array($result)) {
                                    echo number_format($row[0]);
                                }
                                ?>
                                <span class="text-sm font-normal text-gray-600">Items</span>
                            </div>
                        </div>
                        <div class="rounded-full bg-indigo-100 p-3">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Earnings Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border-l-4 border-green-500">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-green-600 uppercase tracking-wide">Total Earnings</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                ₱<?php 
                                $query = "SELECT SUM(CASH) AS total FROM transaction";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo number_format($row['total'], 2);
                                }
                                ?>
                            </div>
                        </div>
                        <div class="rounded-full bg-green-100 p-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fourth Column - Recent Products -->
        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden h-full transition-all duration-300 hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-coffee mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Recent Products</h3>
                    </div>
                    <div class="space-y-3 max-h-35 overflow-y-auto">
                        <?php 
                        $query = "SELECT NAME, PRODUCT_CODE FROM product order by PRODUCT_ID DESC LIMIT 5";
                        $result = mysqli_query($db, $query) or die(mysqli_error($db));
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<div class="flex items-center p-3 rounded-lg transition-colors hover:bg-gray-50">
                                    <span class="w-8 h-8 rounded-full bg-coffee-light flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-coffee" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                    <div class="w-full"><!-- Expanded text area -->
                                        <h4 class="text-sm font-medium text-gray-800">' . $row[0] . '</h4>
                                        <p class="text-xs text-gray-500">Code: ' . $row[1] . '</p>
                                    </div>
                                </div>';
                        }
                        ?>
                    </div>
                    <div class="mt-5 pt-4 border-t">
                        <a href="product.php" class="flex items-center justify-center py-2 px-4 bg-coffee text-white rounded-lg hover:bg-coffee-dark transition-colors">
                            <span>View All Products</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart Section -->
<div class="mt-8 bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Top Selling Products</h2>
    <div class="relative h-96">
        <canvas id="myPieChart"></canvas>
    </div>
</div>
</div>

<?php
// Fetch top-selling products
$query = "SELECT PRODUCT, SUM(QTY) AS total_sales
        FROM transaction_details
        GROUP BY PRODUCT
        ORDER BY total_sales DESC
        LIMIT 10"; // Top 10 products

$result = mysqli_query($db, $query) or die(mysqli_error($db));

// Prepare arrays for chart data
$productNames = [];
$salesData = [];

while ($row = mysqli_fetch_array($result)) {
    $productNames[] = $row['PRODUCT'];
    $salesData[] = (int)$row['total_sales']; // Ensure data is numeric
}

// Encode PHP arrays to JSON for use in JavaScript
$productNamesJSON = json_encode($productNames);
$salesDataJSON = json_encode($salesData);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Add Chart.js Plugin to display data labels -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    // Get data from PHP
    const productNames = <?php echo $productNamesJSON; ?>;
    const salesData = <?php echo $salesDataJSON; ?>;
    
    // Calculate total for percentages
    const totalSales = salesData.reduce((sum, value) => sum + value, 0);
    
    // Generate colors for the pie slices
    function generateColors(numColors) {
        const baseColors = [
            'rgba(139, 69, 19, 0.7)',   // Brown
            'rgba(54, 162, 235, 0.7)',  // Blue
            'rgba(255, 206, 86, 0.7)',  // Yellow
            'rgba(75, 192, 192, 0.7)',  // Teal
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 99, 132, 0.7)',  // Pink
            'rgba(255, 159, 64, 0.7)',  // Orange
            'rgba(46, 204, 113, 0.7)',  // Green
            'rgba(231, 76, 60, 0.7)',   // Red
            'rgba(52, 73, 94, 0.7)'     // Dark blue
        ];
        
        const borderColors = baseColors.map(color => color.replace('0.7', '1'));
        
        return {
            backgroundColor: baseColors.slice(0, numColors),
            borderColor: borderColors.slice(0, numColors)
        };
    }
    
    const colors = generateColors(productNames.length);
    
    // Register the datalabels plugin
    Chart.register(ChartDataLabels);
    
    // Create Pie Chart with enhanced styling
    const ctx = document.getElementById("myPieChart");
    const myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: productNames,
            datasets: [{
                data: salesData,
                backgroundColor: colors.backgroundColor,
                borderColor: colors.borderColor,
                borderWidth: 1,
                hoverOffset: 15
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                // Configure the datalabels plugin
                datalabels: {
                    formatter: (value, ctx) => {
                        const percentage = ((value / totalSales) * 100).toFixed(1);
                        return `${percentage}%`;
                    },
                    color: '#fff',
                    backgroundColor: 'rgba(0, 0, 0, 0.6)',
                    borderRadius: 3,
                    padding: {
                        top: 5,
                        bottom: 5,
                        left: 8,
                        right: 8
                    },
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    // Only show labels for segments that are large enough
                    display: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        const percentage = (value / totalSales) * 100;
                        return percentage > 3; // Only show if bigger than 3%
                    }
                },
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const percentage = ((value / totalSales) * 100).toFixed(1);
                            return `${context.label}: ${value} units (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                duration: 1500,
                animateRotate: true,
                animateScale: true
            }
        }
    });
</script>

<?php include'../includes/footer.php'; ?>