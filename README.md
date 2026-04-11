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
fitness/
├── index.php                        ← Entry point (redirect)
│
├── includes/                        ← Shared components
│   ├── config.php                   ← Database config & constants
│   ├── db.php                       ← Database class (PDO)
│   ├── auth.php                     ← Login / Logout / Register helpers
│   ├── header.php                   ← Navbar + HTML head
│   └── footer.php                   ← Footer + scripts
│
├── assets/                          ← Static files
│   ├── css/
│   │   └── main.css                 ← Global styles (Dark Luxury theme)
│   ├── js/
│   │   └── main.js                  ← Interactions, modals, filters
│   └── images/                      ← Upload images here
│
├── pages/
│   ├── login.php                    ← Login page
│   ├── register.php                 ← Register page
│   │
│   ├── user/                        ← User-facing pages
│   │   ├── dashboard.php            ← Main dashboard
│   │   ├── exercises.php            ← Exercise library + search
│   │   ├── programs.php             ← Training programs list
│   │   ├── program_detail.php       ← Program detail + days
│   │   ├── challenges.php           ← Fitness challenges
│   │   ├── body_stats.php           ← Weight / body fat tracking + chart
│   │   ├── notifications.php        ← Notification center
│   │   └── profile.php              ← Profile + password change
│   │
│   └── admin/                       ← Admin-only pages
│       ├── dashboard.php            ← Admin overview
│       ├── users.php                ← User management
│       ├── exercises.php            ← Exercise CRUD
│       ├── programs.php             ← Program CRUD
│       ├── challenges.php           ← Challenge CRUD
│       └── badges.php               ← Badge CRUD
│
└── api/                             ← AJAX endpoints
    ├── favorites.php                ← Toggle favorites (fetch)
    └── logout.php                   ← Logout handler
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

