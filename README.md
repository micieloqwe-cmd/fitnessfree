# 🏋️ ELEV8 Fitness App

> Premium Fitness Web Application — HTML · CSS · JS · PHP · MySQL

---

## 📌 เกี่ยวกับโปรเจค

**ELEV8 Fitness App** คือเว็บแอปพลิเคชันด้านฟิตเนสแบบครบระบบ (Full System)  
ออกแบบมาให้สามารถใช้งานได้จริง ไม่ใช่แค่ UI สวยงาม

🔗 Demo: https://fitnessfree.lovestoblog.com/pages/login.php

---

## 🎯 แนวคิดหลัก

- ✅ ใช้งานได้จริง (Production-ready)
- ✅ มีระบบผู้ใช้ (User) และผู้ดูแล (Admin)
- ✅ มีฐานข้อมูล (MySQL)
- ✅ UX/UI ระดับ Premium (Dark Luxury Theme)

---

## 🧠 Architecture

ระบบแบ่งออกเป็น 3 ส่วนหลัก:

### 🖥️ 1. Frontend
- HTML — โครงสร้างหน้าเว็บ
- CSS — ธีม Dark Luxury
- JavaScript — Interaction / AJAX

### ⚙️ 2. Backend
- PHP — จัดการ Logic
- Authentication (Login/Register)
- Authorization (Role-based)

### 🗄️ 3. Database
- MySQL — จัดเก็บข้อมูลทั้งหมด

---

## 📁 โครงสร้างไฟล์

```
FITNESS_ELEV8/
└── fitness/
    ├── index.php                    ← จุดเริ่มต้นของเว็บ (Entry point / redirect ไปหน้าอื่น)
    │
    ├── login.php                    ← หน้าเข้าสู่ระบบของผู้ใช้
    ├── register.php                 ← หน้าสมัครสมาชิกใหม่
    │
    ├── api/                         ← ส่วนจัดการหลังบ้าน (API / AJAX)
    │   ├── favorites.php            ← จัดการระบบรายการโปรด (เพิ่ม/ลบ)
    │   └── logout.php               ← ออกจากระบบ (ลบ session)
    │
    ├── assets/                      ← ไฟล์ static ทั้งหมด (หน้าบ้าน)
    │   ├── css/                     ← ไฟล์ตกแต่งหน้าเว็บ (CSS)
    │   ├── images/                  ← รูปภาพ เช่น รูปโปรไฟล์ / รูป exercise
    │   └── js/                      ← JavaScript (interaction / fetch API)
    │
    ├── includes/                    ← ไฟล์ที่เรียกใช้ซ้ำ (Reusable)
    │   ├── config.php               ← ตั้งค่าระบบ เช่น DB_HOST, DB_NAME
    │   ├── db.php                   ← เชื่อมต่อฐานข้อมูล (PDO)
    │   ├── auth.php                 ← ฟังก์ชันตรวจสอบ login / session
    │   ├── header.php               ← ส่วนหัวเว็บ (navbar + <head>)
    │   └── footer.php               ← ส่วนท้ายเว็บ (footer + script)
    │
    ├── pages/                       ← หน้าแสดงผลของระบบ
    │   ├── admin/                   ← หน้าสำหรับผู้ดูแลระบบ (Admin)
    │   │   ├── dashboard.php        ← หน้าแดชบอร์ด (ภาพรวมระบบ)
    │   │   ├── users.php            ← จัดการผู้ใช้งาน
    │   │   ├── exercises.php        ← จัดการท่าออกกำลังกาย
    │   │   ├── programs.php         ← จัดการโปรแกรมออกกำลังกาย
    │   │   ├── challenges.php       ← จัดการชาเลนจ์
    │   │   └── badges.php           ← จัดการเหรียญรางวัล
    │   │
    │   └── user/                    ← หน้าสำหรับผู้ใช้งานทั่วไป
    │       ├── dashboard.php        ← หน้าแดชบอร์ดผู้ใช้
    │       ├── profile.php          ← ข้อมูลโปรไฟล์ผู้ใช้
    │       ├── body_stats.php       ← ข้อมูลร่างกาย (น้ำหนัก / ส่วนสูง)
    │       ├── exercises.php        ← รายการท่าออกกำลังกาย
    │       ├── programs.php         ← โปรแกรมออกกำลังกาย
    │       ├── program_detail.php   ← รายละเอียดโปรแกรม
    │       ├── challenges.php       ← ชาเลนจ์ของผู้ใช้
    │       └── notifications.php    ← การแจ้งเตือน
    │
    └── README.md                    ← อธิบายโปรเจกต์ วิธีติดตั้ง และใช้งาน
```

### 🔹 includes/
- config.php → ตั้งค่า Database
- db.php → เชื่อมต่อ DB (PDO)
- auth.php → ระบบ Login/Register
- header.php / footer.php → Layout

### 🔹 assets/
- css/main.css → ธีมหลัก
- js/main.js → Interaction
- images/ → รูปภาพ

### 🔹 pages/

#### 👤 User
- dashboard.php
- exercises.php
- programs.php
- program_detail.php
- challenges.php
- body_stats.php
- notifications.php
- profile.php

#### 🛠️ Admin
- dashboard.php
- users.php
- exercises.php
- programs.php
- challenges.php
- badges.php

### 🔹 api/
- favorites.php (AJAX)
- logout.php

---

## 🔐 ระบบสิทธิ์ (Role System)

| Role | สิทธิ์ |
|------|--------|
| user | ใช้งานทั่วไป |
| admin | จัดการระบบทั้งหมด |

---

## ⚙️ Features

- 🔐 Authentication (Login/Register)
- 📊 Dashboard + Progress
- 💪 Exercise Library
- 📅 Training Programs
- 📈 Body Stats + Chart.js
- 🏆 Challenges System
- ❤️ Favorites (AJAX)
- 🔔 Notifications
- 👤 Profile Management
- 🛠️ Admin Panel (CRUD)

---

## 🎨 Design System

- Background: #0a0a0b
- Card: #141418
- Accent: #c9a84c (Gold)
- Font:
  - Headings: Cormorant Garamond
  - Body: DM Sans

---

## 🚀 วิธีติดตั้ง

### 1. Clone หรือวางโปรเจค
```
C:/xampp/htdocs/fitness/
```

### 2. Import Database
- เปิด phpMyAdmin
- สร้าง DB: `fitness`
- Import `fitness.sql`

### 3. ตั้งค่า config
```
includes/config.php
```

### 4. Run
```
http://localhost/fitness
```

---

## 🧩 Core Flow

1. Login → ตรวจสอบผู้ใช้
2. Dashboard → แสดงข้อมูล
3. Programs → เลือกโปรแกรม
4. Body Stats → บันทึกข้อมูล
5. Progress → ดูพัฒนา

---

## 📌 เหมาะสำหรับ

- Portfolio Developer
- ฝึก Full Stack
- โปรเจคจบ / ส่งงาน
- ต่อยอดเป็น SaaS

---

## 💡 Future Ideas

- 🤖 AI แนะนำโปรแกรม
- 💳 ระบบ Subscription
- 📱 Mobile App (React Native / Flutter)

---

## 📄 License

Free for learning & portfolio use

