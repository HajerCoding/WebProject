<?php
// delete_irrigation.php
// Delete irrigation records

include "db_connect.php";

$message = "";
$messageType = "";
$allRecords = array();

// Handle DELETE request
if (isset($_POST['delete']) && isset($_POST['record_id'])) {
    $recordId = mysqli_real_escape_string($conn, $_POST['record_id']);
    
    $sql = "DELETE FROM irrigation_records WHERE id = '$recordId'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $message = "Record deleted successfully!";
        $messageType = "success";
    } else {
        $message = "Error deleting record: " . mysqli_error($conn);
        $messageType = "danger";
    }
}

// Retrieve all records
$sql = "SELECT * FROM irrigation_records ORDER BY record_date DESC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $allRecords[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Irrigation Records</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(companyName) {
            return confirm("Are you sure you want to delete the irrigation record for " + companyName + "?");
        }
    </script>
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
    <h2 class="mb-4" style="color:#703B3B;">Delete Irrigation Records</h2>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5>Irrigation Records - Delete Management</h5>
        </div>
        <div class="card-body">
            <?php if (count($allRecords) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Amount (m³/min)</th>
                                <th>Location</th>
                                <th>Record Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allRecords as $record): ?>
                                <tr>
                                    <td><?php echo $record['id']; ?></td>
                                    <td><?php echo htmlspecialchars($record['company_name']); ?></td>
                                    <td><?php echo number_format($record['irrigation_amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($record['location']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($record['record_date'])); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;" 
                                              onsubmit="return confirmDelete('<?php echo htmlspecialchars($record['company_name']); ?>');">
                                            <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm">
                                                🗑️ Delete
                                            </button>
                                        </form>
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
        <a href="view_irrigation.php" class="btn btn-info">View All Records</a>
        <a href="index.html" class="btn btn-secondary">Home</a>
    </div>
</div>

<footer class="text-white py-4 mt-5" style="background-color:#1B3C53;">
    <div class="container">
        <p class="mb-0">© 2025 AgriSense - All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
