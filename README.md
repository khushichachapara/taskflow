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
DB_PASS=

CREATE DATABASE taskflow

run php application -- open in browser -- http://kvc.testproj.127.aum/taskflow

----------------------------------------------------------------------
Main Routes
Authentication

/login
/logout

Tasks

GET /tasks – List tasks
GET /tasks/create – Create form
POST /tasks/store – Store task
GET /tasks/edit?id={id} – Edit form
POST /tasks/update – Update task
GET /tasks/delete?id={id} – Soft delete
GET /tasks/view?id={id} – Task detail

Comments

POST /comments/store – Add comment

API

GET /api/tasks – Returns tasks JSON

--------------------------------------------------------------------------
Assumptions

Soft-deleted tasks are excluded from task list.
Activity logs are automatically generated on: Task created , Task updated , Task deleted

Comment counts are generated using LEFT JOIN + COUNT + GROUP BY.

Only authenticated users can access protected routes.

---------------------------------------------------------------------------

MVC + Repository + Model Flow

The user sends a request (for example: /tasks)
The Router forwards the request to the Controller
The Controller calls the Repository
(“Fetch the tasks data”)
The Repository retrieves raw data from the database
The Repository converts the raw database data into Model objects
The Model objects are returned to the Controller
The Controller passes the Model data to the View
The View displays the data to the user