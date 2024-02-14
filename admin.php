<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch the user's first name from the database
$query = "SELECT first_name FROM user WHERE username = '$username'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['first_name'];
} else {
    // Handle the case where the username is not found
    $firstName = "Unknown";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand">Library Management System</span>
            <!-- Button group for navigation links -->
            <div class="btn-group" role="group" aria-label="Navigation Links">
                <a href="admin.php" class="btn btn-light">Home</a>
                <a href="#" class="btn btn-light">Books Registration</a>
                <a href="#" class="btn btn-light">Category Registration</a>
                <a href="#" class="btn btn-light">Member Registration</a>
                <a href="#" class="btn btn-light">Borrow Details</a>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text">
                            Welcome, <?php echo $firstName; ?>!  
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
        </div>
    </nav>

    
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


    <!-- body add later -->

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

