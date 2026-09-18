<?php
// connect to the database
require "db.php";

// get all tasks from the database, newest first
$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* reset default browser styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a3d2b;
            color: #333;
            min-height: 100vh;
        }

        /* top navigation bar */
        header {
            background-color: #fff;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        header h1 {
            color: #1a6b3c;
            font-size: 22px;
            font-weight: 700;
        }

        /* add task button */
        header a {
            background-color: #1a6b3c;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        header a:hover {
            background-color: #145530;
        }

        /* main content area */
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* shown when there are no tasks */
        .empty {
            text-align: center;
            color: #ccc;
            margin-top: 80px;
            font-size: 16px;
        }

        /* each task card */
        .task-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .task-info h3 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1a3d2b;
        }

        .task-info p {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }

        /* status badge colors */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pending    { background: #fef3c7; color: #92400e; }
        .in_progress { background: #dbeafe; color: #1e40af; }
        .done       { background: #d1fae5; color: #065f46; }

        /* edit and delete buttons */
        .task-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-edit, .btn-delete {
            padding: 7px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        .btn-edit   { background: #d1fae5; color: #1a6b3c; }
        .btn-edit:hover { background: #a7f3d0; }

        .btn-delete { background: #fee2e2; color: #dc2626; }
        .btn-delete:hover { background: #fecaca; }
    </style>
</head>
<body>

<header>
    <h1>📝 My Tasks</h1>
    <a href="create.php">+ Add Task</a>
</header>

<div class="container">

    <?php if ($result->num_rows === 0): ?>
        <!-- no tasks yet message -->
        <p class="empty">No tasks yet. Click "+ Add Task" to get started!</p>

    <?php else: ?>
        <!-- loop through all tasks and show each one -->
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="task-card">
            <div class="task-info">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <!-- show status with color badge -->
                <span class="badge <?php echo $row['status']; ?>">
                    <?php echo str_replace('_', ' ', $row['status']); ?>
                </span>
            </div>
            <div class="task-actions">
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-delete"
                   onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>

</div>

</body>
</html>
<?php $conn->close(); ?>
