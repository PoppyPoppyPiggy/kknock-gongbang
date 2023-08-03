<?php
error_reporting( E_ALL );
  ini_set( "display_errors", 1 );
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to My Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #008CBA;
            margin-top: 0;
            padding-top: 10px;
        }
        p {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #008CBA;
        }
        a:hover {
            text-decoration: underline;
        }
        .welcome-msg {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .logout-link {
            display: inline-block;
            margin-top: 10px;
            background-color: #008CBA;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to My Website</h1>
        <?php if(isset($_SESSION['username'])): ?>
            <div class="welcome-msg">
                <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
                <p><a href="logout.php" class="logout-link">Logout</a></p>
            </div>
        <?php else: ?>
            <p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
