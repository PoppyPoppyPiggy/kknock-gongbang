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
