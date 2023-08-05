<?php
// MySQL 서버에 연결
$servername = "localhost";  // MySQL 서버 주소
$username = "kim";         // MySQL 사용자 이름
$password = "0822";        // MySQL 비밀번호
$dbname = "db1";           // 데이터베이스 이름

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 오류 검사
if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}

// 검색어 가져오기
$search_query = $_GET['search']; // HTML form에서 검색어를 전달받을 때는 $_GET을 사용합니다.

// SQL 쿼리 작성
$sql = "SELECT * FROM posts WHERE title LIKE '%$search_query%'";

// 쿼리 실행 및 결과 처리
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>검색 결과</title>
</head>
<body>
    <h1>검색 결과</h1>
    <p>검색어: <?php echo $search_query; ?></p>

    <?php
    if ($result->num_rows > 0) {
        // 검색 결과가 있을 경우
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Title: " . $row["title"] . "<br>";
        }
    } else {
        // 검색 결과가 없을 경우
        echo "검색 결과가 없습니다.";
    }
    ?>
 <p><a href="board.php">돌아가기</a></p>
</body>
</html>

<?php
// 연결 종료
$conn->close();
?>
