<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Message Result</title>

    <!-- Bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet">
</head>
<body style="font-family:'Times New Roman', Times, serif;">

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#1B3C53;">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.html">
          <img src="logo.png" alt="Logo" height="30" class="me-2">
          <span class="fw-bold">AIM</span>
        </a>
      </div>
    </nav>

    <div class="container my-5">
      <h2 class="mb-4" style="color:#703B3B;">Contact Message Summary</h2>

      <?php
      
        $name    = $_POST["name"];
        $email   = $_POST["email"];
        $role    = $_POST["role"];
        $subject = $_POST["subject"];
        $message = $_POST["message"];

        echo "<table class='table table-bordered'>";
        echo "<tr><th>Full Name</th><td>$name</td></tr>";
        echo "<tr><th>Email</th><td>$email</td></tr>";
        echo "<tr><th>Role</th><td>$role</td></tr>";
        echo "<tr><th>Subject</th><td>$subject</td></tr>";
        echo "<tr><th>Message</th><td>$message</td></tr>";
        echo "</table>";
      ?>

      <a href="Contact Us.html" class="btn btn-secondary mt-3">Back to Contact Form</a>
    </div>

    <footer class="py-4 text-white" style="background-color:#1B3C53;">
      <div class="container">
        <p class="mb-0 text-center">@ 2025 AgriSense - All rights reserved.</p>
      </div>
    </footer>

</body>
</html>
