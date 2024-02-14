<?php

require_once "db_connection.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    $member_id = $conn->real_escape_string($_POST['member_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $email = $conn->real_escape_string($_POST['email']);

    // Validate Member ID format
    if (!preg_match('/^M\d{3}$/', $member_id)) {
        $_SESSION['message'] = "Invalid Member ID format. Must be 'M' Eg -M001";
        $_SESSION['message_type'] = "danger";
        header("Location: /memberreg/memberreg.php");
        exit();
    }

    // Prepare the SQL statement to prevent SQL injection
    $sql = "INSERT INTO member (member_id, first_name, last_name, birthday, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sssss", $member_id, $first_name, $last_name, $birthday, $email);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: /memberreg/memberreg.php");
    exit();
}




if (isset($_GET['delete'])) {
    $member_id = $_GET['member_id'];

    // Use prepared statements to avoid SQL injection
    $sql = "DELETE FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $member_id);

    try {
        $stmt->execute();

        $_SESSION['message'] = "User deleted successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: /memberreg/memberreg.php");
    exit(); // Make sure to exit to prevent further script execution
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['update'])) {
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name']; // Corrected from $POST to $_POST
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE member SET first_name = ?, last_name = ?, birthday = ?, email = ? WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $birthday, $email, $member_id);

    try {
        $stmt->execute();

        $_SESSION['message'] = "User updated successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: /memberreg/memberreg.php");
}

