<?php
include('config.php');

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // ค้นหาหนังสือจาก book_id
    $sql = "SELECT * FROM books WHERE book_id = '$book_id'";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_assoc($result);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // รับค่าที่ถูกส่งมาในฟอร์ม
        $title = $_POST['title'];
        $author = $_POST['author'];
        $category = $_POST['category'];
        $publish_year = $_POST['publish_year'];
        $status = $_POST['status'];

        // อัปเดตข้อมูลหนังสือ
        $update_sql = "UPDATE books SET title='$title', author='$author', category='$category', publish_year='$publish_year', status='$status' WHERE book_id = '$book_id'";

        if (mysqli_query($conn, $update_sql)) {
            echo "ข้อมูลหนังสืออัปเดตสำเร็จ!";
        } else {
            echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
        }
    }
} else {
    echo "ไม่พบข้อมูลหนังสือ!";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขหนังสือ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>แก้ไขข้อมูลหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
        </nav>
    </header>
    <div class="container">
        <form method="POST">
            <label for="title">ชื่อหนังสือ:</label>
            <input type="text" name="title" value="<?php echo $book['title']; ?>" required>

            <label for="author">ผู้เขียน:</label>
            <input type="text" name="author" value="<?php echo $book['author']; ?>" required>

            <label for="category">หมวดหมู่:</label>
            <input type="text" name="category" value="<?php echo $book['category']; ?>" required>

            <label for="publish_year">ปีที่เผยแพร่:</label>
            <input type="number" name="publish_year" value="<?php echo $book['publish_year']; ?>" required>

            <label for="status">สถานะ:</label>
            <select name="status" required>
                <option value="available" <?php echo $book['status'] == 'available' ? 'selected' : ''; ?>>พร้อมให้ยืม</option>
                <option value="borrowed" <?php echo $book['status'] == 'borrowed' ? 'selected' : ''; ?>>ถูกยืมแล้ว</option>
            </select>

            <input type="submit" value="อัปเดตข้อมูลหนังสือ">
        </form>
    </div>
</body>
</html>
