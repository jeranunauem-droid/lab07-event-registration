<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'db-server'; 
$db   = 'event_db';
$user = 'app_user';
$secret_path = '/run/secrets/db_user_pass';
if (!file_exists($secret_path)) {
    die("❌ Error: ไม่พบไฟล์รหัสผ่านที่ " . $secret_path);
}
$pass = trim(file_get_contents($secret_path));

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Event Registration</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; max-width: 900px; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; }
        th { background-color: #ddd; }
    </style>
</head>
<body>
    <h2>รายชื่อนักศึกษาที่ลงทะเบียน (LAB07)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            // ลูปเอาข้อมูลมาสร้างเป็นตารางทีละแถว
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>ยังไม่มีข้อมูล (0 results)</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>