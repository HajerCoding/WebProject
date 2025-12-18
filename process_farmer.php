<?php
// process_farmer.php
// Processes farmer water usage form and displays data

include "db_connect.php";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $farmerName = isset($_POST["farmerName"]) ? trim($_POST["farmerName"]) : "";
    $waterUsage = isset($_POST["waterUsage"]) ? trim($_POST["waterUsage"]) : "";
    $farmSize = isset($_POST["farmSize"]) ? trim($_POST["farmSize"]) : "";
    $entryDate = isset($_POST["entryDate"]) ? trim($_POST["entryDate"]) : "";
    
    // Validate data
    $errors = array();
    
    if (empty($farmerName)) {
        $errors[] = "Farmer name is required";
    }
    
    if (empty($waterUsage) || !is_numeric($waterUsage) || $waterUsage < 0) {
        $errors[] = "Valid water usage is required";
    }
    
    if (empty($farmSize) || !is_numeric($farmSize) || $farmSize <= 0) {
        $errors[] = "Valid farm size is required";
    }
    
    if (empty($entryDate)) {
        $errors[] = "Entry date is required";
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        // Escape values for SQL
        $farmerName_esc = mysqli_real_escape_string($conn, $farmerName);
        $waterUsage_esc = mysqli_real_escape_string($conn, $waterUsage);
        $farmSize_esc = mysqli_real_escape_string($conn, $farmSize);
        $entryDate_esc = mysqli_real_escape_string($conn, $entryDate);
        
        // Insert query
        $sql = "INSERT INTO farmers (farmer_name, water_usage, farm_size, entry_date) 
                VALUES ('$farmerName_esc', '$waterUsage_esc', '$farmSize_esc', '$entryDate_esc')";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            $success = true;
            $insertedId = mysqli_insert_id($conn);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Data - Submitted</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <h2 class="mb-4" style="color:#703B3B;">Farmer Water Usage - Submission Result</h2>
    
    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success">
            <h4>✓ Data Saved Successfully!</h4>
            <p>Your water usage data has been recorded in the database.</p>
        </div>
        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Submitted Data</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Farmer Name</th>
                        <td><?php echo htmlspecialchars($farmerName); ?></td>
                    </tr>
                    <tr>
                        <th>Water Usage</th>
                        <td><?php echo htmlspecialchars($waterUsage); ?> Liters</td>
                    </tr>
                    <tr>
                        <th>Farm Size</th>
                        <td><?php echo htmlspecialchars($farmSize); ?> Hectares</td>
                    </tr>
                    <tr>
                        <th>Entry Date</th>
                        <td><?php echo htmlspecialchars($entryDate); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php 
                            if ($waterUsage > 300) {
                                echo '<span class="badge bg-danger">⚠️ High Usage</span>';
                            } else {
                                echo '<span class="badge bg-success">✓ Normal</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
    <?php elseif (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h4>✗ Error</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="Farmer.html" class="btn btn-primary">← Back to Farmer Page</a>
        <a href="view_farmers.php" class="btn btn-info">View All Farmers</a>
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
