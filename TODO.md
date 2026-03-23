# 🗺️ Project Roadmap & TODOs

This document outlines the planned technical improvements and feature expansions for the **Secure PHP E-Commerce Core** (for GitHub publication). The goal is to evolve this foundational MVC structure into a robust, production-ready application.

### 🏗️ Phase 1: Architecture Refactoring (High Priority)
- [ ] **Strict MVC Separation:** Completely remove raw HTML `echo` statements from the Controllers. Pass data arrays to dedicated `.phtml` or template files in the `view/` directory.
- [ ] **Advanced Routing:** Replace the basic `switch` statement in `sorter.php` with a proper Front Controller pattern or a lightweight routing library (e.g., AltoRouter).
- [ ] **PSR-4 Autoloading:** Integrate Composer to handle class autoloading instead of using manual `require_once` statements.
- [ ] **Environment Variables:** Move hardcoded database credentials from `db_config.php` to a `.env` file using `vlucas/phpdotenv`.

### 🛡️ Phase 2: Security Enhancements
- [x] **SQL Injection Protection:** Implemented 100% Prepared Statements (`mysqli_stmt`).
- [ ] **CSRF Protection:** Generate and validate CSRF tokens for all state-changing POST requests (e.g., placing an order, deleting a user).
- [ ] **Input Sanitization:** Add a middleware/validation layer to strictly type-cast and sanitize POST inputs before they reach the Model.
- [ ] **Authentication & Hashing:** Implement a secure login system using `password_hash()` (Bcrypt/Argon2id) and secure HTTP-only sessions.

### ⚙️ Phase 3: Database & Logic
- [x] **Atomic Transactions:** Implemented `begin_transaction()` and `rollback()` for complex multi-table inserts (Order creation).
- [ ] **Soft Deletes:** Add an `is_deleted` boolean column to the `utenti` and `prodotti` tables instead of physically dropping records, preserving order history.
- [ ] **Database Migrations:** Transition from a static `.sql` dump file to a version-controlled migration system.

### 🐳 Phase 4: DevOps & Testing
- [ ] **Dockerization:** Create a `docker-compose.yml` file spinning up a PHP 8.x-FPM container, an Nginx web server, and a MySQL 8 container for instant local deployment.
- [ ] **Unit Testing:** Integrate **PHPUnit** to automatically test the core logic in `model/data.php` (e.g., verifying that the total calculation and stock deductions are mathematically correct).

---
*Note: This roadmap represents the transition from a procedural/basic MVC approach to modern PHP development standards.*