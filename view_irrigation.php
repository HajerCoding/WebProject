<?php
// view_irrigation.php
// View all irrigation records

include "db_connect.php";

$sql = "SELECT * FROM irrigation_records ORDER BY record_date DESC";
$result = mysqli_query($conn, $sql);

$records = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Irrigation Records</title>
    
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
    <h2 class="mb-4" style="color:#703B3B;">All Irrigation Records</h2>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Company Irrigation Data</h5>
        </div>
        <div class="card-body">
            <?php if (count($records) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Amount (m³/min)</th>
                                <th>Location</th>
                                <th>Record Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?php echo $record['id']; ?></td>
                                    <td><?php echo htmlspecialchars($record['company_name']); ?></td>
                                    <td><?php echo number_format($record['irrigation_amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($record['location']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($record['record_date'])); ?></td>
                                    <td>
                                        <?php if ($record['irrigation_amount'] > 5): ?>
                                            <span class="badge bg-danger">⚠️ High</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">✓ Normal</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No irrigation records found in the database.</div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="Company.html" class="btn btn-primary">← Back to Company Page</a>
        <a href="delete_irrigation.php" class="btn btn-danger">Delete Records</a>
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
