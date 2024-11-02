<?php
session_start();
include 'connect.php';
include 'sidebar.php';

if (!isset($_SESSION['username'])) {
    header("Location:login.php"); 
    exit;
}

// Fetch total products and total quantity
$totalProductsQuery = "SELECT COUNT(*) AS totalProducts FROM product";
$totalQuantityQuery = "SELECT SUM(quantity) AS totalQuantity FROM product";

$totalProductsResult = mysqli_query($con, $totalProductsQuery);
$totalQuantityResult = mysqli_query($con, $totalQuantityQuery);

$totalProducts = mysqli_fetch_assoc($totalProductsResult)['totalProducts'];
$totalQuantity = mysqli_fetch_assoc($totalQuantityResult)['totalQuantity'];

// Set to 0 if there are no products
if (is_null($totalQuantity)) {
    $totalQuantity = 0;
}

// Define thresholds
$lowStockThreshold = 5; // Adjust based on your criteria

// Fetch counts for low stock, no stock, and about to expire products
$lowStockQuery = "SELECT COUNT(*) AS lowStockCount FROM product WHERE quantity < $lowStockThreshold";
$noStockQuery = "SELECT COUNT(*) AS noStockCount FROM product WHERE quantity = 0";
$aboutToExpireQuery = "SELECT COUNT(*) AS aboutToExpireCount FROM product WHERE exp < DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND quantity > 0";

$lowStockResult = mysqli_query($con, $lowStockQuery);
$noStockResult = mysqli_query($con, $noStockQuery);
$aboutToExpireResult = mysqli_query($con, $aboutToExpireQuery);

$lowStockCount = mysqli_fetch_assoc($lowStockResult)['lowStockCount'];
$noStockCount = mysqli_fetch_assoc($noStockResult)['noStockCount'];
$aboutToExpireCount = mysqli_fetch_assoc($aboutToExpireResult)['aboutToExpireCount'];

// Calculate percentages
$lowStockPercentage = ($totalQuantity > 0) ? ($lowStockCount / $totalQuantity) * 100 : 0;
$noStockPercentage = ($totalQuantity > 0) ? ($noStockCount / $totalQuantity) * 100 : 0;
$aboutToExpirePercentage = ($totalQuantity > 0) ? ($aboutToExpireCount / $totalQuantity) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Dashboard</h1>
    <div class="container">
        <div class="frame total-products">
            <div class="text-container">
                <div class="header-text">Total Products</div>
                <div class="number"><?php echo $totalProducts; ?></div>
            </div>
        </div>
        <div class="frame total-categories">
            <div class="text-container">
                <div class="header-text">Total Categories</div>
                <div class="number">2</div>
            </div>
        </div>
        <div class="frame overall-quantity">
            <div class="text-container">
                <div class="header-text">Overall Quantity</div>
                <div class="number"><?php echo $totalQuantity; ?></div>
            </div>
        </div>
    </div>
    <div class="status-box low-stock">
    <div class="label">Low Stock Items</div>
    <div class="squares-and-total">
        <div class="rnumber">
            <?php echo $lowStockCount; ?> (<?php echo round($lowStockPercentage, 2); ?>%)
        </div>
        <div class="squares">
            <span class="usage-square" style="background-color: #FF6384;"></span>
            <span class="total-square" style="background-color: #FFFFFF;">
            </span>
        </div>
    </div>
    <canvas id="lowStockChart"></canvas>
</div>

<div class="status-box about-to-expire">
    <div class="label">About to Expire Items</div>
        <div class="rnumber">
            <?php echo $aboutToExpireCount; ?> (<?php echo round($aboutToExpirePercentage, 2); ?>%)
        </div>
        <div class="squares-and-total">
        <div class="squares">
            <span class="usage-square" style="background-color: #FF6384;"></span>
            <span class="total-square" style="background-color: #FFFFFF;">
            </span>
        </div>
    </div>
    <canvas id="aboutToExpireChart"></canvas>
</div>

<div class="status-box no-stock">
    <div class="label">No Stock Products</div>
    <div class="squares-and-total">
        <div class="rnumber">
            <?php echo $noStockCount; ?> (<?php echo round($noStockPercentage, 2); ?>%)
        </div>
        <div class="squares">
            <span class="usage-square" style="background-color: #FF6384;"></span>
            <span class="total-square" style="background-color: #FFFFFF;">
            </span>
        </div>
    </div>
    <canvas id="noStockChart"></canvas>
</div>

    <script>
        const totalQuantity = <?php echo $totalQuantity; ?>;
        const noStockCount = <?php echo $noStockCount; ?>;
        const lowStockCount = <?php echo $lowStockCount; ?>;
        const aboutToExpireCount = <?php echo $aboutToExpireCount; ?>;

        function createChart(canvasId, label, value, total) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const percentage = (value / total) * 100;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [label, 'Remaining'],
                    datasets: [{
                        data: [percentage, 100 - percentage],
                        backgroundColor: ['#FF6384', '#E0E0E0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label === label 
                                        ? label + ': ' + Math.round(percentage) + '%' 
                                        : '';
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        createChart('noStockChart', 'No Stock', noStockCount, totalQuantity);
        createChart('lowStockChart', 'Low Stock', lowStockCount, totalQuantity);
        createChart('aboutToExpireChart', 'About to Expire', aboutToExpireCount, totalQuantity);
    </script>
</body>
</html>
