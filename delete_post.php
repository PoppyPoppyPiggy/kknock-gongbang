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
    $post_id = $_GET['id'];

    // First, delete the comments related to the post
    $sql_comments = "DELETE FROM comments WHERE post_id = ?";
    $stmt_comments = $mysqli->prepare($sql_comments);
    $stmt_comments->bind_param("i", $post_id);
    $stmt_comments->execute();
    $stmt_comments->close();

    // Then, delete the post
    $sql_post = "DELETE FROM posts WHERE id = ? AND user_id = (SELECT id FROM users WHERE realusername = ?)";
    $stmt_post = $mysqli->prepare($sql_post);
    $stmt_post->bind_param("is", $post_id, $realusername);
    $stmt_post->execute();
    $stmt_post->close();

    header("location: board.php");
    exit();
}
?>
