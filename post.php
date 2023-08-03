<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

session_start();

// 로그인이 되어있지 않으면 로그인 페이지로 리다이렉트
if(!isset($_SESSION['realusername'])) {
    header("location: login.php");
    exit;
}

$mysqli = new mysqli('localhost', 'kim', '0822', 'db1');

$id = $mysqli->real_escape_string($_GET['id']);
$sql = "SELECT * FROM posts WHERE id=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// 삭제 버튼이 클릭되었는지 확인
if(isset($_POST['delete']) && $_SESSION['realusername'] === $post['realusername']) {
    $sql = "DELETE FROM posts WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("location: board.php");
    exit;
}

$title = htmlspecialchars($post['title']);
$content = nl2br(htmlspecialchars($post['content'])); // newlines to <br>

// 게시글에 첨부된 파일이 있다면 보여줌
if(!empty($post['filename'])) {
    echo "<p><a href='uploads/{$post['filename']}'>View attachment</a></p>";
}

echo "<h1>$title</h1>";
echo "<p>$content</p>";

// 현재 로그인한 사용자가 이 게시물의 작성자인 경우에만 삭제 버튼을 보여줍니다.
if($_SESSION['realusername'] === $post['realusername']) {
    echo "<form method='post'><input type='submit' name='delete' value='Delete this post'></form>";
}
?>
