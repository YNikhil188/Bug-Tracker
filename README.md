# 📊 Bug Tracker – Streamlined Bug Management

> **Last Updated:** September 14, 2025

**Bug Tracker** is a full-stack web application designed to streamline bug tracking, project management, and team collaboration. With a modern **glassmorphism-inspired UI** (light & dark themes), role-based dashboards, and real-time bug tracking, it empowers **Admins, Project Managers, Developers, and Testers** to manage issues efficiently.

---

## 🌟 Why Bug Tracker?

* Report, assign, and track bugs seamlessly  
* Manage projects & user roles with ease  
* Enjoy immersive **glassmorphism UI** with theme toggle  
* Secure authentication with role-based access  
* Visualize project progress with interactive dashboards  

---

## 🛠 Technology Overview

### 🔹 Frontend  
* HTML5, CSS3 (custom glassmorphism theme) – responsive design  
* Bootstrap 5 – styling framework  
* Font Awesome – icons  

### 🔹 Backend  
* PHP 7.4+ – server-side logic  
* PDO – database interaction  
* MySQL – relational database  

### 🔹 Supporting Tools  
* XAMPP – local development server  
* Chart.js – analytics & visualizations  

---

## 🔑 Core Features  

### 🔐 Security & Authentication  
✔ User registration & login  
✔ Role-based access (Admin, PM, Developer, Tester)  
✔ Session-based authentication  

### 📂 Project Management  
✔ Create & assign projects  
✔ View project details & status  

### 🐞 Bug Tracking  
✔ Report bugs with screenshots  
✔ Assign bugs to developers  
✔ Update bug status (open, in_progress, resolved, closed)  
✔ Add & manage comments  

### 📊 Dashboards  
✔ Role-specific dashboards  
✔ PM analytics with charts  

### 🎨 User Experience  
✔ Responsive glassmorphism UI  
✔ Sticky sidebar for smooth navigation  
✔ Real-time error handling  

---

## 📅 Development Roadmap  

**Phase 1 – Foundation**  
* Setup project, MySQL connection, and basic authentication  
* Role-based dashboards & navigation  

**Phase 2 – Core Features**  
* Bug reporting with uploads  
* Project & user management  
* Developer bug management with comments  

**Phase 3 – Enhancements**  
* Chart.js for analytics  
* Theme toggle (dark/light mode)  
* Performance & security improvements  

---

## 🚦 Quick Start  

### Requirements  
* XAMPP (Apache & MySQL)  
* PHP ≥ 7.4  
* Browser: Chrome, Firefox, or Edge  
* Git (optional)  

### Installation  

```bash
# Clone repository
git clone https://github.com/your-username/bugtracker.git
cd bugtracker
```

**Database Setup**  
* Import `database.sql` into MySQL via phpMyAdmin  
* Configure `config/database.php`:  

```php
$host = 'localhost';
$dbname = 'bugtracker';
$username = 'root';
$password = '';
```

* Create `assets/uploads/` and set write permissions  

---

## ▶ Running the App  

1. Start Apache & MySQL in XAMPP  
2. Visit: [http://localhost/bugtracker/](http://localhost/bugtracker/)  

**Default Credentials**  
- Admin → `admin@example.com / password`  
- PM → `pm@example.com / password`  
- Developer → `dev@example.com / password`  
- Tester → `tester@example.com / password`  

---

## 📁 Project Structure  

```
bugtracker/
├── index.php                  # Landing page
├── login.php                  # Login form
├── register.php               # Registration form
├── logout.php                 # Logout handler
├── profile.php                # User profile
├── config/                    # Database config
│   └── database.php
├── includes/                  # Common includes
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── assets/                    # Static assets
│   ├── css/style.css          # Glassmorphism theme
│   ├── js/script.js           # Enhancements
│   ├── uploads/               # Bug screenshots
│   └── vendor/                # (Optional deps)
├── admin/                     # Admin module
│   ├── dashboard.php
│   ├── users.php
│   └── projects.php
├── pm/                        # PM module
│   ├── dashboard.php
│   ├── projects.php
│   ├── bugs.php
│   └── performance.php
├── dev/                       # Developer module
│   ├── dashboard.php
│   └── bugs.php
├── tester/                    # Tester module
│   ├── dashboard.php
│   └── report_bug.php
├── database.sql               # MySQL schema
└── README.md                  # Documentation
```

---

## 🔌 API Reference  

- **Authentication** → `includes/functions.php`  
- **Data Access** → PDO queries in PHP files  
- **No external REST API – logic embedded in pages**  

---

## ⚡ Common Issues  

* **MySQL connection fails** → Ensure MySQL is running  
* **Uploads not working** → Fix permissions on `assets/uploads/`  
* **Theme not applying** → Check `assets/css/style.css` path  

---

## 🤝 Contribution Guide  

1. Fork repository  
2. Create a branch → `git checkout -b feature/new-feature`  
3. Commit changes → `git commit -m "Add new feature"`  
4. Push branch → `git push origin feature/new-feature`  
5. Open a Pull Request  

---

## 📜 License  

Licensed under MIT – see [LICENSE](LICENSE).  

---

## 🙏 Credits  

* **Bootstrap** – responsive design  
* **Font Awesome** – icons  
* **Chart.js** – data visualization  
* **XAMPP** – development server  

---

## 📞 Support  

Open an issue: [Bug Tracker Issues](https://github.com/your-username/bugtracker/issues)  
Include logs & reproduction steps.  

---

🚀 **Built with ❤️ by [Your Name]**  
Efficient Bug Tracking, Elegant Design  
