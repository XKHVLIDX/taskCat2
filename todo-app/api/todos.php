<?php
require '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action'])) {
        switch($data['action']) {
            case 'get':
                $sql = "SELECT * FROM todos WHERE user_id = " . $_SESSION['user_id'];
                $result = $conn->query($sql);
                $tasks = [];
                while($row = $result->fetch_assoc()) {
                    $tasks[] = $row;
                }
                echo json_encode($tasks);
                break;

            case 'add':
                $title = $conn->real_escape_string($data['title']);
                $sql = "INSERT INTO todos (user_id, title) 
                       VALUES (" . $_SESSION['user_id'] . ", '$title')";
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(["id" => $conn->insert_id, "title" => $title, "completed" => 0]);
                }
                break;

            case 'update':
                $id = intval($data['id']);
                $completed = intval($data['completed']);
                $sql = "UPDATE todos SET completed = $completed 
                       WHERE id = $id AND user_id = " . $_SESSION['user_id'];
                $conn->query($sql);
                echo json_encode(["success" => true]);
                break;

            case 'delete':
                $id = intval($data['id']);
                $sql = "DELETE FROM todos WHERE id = $id AND user_id = " . $_SESSION['user_id'];
                $conn->query($sql);
                echo json_encode(["success" => true]);
                break;
        }
    }
}
$conn->close();
?>