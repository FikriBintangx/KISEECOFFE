<div align="center">

# ☕ KISEE COFFEE

### 🚀 Modern Coffee Shop Management System

[![PHP](https://img.shields.io/badge/PHP-7.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.x-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white)](https://codeigniter.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

![Kisee Coffee Preview](image.png)

_A comprehensive web-based coffee shop management system built with CodeIgniter 3_

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [Tech Stack](#-tech-stack) • [Contributing](#-contributing)

</div>

---

## 📖 About

**Kisee Coffee** is a modern, full-featured coffee shop management system designed to streamline operations for cafes and coffee shops. From order management to inventory tracking, this system provides everything you need to run your coffee business efficiently.

## ✨ Features

### 🛒 **Order Management**

-   Real-time order processing
-   Menu browsing with categories (Food & Beverages)
-   Shopping cart functionality
-   Order history tracking

### 👥 **User Management**

-   Secure authentication system
-   User registration and login
-   Profile management
-   Role-based access control

### 🍔 **Product Management**

-   Dynamic menu management
-   Product categories (Burger, Fries, Ice Matcha, etc.)
-   Product images and descriptions
-   Price management

### 💳 **Transaction System**

-   Secure payment processing
-   Transaction history
-   Invoice generation
-   Payment status tracking

### 📊 **Dashboard & Analytics**

-   Admin dashboard
-   Sales reports
-   User analytics
-   Inventory overview

### 🔔 **Notifications**

-   Real-time notifications
-   Order status updates
-   System alerts

### 🌙 **Additional Features**

-   Dark mode support
-   Responsive design
-   OCR Scanner integration
-   Database auto-fix utilities

## 🛠️ Tech Stack

| Technology        | Purpose                |
| ----------------- | ---------------------- |
| **CodeIgniter 3** | PHP Framework          |
| **MySQL**         | Database               |
| **PHP 7.3+**      | Backend Language       |
| **JavaScript**    | Frontend Interactivity |
| **CSS3**          | Styling                |
| **Composer**      | Dependency Management  |

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

-   **XAMPP** (or similar) with:
    -   PHP 7.3 or higher
    -   MySQL 8.0 or higher
    -   Apache Server
-   **Composer** (for dependency management)
-   **Git** (for version control)

## 🚀 Installation

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/FikriBintangx/KISEECOFFE.git
cd KISEECOFFE
```

### 2️⃣ Install Dependencies

```bash
composer install
```

### 3️⃣ Database Setup

1. Create a new database in phpMyAdmin:

    ```sql
    CREATE DATABASE kiisecoffee;
    ```

2. Import the database:

    ```bash
    # Import main database
    mysql -u root -p kiisecoffee < kiisecoffee.sql

    # If needed, run the fix script
    mysql -u root -p kiisecoffee < fix_database.sql
    ```

### 4️⃣ Configure Database Connection

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',        // Your MySQL username
    'password' => '',            // Your MySQL password
    'database' => 'kiisecoffee', // Database name
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

### 5️⃣ Configure Base URL

Edit `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/KiiseCoffee/';
```

### 6️⃣ Set Permissions

```bash
# For Linux/Mac
chmod -R 755 application/cache
chmod -R 755 application/logs

# For Windows (XAMPP)
# Ensure the folders are not read-only
```

### 7️⃣ Start the Application

1. Start XAMPP (Apache & MySQL)
2. Open your browser and navigate to:
    ```
    http://localhost/KiiseCoffee/
    ```

## 📱 Usage

### For Customers:

1. **Register/Login** - Create an account or login
2. **Browse Menu** - Explore available products
3. **Add to Cart** - Select items and quantities
4. **Checkout** - Complete your order
5. **Track Order** - Monitor order status

### For Admins:

1. **Login** - Access admin panel
2. **Manage Products** - Add/Edit/Delete menu items
3. **Process Orders** - Handle customer orders
4. **View Reports** - Check sales and analytics
5. **Manage Users** - Handle user accounts

## 📂 Project Structure

```
KiiseCoffee/
├── application/
│   ├── controllers/     # Application controllers
│   ├── models/          # Database models
│   ├── views/           # View templates
│   ├── libraries/       # Custom libraries (OCR Scanner, etc.)
│   └── config/          # Configuration files
├── assets/
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── img/             # Images
├── system/              # CodeIgniter core files
├── vendor/              # Composer dependencies
└── index.php            # Entry point
```

## 🔧 Key Controllers

-   **Auth.php** - Authentication & Authorization
-   **Home.php** - Homepage & Dashboard
-   **Makanan.php** - Food/Product Management
-   **Notification.php** - Notification System
-   **Dbfix.php** - Database Utilities

## 🎨 Screenshots

<div align="center">

### 🏠 Homepage

![Homepage](image.png)

_More screenshots coming soon..._

</div>

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. **Fork** the repository
2. **Create** a new branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

## 📝 License

This project is licensed under the **MIT License** - see the [license.txt](license.txt) file for details.

## 👨‍💻 Author

**Fikri Bintang**

-   GitHub: [@FikriBintangx](https://github.com/FikriBintangx)
-   Repository: [KISEECOFFE](https://github.com/FikriBintangx/KISEECOFFE)

## 🙏 Acknowledgments

-   Built with [CodeIgniter 3](https://codeigniter.com/)
-   Icons and UI components from various open-source libraries
-   Special thanks to all contributors

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Issues](https://github.com/FikriBintangx/KISEECOFFE/issues) page
2. Create a new issue if your problem isn't already listed
3. Provide detailed information about the problem

---

<div align="center">

### ⭐ Star this repository if you find it helpful!

**Made with ❤️ and ☕ by Fikri Bintang**

</div>
