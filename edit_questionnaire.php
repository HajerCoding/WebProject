<?php
// edit_questionnaire.php
// This page can search a feedback by email and then edit/update it.

include "db_connect.php";  

$message = "";
$row     = null;
$step    = "search";      

/* user came from "Edit Your Feedback" button (GET)  */
if (isset($_GET["email"])) {
    $email_search = mysqli_real_escape_string($conn, $_GET["email"]);

    $sql = "SELECT * FROM questionnaire_feedback
            WHERE email = '$email_search'
            ORDER BY created_at DESC
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row  = mysqli_fetch_assoc($result);
        $step = "edit";
    } else {
        $message = "No feedback found for this email.";
        $step    = "search";
    }
}

/* user submitted one of the forms (POST) */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /*  search by email */
    if (isset($_POST["find"])) {

        $email_search = "";
        if (isset($_POST["email_search"])) {
            $email_search = trim($_POST["email_search"]);
        }

        if ($email_search == "") {
            $message = "Please enter an email to search.";
            $step    = "search";
        } else {
            $email_search_esc = mysqli_real_escape_string($conn, $email_search);

            $sql = "SELECT * FROM questionnaire_feedback 
                    WHERE email = '$email_search_esc'
                    ORDER BY created_at DESC
                    LIMIT 1";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row  = mysqli_fetch_assoc($result);
                $step = "edit";
            } else {
                $message = "No feedback found for this email.";
                $step    = "search";
            }
        }

    /*  save changes (UPDATE)  */
    } elseif (isset($_POST["save"])) {

        // Read edited values
        $id           = isset($_POST["id"]) ? $_POST["id"] : "";
        $full_name    = isset($_POST["qName"]) ? trim($_POST["qName"]) : "";
        $email        = isset($_POST["qEmail"]) ? trim($_POST["qEmail"]) : "";
        $user_type    = isset($_POST["qType"]) ? $_POST["qType"] : "";
        $satisfaction = isset($_POST["qSatisfaction"]) ? $_POST["qSatisfaction"] : "";
        $message_txt  = isset($_POST["qMessage"]) ? trim($_POST["qMessage"]) : "";
        $agree        = isset($_POST["qAgree"]) ? 1 : 0;

        $topics_arr = array();
        if (isset($_POST["topics"])) {
            $topics_arr = $_POST["topics"];  // array of checkbox values
        }
        $topics = implode(", ", $topics_arr);

        // Simple validation
        $errors = array();

        if ($id == "") {
            $errors[] = "Missing record ID.";
        }
        if ($full_name == "") {
            $errors[] = "Full name cannot be empty.";
        }
        if ($email == "") {
            $errors[] = "Email cannot be empty.";
        }
        if ($message_txt == "" || strlen($message_txt) < 10) {
            $errors[] = "Message must be at least 10 characters.";
        }

        if (count($errors) > 0) {
            // Keep error messages in one string
            $message = "";
            for ($i = 0; $i < count($errors); $i++) {
                $message .= $errors[$i] . "<br>";
            }

            // Rebuild $row to refill the form
            $row = array(
                "id"           => $id,
                "full_name"    => $full_name,
                "email"        => $email,
                "user_type"    => $user_type,
                "satisfaction" => $satisfaction,
                "topics"       => $topics,
                "message"      => $message_txt,
                "agree"        => $agree
            );
            $step = "edit";
        } else {
            // Escape for SQL
            $id_esc           = mysqli_real_escape_string($conn, $id);
            $full_name_esc    = mysqli_real_escape_string($conn, $full_name);
            $email_esc        = mysqli_real_escape_string($conn, $email);
            $user_type_esc    = mysqli_real_escape_string($conn, $user_type);
            $satisfaction_esc = mysqli_real_escape_string($conn, $satisfaction);
            $topics_esc       = mysqli_real_escape_string($conn, $topics);
            $message_esc      = mysqli_real_escape_string($conn, $message_txt);

            // UPDATE statement
            $sql_update = "UPDATE questionnaire_feedback
                           SET full_name    = '$full_name_esc',
                               email        = '$email_esc',
                               user_type    = '$user_type_esc',
                               satisfaction = '$satisfaction_esc',
                               topics       = '$topics_esc',
                               message      = '$message_esc',
                               agree        = $agree
                           WHERE id = $id_esc";

            $result_update = mysqli_query($conn, $sql_update);

            if ($result_update) {
                $message = "Feedback was updated successfully.";

                // Load the updated row again
                $sql  = "SELECT * FROM questionnaire_feedback WHERE id = $id_esc";
                $res2 = mysqli_query($conn, $sql);
                if ($res2 && mysqli_num_rows($res2) > 0) {
                    $row = mysqli_fetch_assoc($res2);
                }
                $step = "done";
            } else {
                $message = "Error updating feedback: " . mysqli_error($conn);
                $step    = "edit";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Questionnaire Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f8fafc; font-family:'Times New Roman', Times, serif;">

<div class="container py-5">
    <h2 class="mb-4" style="color:#703B3B;">Edit Questionnaire Feedback</h2>

    <?php if ($message != "") { ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php } ?>

    <?php if ($step == "search") { ?>
        <!-- Step 1: search by email -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title" style="color:#198a44;">Find Feedback by Email</h5>
                <form method="post" action="edit_questionnaire.php">
                    <div class="mb-3">
                        <label for="email_search" class="form-label">Email</label>
                        <input type="text" name="email_search" id="email_search"
                               class="form-control" placeholder="you@example.com">
                    </div>
                    <button type="submit" name="find" class="btn btn-primary"
                            style="background-color:#3C467B; border-color:#3C467B;">
                        Find Feedback
                    </button>
                </form>
            </div>
        </div>

    <?php } elseif ($step == "edit" && $row != null) { ?>

        <?php
        // Prepare topics list from the stored string
        $topics_selected = array();
        if (isset($row["topics"]) && $row["topics"] != "") {
            $parts = explode(",", $row["topics"]);
            for ($i = 0; $i < count($parts); $i++) {
                $topics_selected[] = trim($parts[$i]);
            }
        }

        $checked1 = in_array("Water saving tips", $topics_selected) ? "checked" : "";
        $checked2 = in_array("Farm analytics", $topics_selected) ? "checked" : "";
        $checked3 = in_array("Government policies", $topics_selected) ? "checked" : "";
        ?>

        <!-- Step 2: edit form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title" style="color:#198a44;">
                    Edit Feedback for: <?php echo $row["email"]; ?>
                </h5>

                <form method="post" action="edit_questionnaire.php">
                    <input type="hidden" name="id"
                           value="<?php echo $row["id"]; ?>">

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="qName" class="form-label">Full Name</label>
                        <input type="text" id="qName" name="qName"
                               class="form-control"
                               value="<?php echo $row["full_name"]; ?>">
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="qEmail" class="form-label">Email</label>
                        <input type="text" id="qEmail" name="qEmail"
                               class="form-control"
                               value="<?php echo $row["email"]; ?>">
                    </div>

                    <!-- User Type -->
                    <div class="mb-3">
                        <label for="qType" class="form-label">You are</label>
                        <select id="qType" name="qType" class="form-select">
                            <?php
                            $types = array("", "Farmer", "Government", "Company", "Researcher", "Student", "Other");
                            for ($i = 0; $i < count($types); $i++) {
                                $t   = $types[$i];
                                $sel = ($t == $row["user_type"]) ? "selected" : "";
                                $label = ($t == "") ? "Choose…" : $t;
                                echo "<option value=\"$t\" $sel>$label</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Satisfaction -->
                    <div class="mb-3">
                        <span class="form-label d-block mb-1">
                            How satisfied are you with AIM?
                        </span>

                        <?php
                        $sat_values = array("Very satisfied", "Satisfied", "Neutral", "Not satisfied");
                        for ($i = 0; $i < count($sat_values); $i++) {
                            $val         = $sat_values[$i];
                            $id_radio    = "sat" . ($i + 1);
                            $checked_sat = ($row["satisfaction"] == $val) ? "checked" : "";
                            echo '<div class="form-check form-check-inline">';
                            echo '<input class="form-check-input" type="radio" ' .
                                 'name="qSatisfaction" id="'.$id_radio.'" value="'.$val.'" '.$checked_sat.'>';
                            echo '<label class="form-check-label" for="'.$id_radio.'">'.$val.'</label>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <!-- Topics -->
                    <div class="mb-3">
                        <span class="form-label d-block mb-1">
                            Which topics are most useful to you?
                        </span>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   id="topic1" name="topics[]"
                                   value="Water saving tips" <?php echo $checked1; ?>>
                            <label class="form-check-label" for="topic1">Water saving tips</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   id="topic2" name="topics[]"
                                   value="Farm analytics" <?php echo $checked2; ?>>
                            <label class="form-check-label" for="topic2">Farm analytics and reports</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   id="topic3" name="topics[]"
                                   value="Government policies" <?php echo $checked3; ?>>
                            <label class="form-check-label" for="topic3">Government water policies</label>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-3">
                        <label for="qMessage" class="form-label">
                            How can we improve AIM for you?
                        </label>
                        <textarea id="qMessage" name="qMessage"
                                  class="form-control" rows="4"><?php
                            echo $row["message"];
                        ?></textarea>
                    </div>

                    <!-- Agreement -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="qAgree" name="qAgree"
                            <?php if ($row["agree"]) echo "checked"; ?>>
                        <label class="form-check-label" for="qAgree">
                            I agree that this feedback may be used to improve the AIM system.
                        </label>
                    </div>

                    <button type="submit" name="save" class="btn btn-primary"
                            style="background-color:#3C467B; border-color:#3C467B;">
                        Save Changes
                    </button>
                    <a href="edit_questionnaire.php" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>

    <?php } elseif ($step == "done" && $row != null) { ?>

        <!-- Step 3: show updated record -->
        <h5 style="color:#198a44;">Updated Feedback</h5>
        <table class="table table-bordered bg-white">
            <tr><th>ID</th><td><?php echo $row["id"]; ?></td></tr>
            <tr><th>Full Name</th><td><?php echo $row["full_name"]; ?></td></tr>
            <tr><th>Email</th><td><?php echo $row["email"]; ?></td></tr>
            <tr><th>User Type</th><td><?php echo $row["user_type"]; ?></td></tr>
            <tr><th>Satisfaction</th><td><?php echo $row["satisfaction"]; ?></td></tr>
            <tr><th>Topics</th><td><?php echo $row["topics"]; ?></td></tr>
            <tr><th>Message</th><td><?php echo $row["message"]; ?></td></tr>
            <tr><th>Agree</th><td><?php echo $row["agree"] ? "Yes" : "No"; ?></td></tr>
            <tr><th>Created At</th><td><?php echo $row["created_at"]; ?></td></tr>
        </table>

        <a href="edit_questionnaire.php" class="btn btn-primary"
           style="background-color:#3C467B; border-color:#3C467B;">
            Edit another feedback
        </a>
    <?php } ?>

</div>

</body>
</html>

