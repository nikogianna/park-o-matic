<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the admin panel of Park-o-Matic.</h1>
    </div>
    <p>
      <a href="map_edit.html" class="btn btn-primary">Edit map</a>
      <a href="admin_sim.html" class="btn btn-success">Step-wise Simulation</a>
    </p>
    <p>
        <a href="pswd_reset.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        <a href="account_create.php" class="btn btn-info">Create A New Account</a>

    </p>
</body>
</html>
