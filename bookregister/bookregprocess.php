<?php

require_once "db_connection.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    $book_id = $conn->real_escape_string($_POST['book_id']);
    $book_name = $conn->real_escape_string($_POST['book_name']);
    $category_id = $conn->real_escape_string($_POST['category_id']);

    // Validate Category format
    if (!preg_match('/^B\d{3}$/', $book_id)) {
        $_SESSION['message'] = "Invalid Category ID format. Must be 'B' followed by 3 digits. Eg -B001";
        $_SESSION['message_type'] = "danger";
        header("Location: bookreg.php");
        exit();
    }

    // Prepare the SQL statement to prevent SQL injection
    $sql = "INSERT INTO book (book_id, book_Name, category_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters with their types
    $stmt->bind_param("sss", $book_id, $book_name, $category_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Book added successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: bookreg.php");
    exit();
}





if (isset($_GET['delete'])) {
    $book_id = $_GET['book_id'];

    // Use prepared statements to avoid SQL injection
    $sql = "DELETE FROM book WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $book_id);

    try {
        $stmt->execute();

        $_SESSION['message'] = "BOOK deleted successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: bookreg.php");
    exit(); // Make sure to exit to prevent further script execution
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    // Your code for creating a new category
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['update'])) {
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $category_id = $_POST['category_id'];

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE book SET book_Name = ?, Category_id = ? WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $book_name, $category_id, $book_id);

    try {
        $stmt->execute();

        $_SESSION['message'] = "Book updated successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: bookreg.php");
    exit(); // Make sure to exit after redirection
} elseif (isset($_GET['delete'])) {
    // Your code for deleting a category
}

?>






