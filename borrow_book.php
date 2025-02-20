<?php
include("config.php");

// ดึงข้อมูลหนังสือจากฐานข้อมูล
$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : '';  // กำหนดค่าเริ่มต้นให้กับ $book_id
$query = "SELECT * FROM books WHERE status = '$book_id'";
$result = mysqli_query($conn, $query);

// ตรวจสอบการยืมหนังสือ
if(isset($_POST['submit'])){
    $book_id = $_POST['book_id'];  // รหัสหนังสือที่เลือก
    $borrower_name = $_POST['borrower_name'];  // ชื่อผู้ยืม
    
    // บันทึกการยืมลงใน borrow_records
    $borrow_date = date("Y-m-d");  // วันที่ยืม
    $query = "INSERT INTO borrow_records (book_id, borrower_name, borrow_date, status) 
              VALUES ('$book_id', '$borrower_name', '$borrow_date', 'borrowed')";
    mysqli_query($conn, $query);
    
    // คำสั่ง SQL สำหรับอัปเดตสถานะหนังสือ
    $query_update = "UPDATE books SET status='borrowed' WHERE book_id='$book_id'";
    mysqli_query($conn, $query_update);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืมหนังสือ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>ยืมหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
        </nav>
    </header>

    <div class="container">
        <div class="form-section">
            <h2>กรอกข้อมูลการยืม</h2>
            <form method="post" action="">
                <label for="borrower_name">ชื่อผู้ยืม:</label>
                <input type="text" name="borrower_name" required>

                <label for="book_id">เลือกหนังสือ:</label>
                <select name="book_id" required>
                    <option value="">เลือกหนังสือ</option>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo $row['book_id']; ?>"><?php echo $row['title']; ?></option>
                    <?php } ?>
                </select>

                <input type="submit" name="submit" value="ยืมหนังสือ">
            </form>
        </div>
    </div>

    
</body>
</html>
