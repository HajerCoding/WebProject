<?php
// process_irrigation.php
// Processes company irrigation form and displays data

include "db_connect.php";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $companyName = isset($_POST["companyName"]) ? trim($_POST["companyName"]) : "";
    $irrigationAmount = isset($_POST["irrigationAmount"]) ? trim($_POST["irrigationAmount"]) : "";
    $location = isset($_POST["location"]) ? trim($_POST["location"]) : "";
    $recordDate = isset($_POST["recordDate"]) ? trim($_POST["recordDate"]) : "";
    
    // Validate data
    $errors = array();
    
    if (empty($companyName)) {
        $errors[] = "Company name is required";
    }
    
    if (empty($irrigationAmount) || !is_numeric($irrigationAmount) || $irrigationAmount <= 0) {
        $errors[] = "Valid irrigation amount is required";
    }
    
    if (empty($location)) {
        $errors[] = "Location is required";
    }
    
    if (empty($recordDate)) {
        $errors[] = "Record date is required";
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        // Escape values for SQL
        $companyName_esc = mysqli_real_escape_string($conn, $companyName);
        $irrigationAmount_esc = mysqli_real_escape_string($conn, $irrigationAmount);
        $location_esc = mysqli_real_escape_string($conn, $location);
        $recordDate_esc = mysqli_real_escape_string($conn, $recordDate);
        
        // Insert query
        $sql = "INSERT INTO irrigation_records (company_name, irrigation_amount, location, record_date) 
                VALUES ('$companyName_esc', '$irrigationAmount_esc', '$location_esc', '$recordDate_esc')";
        
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
    <title>Irrigation Data - Submitted</title>
    
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
    <h2 class="mb-4" style="color:#703B3B;">Irrigation Data - Submission Result</h2>
    
    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success">
            <h4>✓ Data Saved Successfully!</h4>
            <p>Your irrigation data has been recorded in the database.</p>
        </div>
        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Submitted Data</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Company Name</th>
                        <td><?php echo htmlspecialchars($companyName); ?></td>
                    </tr>
                    <tr>
                        <th>Irrigation Amount</th>
                        <td><?php echo htmlspecialchars($irrigationAmount); ?> m³/min</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td><?php echo htmlspecialchars($location); ?></td>
                    </tr>
                    <tr>
                        <th>Record Date</th>
                        <td><?php echo htmlspecialchars($recordDate); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php 
                            if ($irrigationAmount > 5) {
                                echo '<span class="badge bg-danger">⚠️ High Flow</span>';
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
        <a href="Company.html" class="btn btn-primary">← Back to Company Page</a>
        <a href="view_irrigation.php" class="btn btn-info">View All Irrigation Records</a>
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
