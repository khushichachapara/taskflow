# TaskFlow – PHP MVC Task Management System

TaskFlow is a simple PHP MVC-based task management application that allows users to create, update, view, and manage tasks with priorities and statuses.

---

## Requirements

- PHP 8.x
- Composer
- MySQL / MariaDB
- Web server (Nginx / Apache)

---

## Installation
```bash
git clone <repo-url>
cd TaskFlow

composer install

create environment file
DB_HOST=127.0.0.1
DB_NAME=taskflow
DB_USER=root
DB_PASS

CREATE DATABASE taskflow

run php application 