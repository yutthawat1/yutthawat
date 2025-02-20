<?php
include('config.php');

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    $sql = "DELETE FROM books WHERE book_id = '$book_id'";

    if (mysqli_query($conn, $sql)) {
        // ลบหนังสือสำเร็จ และเปลี่ยนเส้นทางไปยังหน้า index.php
        header("Location: index.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>
