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


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
    min-height: 100vh;
    background-image: linear-gradient(to bottom, #000080, #1E90FF); /* Gradient from navy blue to light blue */
    color: #fff;
    padding: 25px;
}

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #ffc107;
        }
        .content-area {
            padding: 0px;
        }
        body {
            padding-top: 0px; /* Ensure content doesn't get hidden behind the navbar */
        }
        .navbar a, .navbar span {
            color: #fff !important; /* White text for better contrast */
        }
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }

    form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            border-radius: 8px;
            margin-bottom: 20px;
    }
    .alert.success { background-color: #4CAF50; }
        .alert.error { background-color: #f44336; }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            padding: 15px;

        }

        .updateBtn {
            background-color: rgb(28, 93, 7);
            border-radius: 30px;
            color: white;
            text-decoration: none;
            display: inline;
            padding: 10px;
        }
        .deleteBtn {
            background-color: rgb(237, 2, 2);
            border-radius: 30px;
            color: white;
            text-decoration: none;
            display: inline;
            padding: 10px;
        }
        <style>
        .nav-link img {
            max-width: 10px;
            height: auto;
        }
        @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    </style>
    </style>
</head>
<body>

    <!-- Navigation bar -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                <!-- Sidebar links -->
                <h5 style="text-align: center;">Library Management System</h5>

                <ul class="nav flex-column">
                    <li class="nav-item" style="text-align: center;">
                        <a href="admin.php" class="nav-link"><img src="university-of-kelaniya-logo.png" alt="Home" style="max-width: 100px; height: auto; animation: rotate 15s linear infinite;"></a>
                    </li>


                    <li class="nav-item">
                        <a href="../admin.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../bookregister/bookreg.php" class="nav-link">Books Registration</a>
                    </li>
                    <li class="nav-item">
                        <a href="../bookcata/bookcatagory.php" class="nav-link">Category Registration</a>
                    </li>
                    <li class="nav-item">
                    <a href="../memberreg/memberreg.php" class="nav-link">Member Registration</a>

                    </li>
                    <li class="nav-item">
                        <a href="../bookb/book_borrow.php" class="nav-link">Borrow Details</a>
                    </li>
                </ul>
            </div>

        <div class="col-md-9 col-lg-10 content-area">
        <!-- Main content -->


        <nav class="navbar navbar-expand-lg navbar-light bg-black">
            <div class="container-fluid">
                <!-- Place for brand/logo or additional links -->
        
                <!-- This div will align its content to the right -->
                <div class="ms-auto">
                    <a href="profile.php" class="btn btn-primary ms-2">Profile</a>
                    <a href="#logoutConfirmationModal" class="btn btn-outline-danger ms-2" data-bs-toggle="modal">Logout</a>
                </div>
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
