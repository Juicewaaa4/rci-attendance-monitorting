# **Quamet**  
### **Attendance Monitoring System**  


## 📌 **Overview**  
Quamet is a **Card-based Human Monitoring Function (RCI AMS)** designed for efficient and accurate attendance tracking using RFID technology. This system ensures real-time logging of attendance for students, employees, or any registered individuals.  

## 🛠 **Features**  
✅ RFID-based check-in and check-out  
✅ Real-time attendance tracking  
✅ Secure user authentication  
✅ Role-based access control (Admin, Teacher)  
✅ Auto-generated reports and logs  

## 🏗 **Technology Stack**  

![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.0-%237952B3.svg?style=flat&logo=bootstrap&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.2.12-%234479A1.svg?style=flat&logo=mysql&logoColor=white)

## 📂 **Installation**  
**Prerequisites:**  
- [Node.js](https://nodejs.org/) (includes npm)  
- [Composer](https://getcomposer.org/)  
- PHP (compatible with Laravel)  
- MySQL (you can use [XAMPP](https://www.apachefriends.org/index.html) to install and run MySQL easily)  

1. Clone the repository and navigate into it:  
   ```sh
   git clone https://github.com/JohnExho/Quamet.git
   cd quamet

2. Install dependencies:

   ```sh
   composer install
   npm install
   ```
3. Set up the environment file and configure your database credentials in `.env`:

   ```sh
   cp .env.example .env
   ```

   Update `.env` with your MySQL or XAMPP database settings, e.g.:

   ```
   DB_DATABASE=schoolattendance
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Generate the application key:

   ```sh
   php artisan key:generate
   ```
5. Run migrations and seed the database:

   ```sh
   php artisan migrate --seed
   ```
6. Start the development server:

   ```sh
   php artisan serve
   ```

> **Note:** If using XAMPP, ensure Apache and MySQL services are running via the XAMPP Control Panel before starting the Laravel server.

## 👤 **User Roles**

* **Admin**: Manages users, logs, and reports
* **Teacher**: Views student attendance logs
* **Student**: Checks attendance records


[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
