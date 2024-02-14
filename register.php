<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["userId"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encrypt the password
    $email = $_POST["email"];


    $checkQuery = "SELECT * FROM user WHERE email = '$email' OR username = '$username' OR user_id = '$userId'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var registrationModal = new bootstrap.Modal(document.getElementById('alreadyModal'));
                        registrationModal.show();
                    });
                  </script>";
    } else {
        $sql = "INSERT INTO user (user_id, first_name, last_name, username, password, email) VALUES ('$userId', '$firstName', '$lastName', '$username', '$password', '$email')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var registrationModal = new bootstrap.Modal(document.getElementById('registrationModal'));
                        registrationModal.show();
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var registrationModal = new bootstrap.Modal(document.getElementById('db_error'));
                        registrationModal.show();
                    });
                  </script>";
        }
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- Register form -->
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">User Registration</h3>
                    </div>
                    <div class="card-body">
                        <form action="register.php" method="post" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label for="userId" class="form-label">User ID</label>
                                <input type="text" class="form-control" name="userId" id="userId" placeholder="Enter User ID" required>
                                <small id="userIdError" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter First Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter Last Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                                <small id="passwordError" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
                                <small id="passwordError" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email Address" required>
                                <small id="emailError" class="text-danger"></small>
                            </div>
                            <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Registration Successful Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrationModalLabel">Registration Successful</h5>
                </div>

                <div class="modal-footer">
                    <a class="btn btn-success" href="index.php">Back to login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Unsuccessful Modal -->
    <div class="modal fade" id="alreadyModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrationModalLabel">Registration Unsuccessful</h5>
                </div>
                <div class="modal-body">
                    <p>User with the same email or username already exists!</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="register.php">Back to Register</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Can't connect the database Modal -->
    <div class="modal fade" id="db_error" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrationModalLabel">Registration Unsuccessful</h5>
                </div>
                <div class="modal-body">
                    <p>Can't connect the database</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="register.php">Back to Register</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form validation -->
    <script>
        function validateForm() {
            var userId = document.getElementById("userId").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var email = document.getElementById("email").value;

            // Check User ID starts with "U"
            if (userId.charAt(0) !== 'U') {
                document.getElementById("userIdError").innerText = "User ID should start with 'U'";
                return false;
            } else {
                document.getElementById("userIdError").innerText = "";
            }

            // Check password leanth
            if (password.length < 8) {
                document.getElementById("passwordError").innerText = "Password must be at least 8 characters";
                return false;
            } else {
                document.getElementById("passwordError").innerText = "";
            }

            // Check both password are match
            if (password !== confirmPassword) {
                document.getElementById("passwordError").innerText = "Passwords do not match";
                return false;
            } else {
                document.getElementById("passwordError").innerText = "";
            }

            // Email format validation using a simple regex
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById("emailError").innerText = "Invalid email format";
                return false;
            } else {
                document.getElementById("emailError").innerText = "";
            }

            return true;
        }
    </script>

</body>
</html>


