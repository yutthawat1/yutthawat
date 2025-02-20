<?php
// รวมการเชื่อมต่อกับฐานข้อมูล
include('config.php');

// คำนวณจำนวนหนังสือทั้งหมด
$query_total_books = "SELECT COUNT(*) AS total_books FROM books";
$result_total_books = mysqli_query($conn, $query_total_books);
$row_total_books = mysqli_fetch_assoc($result_total_books);
$total_books = $row_total_books['total_books'];

// คำนวณจำนวนหนังสือที่ถูกยืม
$query_borrowed_books = "SELECT COUNT(*) AS total_borrowed_books 
                         FROM borrow_records 
                         WHERE status = 'borrowed'";
$result_borrowed_books = mysqli_query($conn, $query_borrowed_books);
$row_borrowed_books = mysqli_fetch_assoc($result_borrowed_books);
$total_borrowed_books = $row_borrowed_books['total_borrowed_books'];

// คำนวณจำนวนหนังสือที่ถูกคืน
$query_returned_books = "SELECT COUNT(*) AS total_returned_books 
                         FROM borrow_records 
                         WHERE status = 'returned'";
$result_returned_books = mysqli_query($conn, $query_returned_books);
$row_returned_books = mysqli_fetch_assoc($result_returned_books);
$total_returned_books = $row_returned_books['total_returned_books'];

// คำนวณยอดรวมที่ถูกยืมทั้งหมด
$query_total_borrowed_count = "SELECT SUM(borrow_count) AS total_borrowed_count
                               FROM (
                                   SELECT COUNT(*) AS borrow_count
                                   FROM borrow_records 
                                   GROUP BY book_id
                               ) AS subquery";
$result_total_borrowed_count = mysqli_query($conn, $query_total_borrowed_count);
$row_total_borrowed_count = mysqli_fetch_assoc($result_total_borrowed_count);
$total_borrowed_count = $row_total_borrowed_count['total_borrowed_count'];

// ดึงข้อมูลหนังสือทั้งหมด
$query = "SELECT b.book_id, b.title, br.borrow_date, br.return_date, br.status 
          FROM books b 
          LEFT JOIN borrow_records br ON b.book_id = br.book_id 
          ORDER BY b.title";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถิติการยืมคืนหนังสือ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>สถิติการยืมคืนหนังสือ</h1>
        <nav>
            <a href="index.php">หน้าหลัก</a>
        </nav>
    </header>

    <div class="container">
        <div class="statistics">
            <p>จำนวนหนังสือทั้งหมด: <?php echo $total_books; ?></p>
            <p>จำนวนหนังสือที่ถูกยืม: <?php echo $total_borrowed_books; ?></p>
            <p>จำนวนหนังสือที่ถูกคืน: <?php echo $total_returned_books; ?></p>
            <p>ยอดรวมหนังสือที่เคยถูกยืมทั้งหมด: <?php echo $total_borrowed_count; ?></p> <!-- แสดงยอดรวมการยืมทั้งหมด -->
        </div>

        <table>
            <thead>
                <tr>
                    <th>รหัสหนังสือ</th>
                    <th>ชื่อหนังสือ</th>
                    <th>วันที่ยืม</th>
                    <th>วันที่คืน</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['book_id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['borrow_date'] ? $row['borrow_date'] : 'ยังไม่ยืม'; ?></td>
                        <td><?php echo $row['return_date'] ? $row['return_date'] : 'ยังไม่คืน'; ?></td>
                        <td>
                            <?php
                                if ($row['status'] == 'borrowed') {
                                    echo 'ถูกยืม';
                                } else {
                                    echo 'คืนแล้ว';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>
