<?php
// Connect to the database
$host = 'localhost';
$user = 'root';  
$password = '';  
$dbname = 'tofdo_list';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Add a new task
if (isset($_POST['add_task'])) {
    $task = $_POST['task'];
    $created_at = date('Y-m-d H:i:s');  
    $sql = "INSERT INTO todo_list (tasks, created_at) VALUES ('$task', '$created_at')";
    $conn->query($sql);
    header('Location: index.php');
}

// Mark a task as completed
if (isset($_GET['complete_task'])) {
    $id = $_GET['complete_task'];
    $sql = "UPDATE todo_list   SET status = 1 WHERE id = $id";
    $conn->query($sql);
    header('Location: index.php');
}

// Delete a task
if (isset($_GET['delete_task'])) {
    $id = $_GET['delete_task'];
    $sql = "DELETE FROM todo_list WHERE id = $id";
    $conn->query($sql);
    header('Location: index.php');
}

// Fetch tasks from the database
$sql = "SELECT * FROM todo_list";
$tasks = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP To-Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            justify-content: space-between;
        }
        input[type="text"] {
            width: 80%;
            padding: 8px;
        }
        input[type="submit"] {
            padding: 8px 12px;
            background-color: #28a745;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
        }
        li.completed {
            text-decoration: line-through;
            color: #aaa;
        }
        a {
            text-decoration: none;
            color: #dc3545;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>
        <form action="index.php" method="POST">
            <input type="text" name="task" placeholder="Add new task..." required>
            <input type="submit" name="add_task" value="Add">
        </form>
        <ul>
            <?php while($row = $tasks->fetch_assoc()): ?>
            <li class="<?php echo $row['status'] ? 'completed' : '' ?>">
                <?php echo $row['tasks']; ?>
                <div>
                    <?php if(!$row['status']): ?>
                    <a href="index.php?complete_task=<?php echo $row['id']; ?>">Complete</a>
                    <?php endif; ?>
                    <a href="index.php?delete_task=<?php echo $row['id']; ?>">Delete</a>
                </div>
            </li>
            <?php endwhile; ?>



        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
