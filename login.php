<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, redirect to admin page
            session_start();
            $_SESSION['username'] = $username;
            header("Location: admin.php");
        } else {
            // Password is incorrect modal
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var registrationModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                        registrationModal.show();
                    });
                  </script>";
        }
    } else {
        // Username not found modal
        echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var registrationModal = new bootstrap.Modal(document.getElementById('usernameModal'));
                        registrationModal.show();
                    });
                  </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Password is incorrect modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrationModalLabel">Password is incorrect</h5>
                </div>
                <div class="modal-body">
                    <p>Please check your password and try again!</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="index.php">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Username not found modal -->
    <div class="modal fade" id="usernameModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrationModalLabel">Username not found</h5>
                </div>
                <div class="modal-body">
                    <p>Please check your User Name and try again!</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="index.php">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


