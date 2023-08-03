<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$mysqli = new mysqli('localhost', 'kim', '0822', 'db1');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['realusername'])) {
        header("location: login.php");
        exit();
    }

    $content = $mysqli->real_escape_string($_POST['content']);
    $realusername = $_SESSION['realusername'];
    $post_id = $_GET['id'];

    $sql = "INSERT INTO comments (user_id, post_id, content) VALUES ((SELECT id FROM users WHERE realusername = ?), ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sis", $realusername, $post_id, $content);
    $stmt->execute();
    $stmt->close();
}

$post_id = $_GET['id'];
$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.realusername, uploads.filename FROM posts JOIN users ON posts.user_id = users.id LEFT JOIN uploads ON posts.id = uploads.post_id WHERE posts.id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

$comments_sql = "SELECT comments.id, comments.content, comments.created_at, users.realusername FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at";
$comments_stmt = $mysqli->prepare($comments_sql);
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comments = $comments_result->fetch_all(MYSQLI_ASSOC);
$comments_stmt->close();

// 사용자 이름이 존재하지 않을 경우 처리
if ($post['realusername'] === null) {
    $post['realusername'] = 'Unknown User';
}

foreach ($comments as &$comment) {
    if ($comment['realusername'] === null) {
        $comment['realusername'] = 'Unknown User';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>View Post</title>
</head>
<body>
    <div class="container">
        <div class="mt-5">
            <h1><?php echo $post['title']; ?></h1>
            <p class="text-muted">Posted by <?php echo $post['realusername']; ?> at <?php echo $post['created_at']; ?></p>
            <p><?php echo $post['content']; ?></p>
            <?php if ($post['filename']): ?>
                <p><a href="/uploads/<?php echo $post['filename']; ?>">Download attached file</a></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['realusername']) && $_SESSION['realusername'] == $post['realusername']): ?>
                <p><a href="/delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger">Delete this post</a></p>
            <?php endif; ?>
        </div>

        <div class="mt-5">
            <h2>Comments</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <p><?php echo $comment['content']; ?></p>
                        <p class="text-muted">Posted by <?php echo $comment['realusername']; ?> at <?php echo $comment['created_at']; ?></p>
                        <?php if (isset($_SESSION['realusername']) && $_SESSION['realusername'] == $comment['realusername']): ?>
                            <p><a href="/delete_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-danger">Delete this comment</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (isset($_SESSION['realusername'])): ?>
            <div class="mt-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $post_id); ?>" method="POST">
                    <div class="form-group">
                        <label>Write a comment</label>
                        <textarea name="content" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
