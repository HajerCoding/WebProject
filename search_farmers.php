<?php
// search_farmers.php
// Search functionality for farmers

include "db_connect.php";

$searchResults = array();
$searchPerformed = false;
$searchTerm = "";

// Check if search was submitted
if (isset($_GET['search']) || isset($_POST['search'])) {
    $searchPerformed = true;
    $searchTerm = isset($_GET['searchTerm']) ? trim($_GET['searchTerm']) : 
                  (isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : "");
    
    if (!empty($searchTerm)) {
        // Escape search term
        $searchTerm_esc = mysqli_real_escape_string($conn, $searchTerm);
        
        // Search in farmer_name or water_usage
        $sql = "SELECT * FROM farmers 
                WHERE farmer_name LIKE '%$searchTerm_esc%' 
                OR water_usage LIKE '%$searchTerm_esc%'
                OR farm_size LIKE '%$searchTerm_esc%'
                ORDER BY entry_date DESC";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
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
    <title>Search Farmers</title>
    
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
    <h2 class="mb-4" style="color:#703B3B;">Search Farmer Records</h2>
    
    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5>Search Criteria</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="search_farmers.php">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" 
                               name="searchTerm" 
                               class="form-control" 
                               placeholder="Enter farmer name, water usage, or farm size..."
                               value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="search" class="btn btn-info w-100">
                            🔍 Search
                        </button>
                    </div>
                </div>
                <small class="text-muted">Search by farmer name, water usage amount, or farm size</small>
            </form>
        </div>
    </div>
    
    <!-- Search Results -->
    <?php if ($searchPerformed): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Search Results</h5>
            </div>
            <div class="card-body">
                <?php if (count($searchResults) > 0): ?>
                    <p class="mb-3">Found <strong><?php echo count($searchResults); ?></strong> result(s) for "<?php echo htmlspecialchars($searchTerm); ?>"</p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Farmer Name</th>
                                    <th>Water Usage (L)</th>
                                    <th>Farm Size (ha)</th>
                                    <th>Entry Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($searchResults as $row): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['farmer_name']); ?></td>
                                        <td><?php echo number_format($row['water_usage'], 2); ?></td>
                                        <td><?php echo number_format($row['farm_size'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['entry_date'])); ?></td>
                                        <td>
                                            <?php if ($row['water_usage'] > 300): ?>
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
                    <div class="alert alert-warning">
                        No results found for "<?php echo htmlspecialchars($searchTerm); ?>"
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="view_farmers.php" class="btn btn-primary">View All Farmers</a>
        <a href="Farmer.html" class="btn btn-secondary">← Back to Farmer Page</a>
        <a href="index.html" class="btn btn-outline-secondary">Home</a>
    </div>
</div>

<footer class="text-white py-4 mt-5" style="background-color:#1B3C53;">
    <div class="container">
        <p class="mb-0">© 2025 AgriSense - All rights reserved.</p>
    </div>
</footer>

</body>
</html>
