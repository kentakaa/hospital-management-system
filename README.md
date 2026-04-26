# Hospital Management System (HMS)

A comprehensive web-based application designed to manage hospital operations efficiently. This project focuses on streamlining administrative tasks, managing patient records, and handling billing processes.

## 🚀 Features
- **Patient Management:** Register new patients and maintain their medical history.
- **Doctor Scheduling:** Manage doctor profiles and their availability.
- **Appointment Booking:** System to book and track patient appointments.
- **Dashboard:** Overview of hospital activities for administrators.

## 🛠️ Tech Stack
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server:** XAMPP

## 📋 Prerequisites
To run this project locally, you need:
- PHP 7.4 or higher
- MySQL
- XAMPP or any local web server

## 🔧 Installation & Setup
1. **Clone the repository:**
   ```
   git clone [https://github.com/kentakaa/hospital-management-system.git]

2. Database Configuration
- Open XAMPP Control Panel and start Apache and MySQL.

- Go to your browser and open localhost/phpmyadmin.

- Create a new database named blood_bank (or the name specified in your connectionDB.php).

- Select the created database and click on the Import tab.

- Choose the blood_bank.sql file located in the root directory of this project.

- Click Go to import the tables and schema.

3. Application Setup
- Move the project folder to your local server directory (e.g., C:/xampp/htdocs/).

- Open connectionDB.php and verify the following credentials:

- Hostname: localhost

- User: root

- Password: "" (Empty by default)

- Database: blood_bank

4. Running the Project
- Open your browser and type:
  ```
   http://localhost/hospital-management-system

📂 Project Structure

Plaintext
├── assests/            # CSS, Images, and Client-side JS
├── .vscode/            # IDE configurations
├── admin.php           # Admin panel entry point
├── blood_bank.sql      # Database schema export
├── connectionDB.php    # Database connection logic
├── recipient.php       # Recipient/Patient dashboard
├── staff_dashboard.php # Staff management interface
└── README.md           # Project documentation

📜 License
 - This project is for educational purposes. You are free to use and modify it for your own learning.

