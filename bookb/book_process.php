<?php

require_once "db_connection.php";
session_start();

// Handle book borrow creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    $borrow_id = $conn->real_escape_string($_POST['borrow_id']);
    $book_id = $conn->real_escape_string($_POST['book_id']);
    $member_id = $conn->real_escape_string($_POST['member_id']);
    $borrow_status = $conn->real_escape_string($_POST['borrow_status']);
    $borrower_date_modified = $conn->real_escape_string($_POST['borrower_date_modified']); // Set the current date and time

    // Validate Borrow ID format
    if (!preg_match('/^BR\d{3}$/', $borrow_id)) {
        $_SESSION['message'] = "Invalid Borrow ID format. Must be 'BR' followed by 3 digits. Eg - BR001";
        $_SESSION['message_type'] = "danger";
        header("Location: /bookb/book_borrow.php");
        exit();
    }

    // Validate Book ID format
    if (!preg_match('/^B\d{3}$/', $book_id)) {
        $_SESSION['message'] = "Invalid Book ID format. Must be 'B' followed by 3 digits. Eg - B001";
        $_SESSION['message_type'] = "danger";
        header("Location: book_borrow.php");
        exit();
    }

    // Validate Member ID format
    if (!preg_match('/^M\d{3}$/', $member_id)) {
        $_SESSION['message'] = "Invalid Member ID format. Must be 'M' followed by 3 digits. Eg - M001";
        $_SESSION['message_type'] = "danger";
        header("Location: /bookb/book_borrow.php");
        exit();
    }

    // Insert into bookborrower table (assuming this is the correct table name)
    $sql = "INSERT INTO bookborrower (borrow_id, book_id, member_id, borrow_status, borrower_date_modified) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sssss", $borrow_id, $book_id, $member_id, $borrow_status, $borrower_date_modified);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Borrow record added successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: book_borrow.php");
    exit();
}

// Handle book borrow deletion
if (isset($_GET['delete'])) {
    $borrow_id = $_GET['borrow_id'];

    $sql = "DELETE FROM bookborrower WHERE borrow_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $borrow_id);

    try {
        $stmt->execute();
        $_SESSION['message'] = "Borrow record deleted successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: book_borrow.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['update'])) {
    // Retrieve form data
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $borrow_status = $_POST['borrow_status'];
    // Assuming 'borrower_date_modified' should be set to the current date/time when updating
    $borrower_date_modified = $_POST['borrower_date_modified'];

    // Validate the format of Book ID and Member ID using Regular Expressions
    if (!preg_match('/^B\d{3}$/', $book_id)) {
        $_SESSION['message'] = "Invalid Book ID format. Must be 'B' followed by 3 digits.";
        $_SESSION['message_type'] = "danger";
        header("Location: book_borrow.php");
        exit();
    }

    if (!preg_match('/^M\d{3}$/', $member_id)) {
        $_SESSION['message'] = "Invalid Member ID format. Must be 'M' followed by 3 digits.";
        $_SESSION['message_type'] = "danger";
        header("Location: /bookb/book_borrow.php");
        exit();
    }

    // Update query
    $sql = "UPDATE bookborrower SET book_id = ?, member_id = ?, borrow_status = ?, borrower_date_modified = ? WHERE borrow_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $_SESSION['message'] = "Error preparing the statement: " . $conn->error;
        $_SESSION['message_type'] = "danger";
        header("Location: book_borrow.php");
        exit();
    }

    $stmt->bind_param("sssss", $book_id, $member_id, $borrow_status, $borrower_date_modified, $borrow_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Borrow details updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating borrow details: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: book_borrow.php");
    exit();
}

?>



