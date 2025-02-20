<?php
// เชื่อมต่อกับฐานข้อมูล
include('config.php');

// ค้นหาหนังสือทั้งหมดพร้อมสถานะการยืม
$query = "SELECT b.book_id, b.title, b.author, b.publish_year, br.status AS borrow_status, br.borrower_name
          FROM books b
          LEFT JOIN borrow_records br ON b.book_id = br.book_id AND br.status = 'borrowed'
          ORDER BY b.title";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>ระบบยืมหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
            <a href="add_book.php">เพิ่มหนังสือ</a>
            <a href="borrow_book.php">ยืมหนังสือ</a>
            <a href="return_book.php">คืนหนังสือ</a>
            <a href="statistics.php">สถิติ</a>
        </nav>
    </header>
    <form action="search.php" method="GET">
        <button>ค้นหา</button>
    </form>
    <div class="container">
        <h2>หนังสือทั้งหมด</h2>
        <table>
            <thead>
                <tr>
                    <th>รหัสหนังสือ</th>
                    <th>ชื่อหนังสือ</th>
                    <th>ผู้แต่ง</th>
                    <th>ปีที่พิมพ์</th>
                    <th>สถานะ</th>
                    <th>ผู้ยืม</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['book_id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['publish_year']; ?></td>
                        <td>
                            <?php 
                                if ($row['borrow_status'] == 'borrowed') {
                                    echo "ยืมแล้ว";
                                } else {
                                    echo "พร้อมให้ยืม";
                                }
                            ?>  
                        </td>
                        <td>
                            <?php 
                                if ($row['borrow_status'] == 'borrowed') {
                                    echo $row['borrower_name'];
                                } else {
                                    echo "-";
                                }
                            ?>
                        </td>
                        <td>
                            <?php if ($row['borrow_status'] != 'borrowed'): ?>
                                <a href="borrow_book.php?book_id=<?php echo $row['book_id']; ?>">ยืมหนังสือ</a>
                            <?php else: ?>
                                <a href="return_book.php?book_id=<?php echo $row['book_id']; ?>">คืนหนังสือ</a>
                            <?php endif; ?>
                            <a href="edit_book.php?book_id=<?php echo $row['book_id']; ?>">แก้ไข</a>
                            <a href="delete_book.php?book_id=<?php echo $row['book_id']; ?>" onclick="return confirm('คุณต้องการลบหนังสือนี้หรือไม่?')">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>
