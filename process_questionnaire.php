<?php
// This page receives the questionnaire form and saves it in the database,
// then shows the saved values in a table.

include "db_connect.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //  Read values from the form
    $name         = "";
    $email        = "";
    $type         = "";
    $satisfaction = "";
    $message      = "";
    $agree        = 0;
    $topics_arr   = array();

    if (isset($_POST["qName"]))         { $name         = trim($_POST["qName"]); }
    if (isset($_POST["qEmail"]))        { $email        = trim($_POST["qEmail"]); }
    if (isset($_POST["qType"]))         { $type         = $_POST["qType"]; }
    if (isset($_POST["qSatisfaction"])) { $satisfaction = $_POST["qSatisfaction"]; }
    if (isset($_POST["qMessage"]))      { $message      = trim($_POST["qMessage"]); }
    if (isset($_POST["qAgree"]))        { $agree        = 1; }

    if (isset($_POST["topics"])) {
        $topics_arr = $_POST["topics"];      // array of checkbox values
    }
    $topics = implode(", ", $topics_arr);     // join topics into one string

    // Escape values for SQL 
    $name_esc   = mysqli_real_escape_string($conn, $name);
    $email_esc  = mysqli_real_escape_string($conn, $email);
    $type_esc   = mysqli_real_escape_string($conn, $type);
    $sat_esc    = mysqli_real_escape_string($conn, $satisfaction);
    $topics_esc = mysqli_real_escape_string($conn, $topics);
    $msg_esc    = mysqli_real_escape_string($conn, $message);

    //  INSERT statement
    $sql = "INSERT INTO questionnaire_feedback
            (full_name, email, user_type, satisfaction, topics, message, agree)
            VALUES
            ('$name_esc',
             '$email_esc',
             '$type_esc',
             '$sat_esc',
             '$topics_esc',
             '$msg_esc',
             $agree)";

    // Execute INSERT
    $result = mysqli_query($conn, $sql);

    if ($result) {
        //  Show a table with the saved values
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Feedback Saved</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body style="background-color:#f8fafc; font-family:'Times New Roman', Times, serif;">
        <div class="container py-5">
            <h2 class="mb-4" style="color:#703B3B;">Thank you for your feedback!</h2>
            <p>Your answers were saved in the database (aim_db → questionnaire_feedback).</p>

            <table class="table table-bordered bg-white">
                <tr><th>Full Name</th><td><?php echo $name; ?></td></tr>
                <tr><th>Email</th><td><?php echo $email; ?></td></tr>
                <tr><th>User Type</th><td><?php echo $type; ?></td></tr>
                <tr><th>Satisfaction</th><td><?php echo $satisfaction; ?></td></tr>
                <tr><th>Topics</th><td><?php echo $topics; ?></td></tr>
                <tr><th>Message</th><td><?php echo $message; ?></td></tr>
                <tr><th>Agree</th><td><?php echo $agree ? "Yes" : "No"; ?></td></tr>
            </table>

            <!-- Button: back to questionnaire -->
            <a href="questionnaire.html" class="btn btn-primary"
               style="background-color:#3C467B; border-color:#3C467B;">
                Back to Questionnaire
            </a>

            <!-- Button: go to edit page with this email -->
            <a href="edit_questionnaire.php?email=<?php echo urlencode($email); ?>"
               class="btn btn-success ms-2"
               style="background-color:#198a44; border-color:#198a44;">
                Edit Your Feedback
            </a>

        </div>
        </body>
        </html>
        <?php
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }

    mysqli_close($conn);  // close connection
} else {
    // If someone opens this page directly, send them back to the form
    header("Location: questionnaire.html");
    exit;
}
?>
