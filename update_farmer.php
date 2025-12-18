<?php
// update_farmer.php
// Update farmer water usage records

include "db_connect.php";

$message = "";
$messageType = "";
$farmerData = null;
$showForm = false;

// Handle UPDATE request
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $farmerName = mysqli_real_escape_string($conn, $_POST['farmerName']);
    $waterUsage = mysqli_real_escape_string($conn, $_POST['waterUsage']);
    $farmSize = mysqli_real_escape_string($conn, $_POST['farmSize']);
    $entryDate = mysqli_real_escape_string($conn, $_POST['entryDate']);
    
    $sql = "UPDATE farmers 
            SET farmer_name = '$farmerName',
                water_usage = '$waterUsage',
                farm_size = '$farmSize',
                entry_date = '$entryDate'
            WHERE id = '$id'";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $message = "Farmer record updated successfully!";
        $messageType = "success";
    } else {
        $message = "Error updating record: " . mysqli_error($conn);
        $messageType = "danger";
    }
}

// Handle EDIT request (load data for editing)
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $sql = "SELECT * FROM farmers WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $farmerData = mysqli_fetch_assoc($result);
        $showForm = true;
    } else {
        $message = "Farmer record not found!";
        $messageType = "warning";
    }
}

// Get all farmers for listing
$allFarmers = array();
$sql = "SELECT * FROM farmers ORDER BY entry_date DESC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $allFarmers[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Farmer Records</title>
    
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
    <h2 class="mb-4" style="color:#703B3B;">Update Farmer Records</h2>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($showForm && $farmerData): ?>
        <!-- Edit Form -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5>Edit Farmer Record</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="update_farmer.php">
                    <input type="hidden" name="id" value="<?php echo $farmerData['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="farmerName" class="form-label">Farmer Name *</label>
                        <input type="text" 
                               class="form-control" 
                               id="farmerName" 
                               name="farmerName" 
                               value="<?php echo htmlspecialchars($farmerData['farmer_name']); ?>"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="waterUsage" class="form-label">Water Usage (Liters) *</label>
                        <input type="number" 
                               step="0.01"
                               class="form-control" 
                               id="waterUsage" 
                               name="waterUsage" 
                               value="<?php echo $farmerData['water_usage']; ?>"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="farmSize" class="form-label">Farm Size (Hectares) *</label>
                        <input type="number" 
                               step="0.01"
                               class="form-control" 
                               id="farmSize" 
                               name="farmSize" 
                               value="<?php echo $farmerData['farm_size']; ?>"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="entryDate" class="form-label">Entry Date *</label>
                        <input type="date" 
                               class="form-control" 
                               id="entryDate" 
                               name="entryDate" 
                               value="<?php echo $farmerData['entry_date']; ?>"
                               required>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-success">
                        ✓ Update Record
                    </button>
                    <a href="update_farmer.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- List All Farmers -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>All Farmer Records - Select to Edit</h5>
        </div>
        <div class="card-body">
            <?php if (count($allFarmers) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Farmer Name</th>
                                <th>Water Usage (L)</th>
                                <th>Farm Size (ha)</th>
                                <th>Entry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allFarmers as $farmer): ?>
                                <tr>
                                    <td><?php echo $farmer['id']; ?></td>
                                    <td><?php echo htmlspecialchars($farmer['farmer_name']); ?></td>
                                    <td><?php echo number_format($farmer['water_usage'], 2); ?></td>
                                    <td><?php echo number_format($farmer['farm_size'], 2); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($farmer['entry_date'])); ?></td>
                                    <td>
                                        <a href="update_farmer.php?edit=<?php echo $farmer['id']; ?>" 
                                           class="btn btn-warning btn-sm">
                                            ✏️ Edit
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No farmer records found in the database.</div>
            <?php endif; ?>
        </div>
    </div>
    
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
