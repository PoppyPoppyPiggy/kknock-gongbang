<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

session_start();

$mysqli = new mysqli('localhost', 'kim', '0822', 'db1');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!isset($_SESSION['realusername'])) {
        header("location: login.php");
        exit();
    }

    $title = $mysqli->real_escape_string($_POST['title']);
    $content = $mysqli->real_escape_string($_POST['content']);
    $realusername = $_SESSION['realusername'];

    $sql = "INSERT INTO posts (user_id, title, content) VALUES ((SELECT id FROM users WHERE realusername = ?), ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $realusername, $title, $content);
    $stmt->execute();
    $post_id = $stmt->insert_id;
    $stmt->close();

    if (!empty($_FILES['file']['name'])) {
        $filename = basename($_FILES['file']['name']);
        $uploadDir = '/var/www/html/uploads/';
        $uploadFilePath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)) {
            $upload_sql = "INSERT INTO uploads (user_id, post_id, filename) VALUES ((SELECT id FROM users WHERE realusername = ?), ?, ?)";
            $upload_stmt = $mysqli->prepare($upload_sql);
            $upload_stmt->bind_param("sis", $realusername, $post_id, $filename);
            $upload_stmt->execute();
            $upload_stmt->close();
        }
    }

    header("location: board.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            resize: vertical;
        }
        input[type="file"] {
            display: none;
        }
        .file-label {
            display: inline-block;
            padding: 10px;
            background-color: #008CBA;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .file-name {
            margin-left: 10px;
            color: #555;
        }
        .submit-button {
            background-color: #008CBA;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Post</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label>Attach a file</label>
                <label class="file-label">
                    Choose File<input type="file" name="file">
                </label>
                <span class="file-name" id="file-name"></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit" class="submit-button">
            </div>
        </form>
    </div>

    <script>
        // Show selected file name
        const fileInput = document.querySelector('input[type="file"]');
        const fileNameSpan = document.getElementById('file-name');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileNameSpan.textContent = this.files[0].name;
            } else {
                fileNameSpan.textContent = '';
            }
        });
    </script>
</body>
</html>
