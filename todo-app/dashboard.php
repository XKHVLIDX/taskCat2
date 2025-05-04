<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My To-Do List</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    
    <form id="todoForm">
        <input type="text" id="newTask" placeholder="New task..." required>
        <button type="submit">Add Task</button>
    </form>

    <h2>Your Tasks:</h2>
    <ul id="taskList"></ul>

    <p><a href="logout.php">Logout</a></p>
    
    <script src="script.js"></script>
</body>
</html>