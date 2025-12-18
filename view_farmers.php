<?php
// view_farmers.php
// Displays all farmers using class, array of objects, and function

include "db_connect.php";

// ========================================
// CLASS DEFINITION - Farmer
// ========================================
class Farmer {
    // Attributes
    private $id;
    private $farmerName;
    private $waterUsage;
    private $farmSize;
    private $entryDate;
    
    // Constructor
    public function __construct($id, $farmerName, $waterUsage, $farmSize, $entryDate) {
        $this->id = $id;
        $this->farmerName = $farmerName;
        $this->waterUsage = $waterUsage;
        $this->farmSize = $farmSize;
        $this->entryDate = $entryDate;
    }
    
    // Getter methods
    public function getId() {
        return $this->id;
    }
    
    public function getFarmerName() {
        return $this->farmerName;
    }
    
    public function getWaterUsage() {
        return $this->waterUsage;
    }
    
    public function getFarmSize() {
        return $this->farmSize;
    }
    
    public function getEntryDate() {
        return $this->entryDate;
    }
    
    // Setter methods
    public function setFarmerName($name) {
        $this->farmerName = $name;
    }
    
    public function setWaterUsage($usage) {
        $this->waterUsage = $usage;
    }
    
    public function setFarmSize($size) {
        $this->farmSize = $size;
    }
    
    // Additional method - Check if usage is high
    public function isHighUsage() {
        return $this->waterUsage > 300;
    }
    
    // Additional method - Get water efficiency (usage per hectare)
    public function getWaterEfficiency() {
        if ($this->farmSize > 0) {
            return round($this->waterUsage / $this->farmSize, 2);
        }
        return 0;
    }
}

// ========================================
// RETRIEVE DATA FROM DATABASE
// ========================================
$sql = "SELECT * FROM farmers ORDER BY entry_date DESC";
$result = mysqli_query($conn, $sql);

// Create array of Farmer objects
$farmerArray = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $farmer = new Farmer(
            $row['id'],
            $row['farmer_name'],
            $row['water_usage'],
            $row['farm_size'],
            $row['entry_date']
        );
        $farmerArray[] = $farmer;
    }
}

mysqli_close($conn);

// ========================================
// FUNCTION TO DISPLAY FARMERS IN TABLE
// ========================================
function displayFarmersTable($farmers) {
    if (empty($farmers)) {
        echo '<div class="alert alert-info">No farmer records found in the database.</div>';
        return;
    }
    
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-bordered">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Farmer Name</th>';
    echo '<th>Water Usage (L)</th>';
    echo '<th>Farm Size (ha)</th>';
    echo '<th>Efficiency (L/ha)</th>';
    echo '<th>Entry Date</th>';
    echo '<th>Status</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    // Iterate through array of Farmer objects
    foreach ($farmers as $farmer) {
        echo '<tr>';
        echo '<td>' . $farmer->getId() . '</td>';
        echo '<td>' . htmlspecialchars($farmer->getFarmerName()) . '</td>';
        echo '<td>' . number_format($farmer->getWaterUsage(), 2) . '</td>';
        echo '<td>' . number_format($farmer->getFarmSize(), 2) . '</td>';
        echo '<td>' . number_format($farmer->getWaterEfficiency(), 2) . '</td>';
        echo '<td>' . date('M d, Y', strtotime($farmer->getEntryDate())) . '</td>';
        echo '<td>';
        
        // Use method to check status
        if ($farmer->isHighUsage()) {
            echo '<span class="badge bg-danger">⚠️ High</span>';
        } else {
            echo '<span class="badge bg-success">✓ Normal</span>';
        }
        
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// Calculate statistics
$totalFarmers = count($farmerArray);
$totalWater = 0;
$totalSize = 0;
$highUsageCount = 0;

foreach ($farmerArray as $farmer) {
    $totalWater += $farmer->getWaterUsage();
    $totalSize += $farmer->getFarmSize();
    if ($farmer->isHighUsage()) {
        $highUsageCount++;
    }
}

$avgWater = $totalFarmers > 0 ? $totalWater / $totalFarmers : 0;
$avgSize = $totalFarmers > 0 ? $totalSize / $totalFarmers : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Farmers - View Data</title>
    
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
    <h2 class="mb-4" style="color:#703B3B;">All Farmer Records</h2>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Farmers</h5>
                    <p class="display-6 text-primary"><?php echo $totalFarmers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Avg Water Usage</h5>
                    <p class="display-6 text-info"><?php echo number_format($avgWater, 2); ?> L</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Avg Farm Size</h5>
                    <p class="display-6 text-success"><?php echo number_format($avgSize, 2); ?> ha</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">High Usage</h5>
                    <p class="display-6 text-danger"><?php echo $highUsageCount; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Farmer Water Usage Records</h5>
        </div>
        <div class="card-body">
            <?php displayFarmersTable($farmerArray); ?>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="Farmer.html" class="btn btn-primary">← Back to Farmer Page</a>
        <a href="search_farmers.php" class="btn btn-info">Search Farmers</a>
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
