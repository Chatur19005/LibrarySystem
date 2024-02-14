<?php

include 'db_connection.php';
require_once "book_process.php";

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
                    <a href="#" class="nav-link">Borrow Details</a>
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






    <!-- Start of your main content -->
    <div class="mt-4">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?= $_SESSION['message_type']; ?>" role="alert">
            <?= $_SESSION['message']; ?>
        </div>

        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

<div class="container">
    <h1>Book Borrow Form</h1>
    <form action="book_process.php?create=true" method="post">
        <div class="form-group">
            <label for="borrow_id">Borrow ID</label>
            <input type="text" id="borrow_id" name="borrow_id" required>
        </div>
        <div class="form-group">
            <label for="book_id">Enter Book ID</label> <?// book_id get from book table and user should able to search it and input it?>
            <input type="text" id="book_id" name="book_id" required>
        </div>
        <div class="form-group">
            <label for="member_id">Member ID</label><?// member_id get from member table and user should able to search it and input it?>
            <input type="text" id="member_id" name="member_id" required>
        </div>
        <div style="margin-bottom: 20px;">
    <label for="borrow_status" style="display: block; margin-bottom: 5px; font-weight: bold;">Borrow Status:</label>
    <select id="borrow_status" name="borrow_status" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        <option value="borrowed">Borrowed</option>
        <option value="available">Available</option>
    </select><br><br>
</div>

<div class="form-group">
    <label for="borrower_date_modified">Data Modified</label>
    <input type="borrower_date_modified" id="borrower_date_modified" name="borrower_date_modified" readonly>
</div>

<script>
   
    const currentDate = new Date();

   
    const year = currentDate.getFullYear();
    let month = currentDate.getMonth() + 1;
    month = month < 10 ? '0' + month : month;
    let day = currentDate.getDate();
    day = day < 10 ? '0' + day : day;

    
    let hours = currentDate.getHours();
    let minutes = currentDate.getMinutes();
    let seconds = currentDate.getSeconds();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; 


    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

   
    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}${ampm}`;

    document.getElementById("borrower_date_modified").value = formattedDateTime;
</script>
        <div class="form-group">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>


<?php
    $sql = "SELECT borrow_id,book_id,borrow_status, member_id,borrower_date_modified FROM bookborrower";
    $result = $conn->query($sql);    
    ?>

<table>
    <thead>
        <tr>
            <th>Borrow ID</th>
            <th>Book ID</th>
            <th>Borrow Status</th>
            <th>Member ID</th>
            <th>Borrow Date Modified</th>
            <th>Actions</th> 
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['borrow_id'] ?></td>
                    <td><?= $row['book_id'] ?></td>
                    <td><?= $row['borrow_status'] ?></td>
                    <td><?= $row['member_id'] ?></td>
                    <td><?= $row['borrower_date_modified'] ?></td>
                    <td>
                    <a href="borrow_update.php?borrow_id=<?= $row['borrow_id'] ?>" class="updateBtn">Update</a>
                     <a href="book_process.php?delete=true&borrow_id=<?= htmlspecialchars($row['borrow_id']) ?>" class="deleteBtn" onclick="return confirm('Are you sure you want to delete this catagory?');">Delete</a>

                </tr>
            <?php }  ?>
        <?php }  ?>
    </tbody>


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
