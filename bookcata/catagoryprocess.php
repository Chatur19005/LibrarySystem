<?php

require_once "db_connection.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    $category_id = $conn->real_escape_string($_POST['category_id']);
    $category_name = $conn->real_escape_string($_POST['category_Name']);
    $date_modified = $conn->real_escape_string($_POST['date_modified']);

    // Validate Category format
    if (!preg_match('/^C\d{3}$/', $category_id)) {
        $_SESSION['message'] = "Invalid Category ID format. Must be 'C' followed by 3 digits. Eg -C001";
        $_SESSION['message_type'] = "danger";
        header("Location: bookcatagory.php");
        exit();
    }

    // Prepare the SQL statement to prevent SQL injection
    $sql = "INSERT INTO bookcategory (category_id, category_Name, date_modified) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters with their types
    $stmt->bind_param("sss", $category_id, $category_name, $date_modified);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Category added successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: bookcatagory.php");
    exit();
}





if (isset($_GET['delete'])) {
    $category_id = $_GET['category_id'];

    // Use prepared statements to avoid SQL injection
    $sql = "DELETE FROM bookcategory WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_id);

    try {
        $stmt->execute();

        $_SESSION['message'] = "Category deleted successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: bookcatagory.php");
    exit(); // Make sure to exit to prevent further script execution
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    // Your code for creating a new category
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['update'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_Name'];
    $date_modified = $_POST['date_modified'];

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE bookcategory SET category_Name = ?, date_modified = ? WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $category_name, $date_modified, $category_id); // Corrected $member_id to $category_id

    try {
        $stmt->execute();

        $_SESSION['message'] = "Category updated successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: bookcatagory.php");
    exit(); // Make sure to exit after redirection
} elseif (isset($_GET['delete'])) {
    // Your code for deleting a category
}

?>






