<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

session_start();

$mysqli = new mysqli('localhost', 'kim', '0822', 'db1');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_SESSION['realusername'])) {
        header("location: login.php");
        exit();
    }

    $realusername = $_SESSION['realusername'];
    $comment_id = $_GET['id'];

    $sql = "DELETE FROM comments WHERE id = ? AND user_id = (SELECT id FROM users WHERE realusername = ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("is", $comment_id, $realusername);
    $stmt->execute();
    $stmt->close();

    header("location: view_post.php?id=" . $_GET['post_id']);
    exit();
}
?>
