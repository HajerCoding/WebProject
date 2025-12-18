<?php
// view_research_data.php
// Display aggregated research data from all tables

include "db_connect.php";

// Get statistics from all tables
$stats = array();

// Farmer statistics
$sql = "SELECT 
        COUNT(*) as total_farmers,
        AVG(water_usage) as avg_water,
        SUM(water_usage) as total_water,
        AVG(farm_size) as avg_farm_size
        FROM farmers";
$result = mysqli_query($conn, $sql);
if ($result) {
    $stats['farmers'] = mysqli_fetch_assoc($result);
}

// Company statistics
$sql = "SELECT 
        COUNT(*) as total_companies,
        SUM(total_farms) as total_managed_farms
        FROM companies";
$result = mysqli_query($conn, $sql);
if ($result) {
    $stats['companies'] = mysqli_fetch_assoc($result);
}

// Irrigation statistics
$sql = "SELECT 
        COUNT(*) as total_records,
        AVG(irrigation_amount) as avg_irrigation,
        SUM(irrigation_amount) as total_irrigation
        FROM irrigation_records";
$result = mysqli_query($conn, $sql);
if ($result) {
    $stats['irrigation'] = mysqli_fetch_assoc($result);
}

// Get regional data (anonymized)
$sql = "SELECT 
        location,
        COUNT(*) as record_count,
        AVG(irrigation_amount) as avg_amount
        FROM irrigation_records
        GROUP BY location
        ORDER BY avg_amount DESC";
$result = mysqli_query($conn, $sql);
$regional_data = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $regional_data[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Data Portal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body style="font-family:'Times New Roman', Times, serif; background-color:#f8fafc;">

<nav class="navbar navbar-dark" style="background-color:#1B3C53;">
    <div class="container">
        <a class="navbar-brand" href="index.html">
            <img src="logo.png" alt="Logo" height="30" class="me-2">
            <span class="fw-bold">AIM</span>
        </a>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4" style="color:#703B3B;">Research Data Portal</h2>
    
    <div class="alert alert-info">
        <i class="bi bi-shield-lock me-2"></i>
        <strong>Privacy Notice:</strong> All data displayed is anonymized and aggregated for research purposes only.
    </div>
    
    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-1 text-primary"></i>
                    <h5 class="card-title mt-2">Farmers Tracked</h5>
                    <p class="display-6"><?php echo $stats['farmers']['total_farmers']; ?></p>
                    <small class="text-muted">Active participants</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-building fs-1 text-success"></i>
                    <h5 class="card-title mt-2">Companies</h5>
                    <p class="display-6"><?php echo $stats['companies']['total_companies']; ?></p>
                    <small class="text-muted">Registered organizations</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-droplet-fill fs-1 text-info"></i>
                    <h5 class="card-title mt-2">Data Records</h5>
                    <p class="display-6"><?php echo $stats['irrigation']['total_records']; ?></p>
                    <small class="text-muted">Irrigation measurements</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Water Usage Analysis -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5><i class="bi bi-graph-up me-2"></i>Water Usage Analysis</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Farmer Water Usage</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Average Usage per Farm:</td>
                            <td><strong><?php echo number_format($stats['farmers']['avg_water'], 2); ?> L</strong></td>
                        </tr>
                        <tr>
                            <td>Total Water Logged:</td>
                            <td><strong><?php echo number_format($stats['farmers']['total_water'], 2); ?> L</strong></td>
                        </tr>
                        <tr>
                            <td>Average Farm Size:</td>
                            <td><strong><?php echo number_format($stats['farmers']['avg_farm_size'], 2); ?> ha</strong></td>
                        </tr>
                        <tr>
                            <td>Water Efficiency:</td>
                            <td><strong><?php echo number_format($stats['farmers']['avg_water'] / $stats['farmers']['avg_farm_size'], 2); ?> L/ha</strong></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h6>Irrigation Systems</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Average Flow Rate:</td>
                            <td><strong><?php echo number_format($stats['irrigation']['avg_irrigation'], 2); ?> m³/min</strong></td>
                        </tr>
                        <tr>
                            <td>Total Flow Measured:</td>
                            <td><strong><?php echo number_format($stats['irrigation']['total_irrigation'], 2); ?> m³/min</strong></td>
                        </tr>
                        <tr>
                            <td>Managed Farms:</td>
                            <td><strong><?php echo $stats['companies']['total_managed_farms']; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Avg per Company:</td>
                            <td><strong><?php echo number_format($stats['companies']['total_managed_farms'] / $stats['companies']['total_companies'], 1); ?> farms</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Regional Data -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5><i class="bi bi-geo-alt me-2"></i>Regional Analysis</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Region/Location</th>
                            <th>Number of Records</th>
                            <th>Average Irrigation (m³/min)</th>
                            <th>Research Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($regional_data) > 0): ?>
                            <?php foreach ($regional_data as $region): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($region['location']); ?></td>
                                    <td><?php echo $region['record_count']; ?></td>
                                    <td><?php echo number_format($region['avg_amount'], 2); ?></td>
                                    <td>
                                        <?php if ($region['avg_amount'] > 5): ?>
                                            <span class="badge bg-warning">High Usage Area</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Efficient Usage</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No regional data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Research Insights -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5><i class="bi bi-lightbulb me-2"></i>Key Research Insights</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Water Conservation Opportunities</h6>
                    <?php 
                    $high_usage = ($stats['farmers']['avg_water'] > 300) ? true : false;
                    ?>
                    <p>
                        <?php if ($high_usage): ?>
                            <span class="badge bg-danger">Alert</span> 
                            Average water usage exceeds recommended limits. Research focus areas:
                            efficient irrigation methods, crop selection optimization, and water-saving technologies.
                        <?php else: ?>
                            <span class="badge bg-success">Good</span>
                            Water usage within sustainable limits. Continue monitoring trends and 
                            best practices for maintaining efficiency.
                        <?php endif; ?>
                    </p>
                </div>
                
                <div class="col-md-6">
                    <h6>Recommended Research Areas</h6>
                    <ul>
                        <li>Comparative efficiency of irrigation systems</li>
                        <li>Regional water usage patterns and climate adaptation</li>
                        <li>Farm size correlation with water efficiency</li>
                        <li>Sustainable agriculture practices in Oman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="Researcher.html" class="btn btn-primary">← Back to Researcher Portal</a>
        <a href="index.html" class="btn btn-secondary">Home</a>
    </div>
</div>

<footer class="text-white py-4 mt-5" style="background-color:#1B3C53;">
    <div class="container">
        <p class="mb-0">© 2025 AgriSense - All rights reserved.</p>
    </div>
</footer>

</body>
</html>
