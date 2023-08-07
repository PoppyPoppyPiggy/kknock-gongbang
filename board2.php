<!DOCTYPE html>
<html>
<head>
    <title>Board</title>
    <style>
  body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        .create-post-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #008CBA;
            color: #fff;
            border: none;
            border-radius: 3px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="create_post.php" class="create-post-button">Create Post</a>
        <table>
            <tr>
                <th>Post ID</th>
    <th>Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['post_order']; ?></td>
                <td><a href="view_post.php?id=<?php echo $row['id']; ?>"><?php echo $row['title>
                <td><?php echo $row['realusername']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>w
                    <?php if (isset($_SESSION['realusername']) && $_SESSION['realusername'] ===>
                        <a href="board.php?delete_id=<?php echo $row['id']; ?>">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
        <div>
                  <form action="search.php" method="GET">
        <input type="text" name="search" placeholder="검색어를 입력하세요">
        <input type="submit" value="검색">
    </form>
        </div>
   <div>
        <a href="logout.php" class="create-post-button">logout</a>
        </div>
</body>
</html>
