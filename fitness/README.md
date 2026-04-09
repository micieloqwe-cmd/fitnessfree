# ELEV8 Fitness App 🏋️
> Premium Fitness Web Application — HTML · CSS · JS · PHP · MySQL

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

---

## ⚙️ การติดตั้ง (Setup)

### 1. วาง project ใน web server
```bash
# XAMPP: วางไว้ใน
C:/xampp/htdocs/fitness/

# WAMP / Laragon: วางไว้ใน
C:/wamp64/www/fitness/
```

### 2. Import database
1. เปิด **phpMyAdmin** → `http://localhost/phpmyadmin`
2. สร้าง database ชื่อ `fitness`
3. Import ไฟล์ `fitness.sql`

### 3. ตั้งค่า config
แก้ไขไฟล์ `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // ← MySQL username
define('DB_PASS', '');           // ← MySQL password
define('DB_NAME', 'fitness');
define('APP_URL', 'http://localhost/fitness');
```

### 4. เปิดเว็บ
```
http://localhost/fitness
```

---

## 🎨 Design System

| Token | Value |
|-------|-------|
| Background | `#0a0a0b` (deep black) |
| Card | `#141418` |
| Gold accent | `#c9a84c` |
| Text primary | `#f0ede6` |
| Font display | Cormorant Garamond (serif) |
| Font body | DM Sans (sans-serif) |

---

## 👤 Default Roles

| Role | Access |
|------|--------|
| `user` | Dashboard, Exercises, Programs, Challenges, Stats, Profile |
| `admin` | All user pages + Admin panel (Users, Exercises, Programs, Challenges, Badges) |

สร้าง admin user ด้วย SQL:
```sql
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@elev8.com', '$2y$10$...hashed...', 'admin');
```

หรือสมัครปกติแล้ว toggle role ใน Admin → Users

---

## 🔌 Features

- ✅ Authentication (Login / Register / Logout)
- ✅ User Dashboard with stats & EXP progress
- ✅ Exercise Library with search & filter
- ✅ Training Programs with day-by-day schedule
- ✅ Body Stats tracker + weight chart (Chart.js)
- ✅ Challenges system with progress tracking
- ✅ Favorites (AJAX toggle)
- ✅ Notification center
- ✅ User profile & password management
- ✅ Admin panel (CRUD for all entities)
- ✅ Responsive mobile design

---

## 📦 Dependencies (CDN — ไม่ต้อง install)

- [Font Awesome 6.5](https://fontawesome.com)
- [Google Fonts — Cormorant Garamond + DM Sans](https://fonts.google.com)
- [Chart.js 4.4](https://www.chartjs.org) *(body stats page only)*
