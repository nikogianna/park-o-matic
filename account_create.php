<?php

session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "db_config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Δώστε όνομα χρήστη.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $dbh->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            // mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute([$param_username])) {
                if ($stmt->rowCount() >= 1) {
                    $username_err = "Αυτό το όνομα χρήστη χρησιμοποιείται ήδη από άλλον χρήστη.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Κάτι πήγε στραβά. Ξαναδοκιμάστε αργότερα";
            }
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Δώστε τον κωδικό.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Ο κωδικός πρέπει να έχει μήκος τουλάχιστον 6 χαρακτήρες.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Επιβεβαιώστε τον κωδικό.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Οι δύο κωδικοί δεν είναι ίδιοι.";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if ($stmt = $dbh->prepare($sql)) {            // Bind variables to the prepared statement as parameters
            // mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
           if ($stmt->execute([$param_username, $param_password])) {
                // Redirect to login page
                session_start();

                $_SESSION = array();

                // Destroy the session.
                session_destroy();

                // // Redirect to login page
                header("location: login.php");
            } else {
                echo "Κάτι πήγε στραβά. Ξαναδοκιμάστε αργότερα.";
            }
        }
    }
    $dbh = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Δημιουργία Λογαριασμού</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Δημιουργία Λογαριασμού</h2>
        <p>Συμπληρώστε την παρακάτω φόρμα για να δημιουργήσετε έναν νέο λογαριασμό διαχειριστή.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Όνομα Χρήστη</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Κωδικός</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Επιβεβαίωση κωδικού</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>
