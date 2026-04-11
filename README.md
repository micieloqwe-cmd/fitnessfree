<div align="center">

<img src="https://capsule-render.vercel.app/api?type=waving&color=C9A84C&height=200&section=header&text=ELEV8%20FITNESS&fontSize=60&fontColor=ffffff&fontAlignY=38&desc=Your%20Premium%20Fitness%20Journey&descAlignY=60&descSize=18&animation=fadeIn" width="100%"/>

</div>

<div align="center">

### ◦ E L E V 8 ◦
#### *Start your transformation today. No excuses.*

<br/>

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://javascript.com)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://html.spec.whatwg.org)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3.org/Style/CSS/)

</div>

---

## ✨ Overview

**ELEV8** คือ Full-Stack Fitness Web Application ที่พัฒนาเพื่อใช้งานจริง — ไม่ใช่แค่ UI prototype แต่มีระบบครบครัน ตั้งแต่ Authentication, Role Management ไปจนถึง Progress Tracking และ Admin Panel

> 💡 ออกแบบด้วยแนวคิด **Modern · Premium · Scalable**

---

# 💎 ELEV8 - Premium Fitness Management System

<p align="center">
  <img src="https://img.shields.io/badge/PLATFORM-ELEV8-black?style=for-the-badge&logo=target&logoColor=FFD700"/>
  <img src="https://img.shields.io/badge/UI_DESIGN-PREMIUM_DARK-gold?style=for-the-badge&logo=figma&logoColor=white"/>
  <img src="https://img.shields.io/badge/TECH_STACK-PHP_|_MySQL_|_JS-8892BF?style=for-the-badge&logo=php&logoColor=white"/>
</p>

---

##  User 
<img width="1912" height="968" alt="Screenshot 2026-04-11 115356" src="https://github.com/user-attachments/assets/42d02926-2bf9-4a47-bcd8-e347979c5364" />
<img width="1918" height="970" alt="Screenshot 2026-04-11 115436" src="https://github.com/user-attachments/assets/3a15e027-a91e-458a-a90e-2abf0ad8da8f" />
<img width="1919" height="968" alt="Screenshot 2026-04-11 115443" src="https://github.com/user-attachments/assets/40d7b958-6d93-4cae-a565-1dc1b31ad4fe" />
<img width="1919" height="968" alt="Screenshot 2026-04-11 115449" src="https://github.com/user-attachments/assets/c9663ea0-d2e7-46e4-b251-33802de8f6b7" />
<img width="1919" height="968" alt="Screenshot 2026-04-11 115455" src="https://github.com/user-attachments/assets/e764fffe-411b-4d4f-a154-8e26a0d897e0" />
<img width="1919" height="970" alt="Screenshot 2026-04-11 115502" src="https://github.com/user-attachments/assets/2e1380f6-a962-46cf-987b-7b45dbfb7754" />
<img width="1919" height="967" alt="Screenshot 2026-04-11 115511" src="https://github.com/user-attachments/assets/b109fcfa-648d-454e-8f12-ebe981b5fbe5" />
<img width="1919" height="966" alt="Screenshot 2026-04-11 115541" src="https://github.com/user-attachments/assets/ebafae82-4ff6-4398-a498-69b3c4b6d9b4" />



##  Admin 



---

## 🎯 Key Features

<table>
  <tr>
    <td width="50%">

### 👤 User Features
- 🔐 **Authentication** — Login / Register
- 📊 **Dashboard** — Calories · Streak · EXP Points
- 💪 **Exercise Library** — Filter by level & muscle group
- 📅 **Training Programs** — Structured multi-day plans
- 🏆 **Challenges** — Join & track personal challenges
- 📈 **Body Stats** — Weight, body fat, muscle mass trends
- ❤️ **Favorites** — AJAX-powered save system
- 🔔 **Notifications** — Activity alerts

    </td>
    <td width="50%">

### 🛠️ Admin Features
- 📋 **Dashboard** — Platform-wide overview
- 👥 **User Management** — Search, promote, delete
- 🏋️ **Exercise CRUD** — Full exercise management
- 📦 **Program CRUD** — Create training programs
- 🎯 **Challenge CRUD** — Design custom challenges
- 🏅 **Badge System** — 9 badges with Font Awesome icons
- 📊 **Usage Analytics** — Most-used exercises tracker

    </td>
  </tr>
</table>

---

## 🧠 System Architecture

