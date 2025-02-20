<?php
// รวมการเชื่อมต่อกับฐานข้อมูล
include('config.php');

// ตรวจสอบว่าเราได้รับข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['return'])) {
    $book_id = $_POST['book_id']; // รหัสหนังสือที่คืน
    $return_date = date('Y-m-d'); // วันที่คืนหนังสือ

    // เริ่มทำการอัพเดตสถานะการคืนหนังสือในตาราง borrow_records
    $query = "UPDATE borrow_records 
              SET return_date = ?, status = 'returned' 
              WHERE book_id = ? AND status = 'borrowed'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $return_date, $book_id);
    $result = mysqli_stmt_execute($stmt);

    // ตรวจสอบผลการอัพเดตในตาราง borrow_records
    if ($result) {
        // อัพเดตสถานะหนังสือในตาราง books ให้เป็น 'available'
        $update_book_status = "UPDATE books 
                               SET status = 'available' 
                               WHERE book_id = ?";
        $stmt_update = mysqli_prepare($conn, $update_book_status);
        mysqli_stmt_bind_param($stmt_update, 'i', $book_id);
        $result_update = mysqli_stmt_execute($stmt_update);

        if ($result_update) {
            echo "<p>คืนหนังสือสำเร็จ</p>";
        } else {
            echo "<p>เกิดข้อผิดพลาดในการอัพเดตสถานะหนังสือ</p>";
        }
    } else {
        echo "<p>เกิดข้อผิดพลาดในการคืนหนังสือ</p>";
    }
}

// ดึงข้อมูลหนังสือที่ยังไม่ได้คืน (status = 'borrowed')
$query_books = "SELECT b.book_id, b.title, br.borrower_name 
                FROM books b
                INNER JOIN borrow_records br ON b.book_id = br.book_id
                WHERE br.status = 'borrowed'";
$result_books = mysqli_query($conn, $query_books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คืนหนังสือ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>คืนหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
        </nav>
    </header>

    <div class="container">
        <form method="POST" action="">
            <label for="book_id">เลือกหนังสือที่ต้องการคืน:</label>
            <select name="book_id" id="book_id" required>
                <option value="">เลือกหนังสือ</option>
                <?php while ($row = mysqli_fetch_assoc($result_books)): ?>
                    <option value="<?php echo $row['book_id']; ?>">
                        <?php echo $row['title'] . " (ยืมโดย: " . $row['borrower_name'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="submit" name="return" value="คืนหนังสือ">
        </form>
        <br>
    </div>

    
</body>
</html>
