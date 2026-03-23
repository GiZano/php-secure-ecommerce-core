<div align="center">

# 🛒 Secure PHP E-Commerce Management System
### MVC Architecture | Prepared Statements | SQL Transactions

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Security](https://img.shields.io/badge/Security-Prepared_Statements-brightgreen?style=for-the-badge)

</div>

---

## 📖 About The Project

This project is a custom-built, lightweight backend system for managing an E-commerce platform. Developed primarily to demonstrate secure database interaction, the entire data layer is engineered using **100% Parameterized Queries (Prepared Statements)** to completely eliminate SQL Injection vulnerabilities.

The application follows a structured **MVC (Model-View-Controller)** approach, separating the business logic, data access, and user interface into dedicated modules for better maintainability and code clarity.

## ✨ Key Features

* **🛡️ Security First:** Strict implementation of `mysqli_stmt` for every single CRUD operation. No user input is ever directly concatenated into an SQL string.
* **🔄 Atomic Transactions:** Complex operations, such as creating a new order with multiple items, are handled via true SQL Transactions (`begin_transaction`, `commit`, `rollback`). This guarantees database integrity: if one query fails, the entire order is aborted.
* **🧩 Advanced SQL Logic:** Implements relational queries using complex `JOIN`s, data aggregation (`SUM()`), and safe string matching (`LIKE`).
* **📦 Inventory Management:** Dynamic update of product stock quantities directly via mathematical operations at the SQL level.
* **🎨 Clean UI:** Responsive and user-friendly interface powered by Bootstrap 5, operating through a central routing hub.

---

## 🏗️ Architecture & Structure

The repository is organized following an MVC pattern:

```text
/
├── index.php                 # Main entry point and initialization
├── controller/               # Routing and input handling
│   ├── sorter.php            # Central router matching user actions to controllers
│   ├── new_order.php         # Handles order creation requests
│   ├── update_stocks.php     # Handles inventory updates
│   └── ...                   # Other specific controllers
├── model/                    # Database configuration and logic
│   ├── db_config.php         # MySQL connection setup
│   ├── data.php              # Core data access functions (Prepared Statements)
│   └── ecommerce_db.sql      # Database schema and mock data
└── view/                     # User Interface
    └── home_view.php         # Main interactive dashboard (Bootstrap)
```

---

## 💾 Database Schema

The relational database (`negozio_online`) consists of 4 main tables handling the e-commerce flow:

1. **`utenti` (Users):** Stores user credentials and personal data.
2. **`prodotti` (Products):** Inventory registry holding prices, descriptions, and stock quantities.
3. **`ordini` (Orders):** Tracks individual purchases, linking a user to a total spent amount and status.
4. **`dettagli_ordine` (Order Details):** A many-to-many resolution table linking an order to its specific products, freezing the unit price at the time of purchase.

*(Includes cascading deletes to maintain referential integrity when users or orders are removed).*

---

<br><br>
<div align="center">
Made with ❤️ by Giovanni Zanotti
</div>