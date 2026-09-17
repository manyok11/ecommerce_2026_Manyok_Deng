<?php
// connect to the database
require "db.php";

// check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get the values from the form
    $title       = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $status      = $_POST["status"];

    // insert the new task into the database
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $status);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // go back to the main page after saving
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a3d2b;
            min-height: 100vh;
        }

        header {
            background-color: #fff;
            padding: 16px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        header h1 {
            color: #1a6b3c;
            font-size: 22px;
            font-weight: 700;
        }

        /* form container card */
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 20px;
            color: #1a3d2b;
            margin-bottom: 24px;
            font-weight: 600;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        /* style all form inputs the same way */
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 18px;
            color: #333;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #1a6b3c;
        }

        textarea { height: 100px; resize: vertical; }

        /* save button */
        .btn-save {
            background-color: #1a6b3c;
            color: white;
            padding: 11px 28px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .btn-save:hover { background-color: #145530; }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: #1a6b3c;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>
<body>

<header>
    <h1>📝 My Tasks</h1>
</header>

<div class="container">
    <h2>Add New Task</h2>

    <form method="POST" action="create.php">

        <label>Title</label>
        <input type="text" name="title" required placeholder="What do you need to do?">

        <label>Description</label>
        <textarea name="description" placeholder="Add more details here..."></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>

        <button type="submit" class="btn-save">Save Task</button>
    </form>

    <a href="index.php" class="btn-back">← Back to Tasks</a>
</div>

</body>
</html>
