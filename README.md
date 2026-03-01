# 📝 LAB07 and Assignment 07: Full-Stack Web Application with Docker Compose

**วัตถุประสงค์:** เพื่อให้นักศึกษาสามารถออกแบบและจัดการระบบ Web Application โดยใช้ฟีเจอร์ขั้นสูงของ Docker Compose และการจัดการข้อมูลระบุตัวตนผู้ส่งงานผ่านระบบฐานข้อมูล

---

## 🏗️ โจทย์: ระบบลงทะเบียนกิจกรรม (Event Registration System)

ให้นักศึกษาเขียนไฟล์ `compose.yaml` เพื่อตั้งค่าระบบที่มีการทำงานร่วมกันของ 4 บริการ (Services) ดังนี้:

### 📂 โครงสร้างโฟลเดอร์ (File Tree Structure)
```txt
assignment-07/
├── compose.yaml          # ไฟล์หลักสำหรับควบคุม Services ทั้งหมด
├── .env                  # ไฟล์เก็บตัวแปร (Username, DB Name)
├── nginx/                # โฟลเดอร์เก็บไฟล์ตั้งค่า nginx
|   └── my-nginx.conf     # ไฟล์ตั้งค่า Nginx (ใช้ทำ Docker Configs)
├── secret                # โฟลเดอร์เก็บไฟล์รหัสผ่าน
|   └── .db_root_pass.txt # ไฟล์ลับเก็บรหัสผ่าน Root MySQL (Docker Secrets)
|   ├── .db_pass_pass.txt # ไฟล์ลับเก็บรหัสผ่าน User MySQL (Docker Secrets)
├── seed-data.sql         # ไฟล์ SQL สำหรับ Import ข้อมูลนักศึกษาลงใน phpMyAdmin
├── app/                  # โฟลเดอร์สำหรับทำ Bind Mount กับ Backend
│   └── index.php         # ไฟล์ทดสอบการทำงานของ PHP
└── README.md             # คู่มืออธิบายการติดตั้งและภาพผลลัพธ์
```

### 0. ข้อกำหนด
*   ให้เติมข้อความแทน ??? และตั้งค่าให้ถูกต้อง
*   ใชำคำคสั่ง   docker compose ต่างๆ 

### 1. รายละเอียดบริการ (Services)
*   **db-server:** ใช้ Image `mysql:8.0` (ฐานข้อมูลหลัก)
*   **backend-php:** ใช้ Image `php-fpm:latest` (ส่วนประมวลผล PHP)
*   **web-proxy:** ใช้ Image `nginx:latest` (รับงานหน้าบ้าน)
*   **db-management:** ใช้ Image `phpmyadmin:latest` (จัดการฐานข้อมูล)

> **⚠️ เงื่อนไขสำคัญ:** ทุก Service ต้องกำหนด `container_name` โดยมี Prefix เป็น **Username** ของนักศึกษา เช่น `somchai-db`, `somchai-web` เป็นต้น

---

### 2. ข้อกำหนดทางเทคนิค (Technical Requirements)

#### 🌐 **ระบบเครือข่าย (Networks)**
ต้องมีการแยก Network ออกเป็น 2 วง (Isolation):
*   `frontend-zone`: สำหรับให้ `web-proxy` และ `db-management` ติดต่อกับผู้ใช้
*   `backend-zone`: วงปิดสำหรับให้ `backend-php` และ `db-management` ติดต่อกับ `db-server` เท่านั้น

#### 💾 **การเก็บข้อมูล (Volumes)**
*   **Named Volume:** สร้าง `db-storage` เพื่อเก็บข้อมูล MySQL ถาวรที่ `/var/lib/mysql`
*   **Bind Mount:** เชื่อมโยงโฟลเดอร์ `./app` ในเครื่อง เข้ากับ `/var/www/html` ในคอนเทนเนอร์ `backend-php`

#### 🔐 **ความลับและความปลอดภัย (Secrets & Configs)**
*   **Secrets:** ในโฟลเดอร์ secret ให้กำหนดค่าในไฟล์ `.db_root_pass.txt` และ `.db_pass_pass.txt` เพื่อเก็บรหัสผ่าน และเรียกใช้ผ่าน `secrets` (ห้ามพิมพ์รหัสผ่านลงใน YAML)
*   **Configs:** ในโฟลเดอร์ nginx ให้ไฟล์ `my-nginx.conf` และให้เรียกใช้ไฟล์ตั้งค่า Nginx ผ่าน configs ที่พาธ `/etc/nginx/conf.d/default.conf`

#### 📝 **ตัวแปรสภาพแวดล้อม (.env)**
*   สร้างไฟล์ `.env` เก็บค่าตัวแปร: `MY_jeranun`, `HTTP_PORT`, `PMA_PORT`, `DB_NAME` และนำไปใช้ใน `compose.yaml` ทั้งหมด

