<?php
require_once 'db_connection.php';

if(isset($_POST['member_id'])) {
    $memberID = $conn->real_escape_string($_POST['member_id']);

    $sql = "SELECT first_name, last_name FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $memberID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row); // Return member information in JSON format
    } else {
        echo json_encode(["error" => "No member found with ID: $memberID"]);
    }
}
