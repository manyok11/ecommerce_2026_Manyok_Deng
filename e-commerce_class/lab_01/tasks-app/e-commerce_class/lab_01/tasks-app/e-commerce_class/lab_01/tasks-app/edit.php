<?php
// connect to the database
require "db.php";

// get the task id from the URL
$id = intval($_GET["id"] ?? 0);

// check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get updated values from the form
    $title       = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $status      = $_POST["status"];
    $post_id     = intval($_POST["id"]);

    // update the task in the database
    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $status, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // go back to the main page after updating
    header("Location: index.php");
    exit;
}

// fetch the existing task data to fill the form
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
$stmt->close();

// stop if task was not found
if (!$task) { die("Task not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>

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

        /* update button */
        .btn-update {
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

        .btn-update:hover { background-color: #145530; }

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
    <h2>Edit Task</h2>

    <form method="POST" action="edit.php">
        <!-- hidden field to pass the task id -->
        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">

        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>

        <label>Description</label>
        <textarea name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending"     <?php echo $task['status']==='pending'     ? 'selected' : ''; ?>>Pending</option>
            <option value="in_progress" <?php echo $task['status']==='in_progress' ? 'selected' : ''; ?>>In Progress</option>
            <option value="done"        <?php echo $task['status']==='done'        ? 'selected' : ''; ?>>Done</option>
        </select>

        <button type="submit" class="btn-update">Update Task</button>
    </form>

    <a href="index.php" class="btn-back">← Back to Tasks</a>
</div>

</body>
</html>