---

### 3. การจำลองข้อมูลระบุตัวตน (Data Seeding)
หลังจากการรันระบบสำเร็จ ให้นักศึกษาเข้าใช้งาน **phpMyAdmin** ด้วยบัญชีผู้ใช้ที่ตั้งขึ้น และใช้เนื้อหาไฟล์ชื่อ seed-data.sql เพื่อสร้างตารางชื่อ `students` ในฐานข้อมูลที่กำหนดไว้ พร้อมเพิ่มข้อมูลดังนี้:
1.  **Student ID:1660701226** รหัสนักศึกษา
2.  **Full Name:Jeranun-Auemta** ชื่อ-นามสกุล ของนักศึกษา
3.  **Username:jeranun_auemta** ชื่อ username ของนักศึกษา
4.  **Email:jeranun.auem@bumail.net** ชื่อบัญชีผู้ใช้ที่ใช้ในโปรเจกต์
5.  **Status:Submitted** ข้อมูลจำลองอื่นๆ (สถานะการส่งงาน)

```sql
-- 1. สร้างตารางสำหรับเก็บข้อมูลนักศึกษา
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(15) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100),
    `status` ENUM('Submitted', 'Pending', 'In Progress') DEFAULT 'Pending',
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. เพิ่มข้อมูล (แก้ไขจุดผิดและเรียงลำดับให้ตรงกับคอลัมน์)
INSERT INTO `students` (`student_id`, `full_name`, `username`, `email`, `status`)
VALUES 
('6601000100', 'นายสมชาย ใจดี', 'somchai-dev', 'somchai.j@mail.com', 'Submitted'),
('6601000200', 'นางสาวสมหญิง รักเรียน', 'somying-it', 'somying.r@mail.com', 'In Progress'),
-- ใส่ข้อมูลจริงของคุณที่บรรทัดนี้ --
('1660701226', 'นางสาวจีรนัน เอี่ยมท่า', 'jeranun_auemta', 'jeranun.auem@bumail.net', 'Submitted');


```
---

### 📤 สิ่งที่ต้องส่ง (Submission Requirements)

รวบรวมไฟล์ส่งขึ้น git และส่ง git repo url 
ใน git repo ประกอบด้วย:

1.  **Files:** ส่งทุกไฟล์
    *   ไฟล์ `compose.yaml`
    *   ไฟล์ `.env`
    *   ไฟล์ความลับอื่นๆ (`.db_root_pass.txt`, `.db_pass_pass.txt`)
    *   ไฟล์ตั้งค่า (`my-nginx.conf`)
    *   ไฟล์ index.php
    *   ไฟล์ seed-data.sql
    *   ไฟล์ readme.md
2.  **Screenshots (รูปภาพผลลัพธ์):**  เพิ่มเติม ส่งที่ MS-Teams: LAB07&Assignment07
    *   ภาพหน้าจอคำสั่ง `docker compose ps -a` ที่เห็นชื่อคอนเทนเนอร์เป็น Username ของตนเอง
    *   ภาพหน้าจอ **phpMyAdmin** ที่แสดงข้อมูลในตาราง `students` (ต้องเห็นชื่อและรหัสนักศึกษาชัดเจน)
    *   ภาพหน้าจอ **เว็๋บ** ที่แสดงข้อมูลในตาราง `students` (ต้องเห็นชื่อและรหัสนักศึกษาชัดเจน)
3.  *   ส่งลิ้งค์ของ git repo ของนักศึกษา เพิ่มเติม ส่งที่ MS-Teams: LAB07&Assignment07

**หมายเหตุ:**  ส่วนนี้จะต้องใช้ในการทำข้อสอบด้วย
* นักศึกษาต้องใช้ git clone [url]
* สร้าง repository ระบุว่าเป็น LAB07-Assignment07-[username ของนักศึกษา] เช่น LAB07-Assignment07-ekachai.kany
* กำหนด git config --global jeranun.name "jeranun"
* กำหนด git config --global jeranun.email "email"
* กำหนด git init
* กำหนด git add .
* กำหนด git commit -m "first commit"
* กำหนด git branch -M main
* กำหนด git remote add origin jeranun git repo url
* กำหนด git push -u origin main

---

### 🎯 เกณฑ์การให้คะแนน (100%)
* [x]    การใช้ Prefix Username ใน Container Name (20%)
* [x]    การแยก Network และความถูกต้องของ YAML (30%)
* [x]    การใช้งาน Secrets, Configs และ Environment Variables (20%)
* [x]    การจำลองข้อมูลระบุตัวตนในฐานข้อมูลผ่าน phpMyAdmin (20%)
* [x]    ความครบถ้วนของไฟล์ที่ส่งและภาพประกอบ (10%)

---
*ศึกษาข้อมูลเพิ่มเติม: [Docker Compose Official Docs](https://docs.docker.com)*
