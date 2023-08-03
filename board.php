<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

session_start();

$mysqli = new mysqli('localhost', 'kim', '0822', 'db1');

// 게시글 삭제 처리
if (isset($_GET['delete_id'])) {
    if (!isset($_SESSION['realusername'])) {
        header("Location: login.php");
        exit();
    }

    $delete_post_id = $_GET['delete_id'];
    $realusername = $_SESSION['realusername'];

    // 게시글 삭제 시 해당 글을 작성한 사용자의 아이디와 현재 로그인한 사용자의 아이디를 비교하여 일치할 때만 삭제 처리
    $delete_sql = "UPDATE posts SET deleted = 1 WHERE id = ? AND user_id = (SELECT id FROM users WHERE realusername = ?)";
    $stmt_delete = $mysqli->prepare($delete_sql);
    $stmt_delete->bind_param("is", $delete_post_id, $realusername);
    $stmt_delete->execute();
    $stmt_delete->close();
}

// 삭제되지 않은 게시글 순서를 구하기 위해 서브쿼리를 사용하여 순서를 계산
$sql = "
    SELECT 
        posts.id,
        posts.title,
        users.realusername,
        posts.created_at,
        (SELECT COUNT(*) FROM posts p WHERE p.created_at >= posts.created_at AND p.deleted = 0) as post_order
    FROM 
        posts 
    JOIN 
        users ON posts.user_id=users.id 
    WHERE 
        posts.deleted = 0 
    ORDER BY 
        posts.created_at DESC
";
$result = $mysqli->query($sql);
?>

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
                <td><a href="view_post.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
                <td><?php echo $row['realusername']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <?php if (isset($_SESSION['realusername']) && $_SESSION['realusername'] === $row['realusername']): ?>
                        <a href="board.php?delete_id=<?php echo $row['id']; ?>">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$mysqli->close();
?>

