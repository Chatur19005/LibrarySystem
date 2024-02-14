<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch user data from the database based on the username
$query = "SELECT * FROM user WHERE username = '$username'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    // Handle the case where the username is not found
    $userData = array(); // Set an empty array for user data
}

// Check if the form is submitted for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the updated user data
    $updatedFirstName = mysqli_real_escape_string($conn, $_POST['updatedFirstName']);
    $updatedLastName = mysqli_real_escape_string($conn, $_POST['updatedLastName']);
    $updatedEmail = mysqli_real_escape_string($conn, $_POST['updatedEmail']);
    $updatedUsername = mysqli_real_escape_string($conn, $_POST['updatedUsername']);
    $updatedPassword = mysqli_real_escape_string($conn, $_POST['updatedPassword']);

    // Hash the new password before updating (if provided)
    if (!empty($updatedPassword)) {
        $hashedPassword = password_hash($updatedPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE user SET password = '$hashedPassword' WHERE username = '$username'";
        $conn->query($updateQuery);

        // Display logout confirmation modal
        if (!empty($updatedUsername)) {
            $updateQuery = "UPDATE user SET username = '$updatedUsername' WHERE username = '$username'";
            $conn->query($updateQuery);
    
            header("Location: logout.php");
            exit();
        }
        
    }

    // Hash the new password before updating (if provided)
    if (!empty($updatedUsername)) {
        $updateQuery = "UPDATE user SET username = '$updatedUsername' WHERE username = '$username'";
        $conn->query($updateQuery);

        header("Location: logout.php");
        exit();
    }

    // Update user data in the database
    $updateQuery = "UPDATE user SET first_name = '$updatedFirstName', last_name = '$updatedLastName', email = '$updatedEmail' WHERE username = '$username'";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult) {
        // Display logout confirmation modal
        header("Location: profile.php");
        
    } else {
        // Redirect to the profile page with an error message
        header("Location: profile.php?error=1");
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand">Library Management System</span>

            <div class="btn-group" role="group" aria-label="Navigation Links">
                <a href="admin.php" class="btn btn-light">Home</a>
                <a href="books_registration.php" class="btn btn-light">Books Registration</a>
                <a href="category_registration.php" class="btn btn-light">Category Registration</a>
                <a href="member_registration.php" class="btn btn-light">Member Registration</a>
                <a href="borrow_details.php" class="btn btn-light">Borrow Details</a>
            </div>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Welcome, <?php echo $userData['first_name']; ?>!
                    </span>
                </li>
                <li class="nav-item">
                    &nbsp; <!-- Add an empty list item for spacing -->
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    &nbsp; <!-- Add an empty list item for spacing -->
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-danger" data-bs-toggle="modal" href="#logoutConfirmationModal">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Profile information -->
    <div class="container mt-5 pt-3">
        <h3>User Profile</h3>
        <!-- Display user information -->
        <div class="alert alert-warning">
            <strong>NOTE!</strong> If you change the Username or Password, The page will redirect to the login page .
        </div>
        <form action="profile.php" method="post" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="updatedFirstName" class="form-label">First Name</label>
                <input type="text" class="form-control" name="updatedFirstName" id="updatedFirstName" value="<?php echo $userData['first_name']; ?>">
            </div>
            <div class="mb-3">
                <label for="updatedLastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="updatedLastName" id="updatedLastName" value="<?php echo $userData['last_name']; ?>">
            </div>
            <div class="mb-3">
                <label for="updatedEmail" class="form-label">Email Address</label>
                <input type="email" class="form-control" name="updatedEmail" id="updatedEmail" value="<?php echo $userData['email']; ?>">
                <small id="emailError" class="text-danger"></small>
            </div>
            <div class="mb-3">
                <label for="updatedUsername" class="form-label">New Username</label>
                <input type="text" class="form-control" name="updatedUsername" id="updatedUsername">
            </div>
            <div class="mb-3">
                <label for="updatedPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" name="updatedPassword" id="updatedPassword">
                <small id="passwordError" class="text-danger"></small>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutConfirmationModalLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <a class="btn btn-success" href="logout.php">Yes</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Form vaidate -->
    <script>
        function validateForm() {
            var password = document.getElementById("updatedPassword").value;
            var email = document.getElementById("updatedEmail").value;


            // Check password leagth
            if (password) {
                    if (password.length < 8) {
                    document.getElementById("passwordError").innerText = "Password must be at least 8 characters";
                    return false;
                } else {
                    document.getElementById("passwordError").innerText = "";
                }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
