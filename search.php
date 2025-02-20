<?php
include('config.php'); // เชื่อมต่อกับฐานข้อมูล

$search_query = '';
$query = "SELECT b.book_id, b.title, b.author, b.publish_year, br.status AS borrow_status, br.borrower_name
          FROM books b
          LEFT JOIN borrow_records br ON b.book_id = br.book_id AND br.status = 'borrowed'
          ORDER BY b.title";

// ตรวจสอบว่ามีการส่งค่าจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search'])) {
    $search_query = trim($_POST['search']);

    // ใช้ Prepared Statement ป้องกัน SQL Injection
    $sql = "SELECT * FROM books WHERE 
                title LIKE ? OR 
                author LIKE ? OR 
                category LIKE ? OR 
                publish_year LIKE ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    $search_param = "%{$search_query}%";
    mysqli_stmt_bind_param($stmt, "ssss", $search_param, $search_param, $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // แสดงหนังสือทั้งหมดถ้าไม่มีการค้นหา
    $sql = "SELECT * FROM books";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาหนังสือ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <header>
        <h1>ค้นหาหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
        </nav>
    </header>

    <div class="container">
        <form method="POST">
            <label for="search">ค้นหาหนังสือ:</label>
            <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?>" required>
            <input type="submit" value="ค้นหา">
        </form>

        <div class="search-result">
            <h2>ผลการค้นหา</h2>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อหนังสือ</th>
                        <th>ผู้เขียน</th>
                        <th>หมวดหมู่</th>
                        <th>ปีที่เผยแพร่</th>
                        <th>สถานะ</th>
                        <th>การกระทำ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['publish_year'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php 
                                        if (isset($row['status']) && $row['status'] == 'borrowed') {
                                            echo "ยืมแล้ว";
                                        } else {
                                            echo "พร้อมให้ยืม";
                                        }
                                    ?></td>

                                <td>
                                    <?php if ($row['status'] != 'borrowed'): ?>
                                        <a href="borrow_book.php?book_id=<?php echo $row['book_id']; ?>">ยืมหนังสือ</a>
                                    <?php else: ?>
                                        <a href="return_book.php?book_id=<?php echo $row['book_id']; ?>">คืนหนังสือ</a>
                                    <?php endif; ?>
                                    <a href="edit_book.php?book_id=<?php echo $row['book_id']; ?>">แก้ไข</a>
                                    <a href="delete_book.php?book_id=<?php echo $row['book_id']; ?>" onclick="return confirm('คุณต้องการลบหนังสือนี้หรือไม่?')">ลบ</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center; color:red;">ไม่พบข้อมูล</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>