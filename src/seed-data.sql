SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS `event_registration` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `event_registration`;

DROP TABLE IF EXISTS `students`;
-- 1. สร้างตาราง
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(15) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100),
    `status` ENUM('Submitted', 'Pending', 'In Progress') DEFAULT 'Pending',
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 2. เพิ่มข้อมูล (แก้ไขจุดผิดและเรียงลำดับให้ตรงกับคอลัมน์)
INSERT INTO `students` (`student_id`, `full_name`, `username`, `email`, `status`)
VALUES 
('6601000100', 'นายสมชาย ใจดี', 'somchai-dev', 'somchai.j@mail.com', 'Submitted'),
('6601000200', 'นางสาวสมหญิง รักเรียน', 'somying-it', 'somying.r@mail.com', 'In Progress'),
-- ใส่ข้อมูลจริงของคุณที่บรรทัดนี้ --
('1660701226', 'นางสาวจีรนัน เอี่ยมท่า', 'jeranun_it', 'jeranun.auem@bumail.net', 'Submitted');
