<?php
include("config.php");

// ตรวจสอบการบันทึกข้อมูล
if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $publish_year = $_POST['publish_year'];
    
    // คำสั่ง SQL สำหรับเพิ่มข้อมูล
    $query = "INSERT INTO books (title, author, category, publish_year, status) 
              VALUES ('$title', '$author', '$category', '$publish_year', 'available')";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มหนังสือ - ระบบห้องสมุด</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>เพิ่มหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
        </nav>
    </header>

    <div class="container">
        <div class="form-section">
            <form action="" method="post">
                <label for="title">ชื่อหนังสือ:</label>
                <input type="text" name="title" required>
                <label for="author">ผู้เขียน:</label>
                <input type="text" name="author" required>
                <label for="category">หมวดหมู่:</label>
                <input type="text" name="category" required>
                <label for="publish_year">ปีที่พิมพ์:</label>
                <input type="number" name="publish_year" required>
                <input type="submit" name="submit" value="เพิ่มหนังสือ">
            </form>
        </div>
        <br>
    </div>
</body>
</html>
