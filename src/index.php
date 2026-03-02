<?php
header('Content-Type: text/html; charset=UTF-8');
$host = 'db-server'; 
$db   = 'event_registration';
$user = 'jeranun';
$secret_path = '/run/secrets/db_user_pass';

$db_connected = false;
if (file_exists($secret_path)) {
    $pass = trim(file_get_contents($secret_path));
    $conn = @new mysqli($host, $user, $pass, $db);
    if (!$conn->connect_error) {
        $db_connected = true;
        $conn->set_charset("utf8mb4");
        @$conn->query("SET NAMES 'utf8mb4'");
        mysqli_report(MYSQLI_REPORT_OFF);
        $result = @$conn->query("SELECT * FROM students ORDER BY id ASC");
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Assignment 07: Infrastructure</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; margin: 0; display: flex; flex-direction: column; align-items: center; }
        .header-banner { background-color: #9b59b6; color: white; width: 100%; padding: 40px 0; text-align: center; }
        .container { width: 95%; max-width: 1100px; background: white; margin-top: -30px; padding: 20px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th { color: #888; font-size: 12px; text-transform: uppercase; padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; color: #333; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .submitted { background-color: #d4edda; color: #155724; }
        .in-progress { background-color: #fff3cd; color: #856404; }
        .username-tag { color: #9b59b6; background: #f3e5f5; padding: 3px 8px; border-radius: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header-banner">
        <h1>Assignment 07: Infrastructure</h1>
        <p>Containerized Application with Docker Configs & Secrets</p>
    </div>
    <div class="container">
        <h3>Student Database</h3>
        <p>เรียงลำดับตาม ID (น้อยไปมาก)<br><strong>Secrets Active</strong></p>
        <table>
            <thead>
                <tr>
                    <th># ID</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>USERNAME</th>
                    <th>อีเมล</th>
                    <th>สถานะงาน</th>
                    <th>วันที่และเวลาที่บันทึก</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($db_connected && $result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // เช็คว่าค่า status เป็น 'Submitted' หรือไม่ (ระวังตัวเล็กตัวใหญ่)
                        $status_class = (strtolower($row['status']) == 'submitted') ? 'submitted' : 'in-progress';
                        
                        echo "<tr>";
                        echo "<td>" . sprintf("%02d", $row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td><span class='username-tag'>@" . htmlspecialchars($row['username']) . "</span></td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td><span class='status-badge $status_class'>" . htmlspecialchars($row['status']) . "</span></td>";
                        // ต้องสะกดว่า submitted_at (มีตัว d) ตามใน phpMyAdmin ของคุณ
                        echo "<td>" . htmlspecialchars($row['submitted_at'] ?? '-') . " น.</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>⚠️ ไม่พบข้อมูลในตาราง students</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div style="margin-top:20px; font-size:12px; color:#999;">
            *Database Engine: MySQL 8.0 | Environment: Docker Compose v2 | Developer: เจรัญญ์ (1660701226)
        </div>
    </div>
</body>
</html>
