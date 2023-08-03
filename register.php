<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$mysqli = new mysqli('localhost', 'kim', '0822', 'db1');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $realusername = $mysqli->real_escape_string($_POST['realusername']);
    $realpassword = $mysqli->real_escape_string($_POST['realpassword']);
    $realpassword = password_hash($realpassword, PASSWORD_DEFAULT);

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE realusername=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $realusername);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Username already taken');</script>";
        $stmt->close();
    } else {
        $stmt->close();

        // If username is not taken, register new user
        $sql = "INSERT INTO users (realusername, realpassword) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $realusername, $realpassword);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!');</script>";
            $stmt->close();
            header("location: index.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            width: 80%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #008CBA;
            margin-top: 0;
            padding-top: 10px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            background-color: #008CBA;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Real Username</label>
                <input type="text" name="realusername" required>
            </div>
            <div>
                <label>Real Password</label>
                <input type="password" name="realpassword" required>
            </div>
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