```
┌─────────────────────────────────────────────────────────┐
│                     ELEV8 FITNESS                        │
│  ┌───────────────┐   ┌──────────────┐   ┌────────────┐  │
│  │  Frontend     │──▶│  PHP Backend │──▶│  MySQL DB  │  │
│  │  HTML/CSS/JS  │   │  PDO + Auth  │   │  fitness   │  │
│  │  AJAX/Fetch   │◀──│  Sessions    │◀──│  database  │  │
│  └───────────────┘   └──────────────┘   └────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## 📁 Project Structure

```
FITNESS_ELEV8/
└── fitness/
    ├── 📄 index.php                   ← Entry point
    ├── 📄 login.php                   ← Sign in
    ├── 📄 register.php                ← Sign up
    │
    ├── 📂 api/
    │   ├── favorites.php              ← AJAX favorites toggle
    │   └── logout.php                 ← Session destroy
    │
    ├── 📂 assets/
    │   ├── css/                       ← Stylesheets
    │   ├── images/                    ← Uploads & assets
    │   └── js/                        ← Client-side scripts
    │
    ├── 📂 includes/
    │   ├── config.php                 ← App configuration
    │   ├── db.php                     ← PDO connection
    │   ├── auth.php                   ← Session & guards
    │   ├── header.php                 ← Navbar + head
    │   └── footer.php                 ← Footer + scripts
    │
    └── 📂 pages/
        ├── 📂 admin/
        │   ├── dashboard.php          ← Platform overview
        │   ├── users.php              ← Manage users
        │   ├── exercises.php          ← Manage exercises
        │   ├── programs.php           ← Manage programs
        │   ├── challenges.php         ← Manage challenges
        │   └── badges.php             ← Manage badges
        │
        └── 📂 user/
            ├── dashboard.php          ← User home
            ├── profile.php            ← Profile settings
            ├── body_stats.php         ← Body tracking
            ├── exercises.php          ← Exercise library
            ├── programs.php           ← Browse programs
            ├── program_detail.php     ← Program detail
            ├── challenges.php         ← Active challenges
            └── notifications.php     ← Alerts
```

---

## 🔐 Role System

<div align="center">

| Role | Access Level | Capabilities |
|:---:|:---:|:---|
| 👤 **User** | Standard | Browse, track, favorite, join challenges |
| 🛠️ **Admin** | Full Access | CRUD on all content + user management |

</div>

---

## 🎨 Design System

<div align="center">

| Token | Value | Usage |
|:---:|:---:|:---|
| `--bg-primary` | `#0a0a0b` | Page background |
| `--bg-card` | `#141418` | Card surfaces |
| `--accent-gold` | `#c9a84c` | Primary accent, CTAs |
| `--font-heading` | Cormorant Garamond | Display text, titles |
| `--font-body` | DM Sans | Body text, UI labels |

</div>

---

## ⚙️ Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/fitness-elev8.git

# 2. Move to your XAMPP htdocs
cp -r fitness-elev8 C:/xampp/htdocs/fitness

# 3. Import the database
# Open phpMyAdmin → Create DB "fitness" → Import fitness.sql

# 4. Configure connection (if needed)
# Edit includes/config.php

# 5. Launch
http://localhost/fitness
```

> **Requirements:** PHP 7.4+, MySQL 5.7+, XAMPP (or any LAMP/WAMP stack)

---

## 🔄 User Flow

```
Register / Login
      │
      ▼
  Dashboard ──── Body Stats ──── Progress Charts
      │
      ├──── Programs ──── Program Detail ──── Start Workout
      │
      ├──── Exercise Library ──── Favorites
      │
      └──── Challenges ──── Join ──── Track ──── Complete
```

---

## 🚀 Roadmap

- [ ] 🤖 AI Workout Recommendation Engine
- [ ] 💳 Subscription & Payment System  
- [ ] 📱 Mobile App (Flutter / React Native)
- [ ] 🌐 SaaS multi-tenant version
- [ ] 🏋️ Live workout session tracker
- [ ] 📊 Advanced analytics dashboard

---

## 🏆 Suitable For

<div align="center">

| 💼 Portfolio | 🎓 Final Year Project | 💻 Fullstack Practice | 🚀 SaaS Starter |
|:---:|:---:|:---:|:---:|
| ✅ | ✅ | ✅ | ✅ |

</div>

---

## 📄 License

```
Free for learning & portfolio use.
```

---

<div align="center">

**Made with ❤️ and lots of ☕**

*If you found this useful, please give it a* ⭐

<img src="https://capsule-render.vercel.app/api?type=waving&color=C9A84C&height=100&section=footer" width="100%"/>

</div>
