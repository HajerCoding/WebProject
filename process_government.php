<?php
// process_government.php
// This page receives region usage data from Government.html
// saves it in the database, then shows all records in a table.

//  Connect to database (from db_connect.php)
include "db_connect.php";

//  Make sure the request is POST 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //\Read values from form
    $region = "";
    $jan    = 0;
    $feb    = 0;
    $mar    = 0;

    if (isset($_POST["region"])) { $region = trim($_POST["region"]); }
    if (isset($_POST["jan"]))    { $jan    = (int) $_POST["jan"]; }
    if (isset($_POST["feb"]))    { $feb    = (int) $_POST["feb"]; }
    if (isset($_POST["mar"]))    { $mar    = (int) $_POST["mar"]; }

    // Simple validation 
    $errors = array();

    if ($region == "") {
        $errors[] = "Region name cannot be empty.";
    }
    if ($jan <= 0) {
        $errors[] = "January value must be a positive number.";
    }
    if ($feb <= 0) {
        $errors[] = "February value must be a positive number.";
    }
    if ($mar <= 0) {
        $errors[] = "March value must be a positive number.";
    }

    if (count($errors) == 0) {
        //  Escape values for SQL 
        $region_esc = mysqli_real_escape_string($conn, $region);
        $jan_esc    = mysqli_real_escape_string($conn, $jan);
        $feb_esc    = mysqli_real_escape_string($conn, $feb);
        $mar_esc    = mysqli_real_escape_string($conn, $mar);

        // INSERT statement 
        $sql_insert = "INSERT INTO gov_regions_usage
                       (region, january, february, march)
                       VALUES
                       ('$region_esc', $jan_esc, $feb_esc, $mar_esc)";

        $result_insert = mysqli_query($conn, $sql_insert);

        if ($result_insert) {
            $message = "Record was saved successfully.";

            // After insert , select all records to show them in a table
            $sql_select = "SELECT * FROM gov_regions_usage
                           ORDER BY created_at DESC";
            $result_select = mysqli_query($conn, $sql_select);
        } else {
            $message = "Error inserting record: " . mysqli_error($conn);
            $result_select = null;
        }

    } else {
        // If there are validation errors
        $message = "";
        for ($i = 0; $i < count($errors); $i++) {
            $message .= $errors[$i] . "<br>";
        }
        $result_select = null;
    }

} else {
    // If someone opens this page directly, go back to Government.html
    header("Location: Government.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Government Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f8fafc; font-family:'Times New Roman', Times, serif;">

<div class="container py-5">
    <h2 class="mb-4" style="color:#703B3B;">Government - Regional Water Usage</h2>

    <!-- Show message (success or errors) -->
    <?php if (!empty($message)) { ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php } ?>

    <!-- If we have a result set, show table -->
    <?php if (isset($result_select) && $result_select && mysqli_num_rows($result_select) > 0) { ?>
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Region</th>
                <th>January (L)</th>
                <th>February (L)</th>
                <th>March (L)</th>
                <th>Created At</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_select)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["region"]); ?></td>
                    <td><?php echo htmlspecialchars($row["january"]); ?></td>
                    <td><?php echo htmlspecialchars($row["february"]); ?></td>
                    <td><?php echo htmlspecialchars($row["march"]); ?></td>
                    <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No records were found in the database.</p>
    <?php } ?>

    <!-- Back button -->
    <a href="Government.html" class="btn btn-primary"
       style="background-color:#3C467B; border-color:#3C467B;">
        Back to Government Page
    </a>
</div>

</body>
</html>
