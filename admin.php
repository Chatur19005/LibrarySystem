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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar">
            <!-- Sidebar links -->
            <h5 style="text-align: center;">Library Management System</h5>

            <ul class="nav flex-column">
            <li class="nav-item" style="text-align: center;">
    <a href="admin.php" class="nav-link">
        <img src="university-of-kelaniya-logo.png" alt="Home" style="max-width: 100px; height: auto; animation: rotate 15s linear infinite;">
    </a>
</li>


                <li class="nav-item">
                    <a href="admin.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="bookregister/bookreg.php" class="nav-link">Books Registration</a>
                </li>
                <li class="nav-item">
                    <a href="bookcata/bookcatagory.php" class="nav-link">Category Registration</a>
                </li>
                <li class="nav-item">
                <a href="memberreg/memberreg.php" class="nav-link">Member Registration</a>

                </li>
                <li class="nav-item">
                    <a href="bookb/book_borrow.php" class="nav-link">Borrow Details</a>
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
            <span class="navbar-text">
                Welcome, <?php echo $firstName; ?>!
            </span>
            <a href="profile.php" class="btn btn-primary ms-2">Profile</a>
            <a href="#logoutConfirmationModal" class="btn btn-outline-danger ms-2" data-bs-toggle="modal">Logout</a>
        </div>
    </div>
</nav>
<?php
include 'db_connection.php';

// Query to count the number of members
$query = "SELECT COUNT(*) AS total_members FROM member";
$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    // Fetch the count from the result
    $row = $result->fetch_assoc();
    $totalMembers = $row['total_members'];
} else {
    // Handle the case where the query fails
    $totalMembers = "Error fetching total members";
}
?>

<?php
include 'db_connection.php';

// Query to count the number of books
$query = "SELECT COUNT(*) AS total_books FROM book";
$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    // Fetch the count from the result
    $row = $result->fetch_assoc();
    $totalBooks = $row['total_books'];
} else {
    // Handle the case where the query fails
    $totalBooks = "Error fetching total books";
}
?>
<?php
include 'db_connection.php';

// Query to count the number of categories
$query = "SELECT COUNT(*) AS total_categories FROM bookcategory";
$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    // Fetch the count from the result
    $row = $result->fetch_assoc();
    $totalCategories = $row['total_categories'];
} else {
    // Handle the case where the query fails
    $totalCategories = "Error fetching total categories";
}
?>

    <!-- Start of your main content -->
    <br>
    <br>
    <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
    <div class="w3-container w3-red w3-padding-16">
    <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
    <div class="w3-right">
        <h3><?php echo $totalMembers; ?></h3>
    </div>
    <div class="w3-clear"></div>
    <h4>Total Members</h4>
</div>
    </div>
    <div class="w3-quarter">
    <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-book w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3><?php echo $totalBooks; ?></h3>
        </div>
        <div class="w3-clear"></div>
        <h4>BOOKS</h4>
    </div>
</div>
<div class="w3-quarter">
    <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-share-alt w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3><?php echo $totalCategories; ?></h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Total Categories</h4>
    
</div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>UNLIMITED</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Available Member Regs</h4>
      </div>
    </div>
  </div>
    
    <div class="container">
  <div class="row row-cols-">
  <p style="font-family: Arial, sans-serif; color: #333; font-size: 16px;">This Library Management System encompasses several key functions and features, including user registration and management, book and book category management, library member registration, book borrowing details management, and fine assignment for overdue books. Each feature incorporates specific details such as unique IDs, validation checks, and the ability to create, update, delete, and display records in a structured format. These features collectively aim to streamline library operations, enhance resource tracking, and ensure efficient service delivery to library members.</p>

  </div>
</div>
    <!-- End of your main content -->
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
                <a href="logout.php" class="btn btn-success">Yes</a>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
