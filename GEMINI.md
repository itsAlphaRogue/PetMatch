# PetMatch - Project Instructions

## Project Overview

PetMatch is a web-based pet adoption platform that facilitates matching users with pets. It uses a PHP backend, MySQL database, and a modern frontend styled with Tailwind CSS.

### Core Technologies

- **Backend:** PHP 8.x
- **Database:** MySQL (MariaDB)
- **Frontend Styling:** Tailwind CSS v4
- **Frontend Logic:** Vanilla JavaScript (ES6+)
- **Routing:** Custom PHP-based router in `index.php`

## Project Structure

- `index.php`: The main entry point and router for the application.
- `pages/`: Contains the HTML structure for different routes (home, findapet, login, etc.).
- `scripts/`: PHP backend scripts handling logic such as authentication, pet filtering, and adoption requests.
- `includes/`: Common PHP includes like `database.php` (DB connection) and `navbar.php`.
- `assets/`: Static assets including:
    - `css/`: Generated Tailwind CSS (`style.css`).
    - `js/`: Frontend JavaScript files managing interactions and AJAX requests.
    - `images/`: Static images and uploaded pet photos.
- `petmatch.sql`: Database schema and initial data dump.

## Setup & Development

### 1. Database Setup

1. Import `petmatch.sql` into your MySQL server (e.g., using phpMyAdmin or the CLI).
2. Configure your database credentials in `includes/database.php`.

### 2. Running the Project

- The project is designed to run on a standard PHP/Apache server (like XAMPP, WAMP, or MAMP).
- Access the project via `http://localhost/PetMatch/`.
- **Clean URLs:** The project uses `.htaccess` to support clean URLs (e.g., `/PetMatch/findapet` maps to `index.php?route=findapet`). Ensure `mod_rewrite` is enabled in your Apache configuration.

### 3. Frontend Styling (Tailwind CSS)

The project uses Tailwind CSS v4. To compile the CSS during development:

```bash
npx @tailwindcss/cli -i input.css -o assets/css/style.css --watch
```

_Note: Ensure `input.css` exists in the root directory._

### 4. Code Formatting

A `.prettierrc` file is provided. You can format the code using:

```bash
npx fontier --write .
```

## Development Conventions

### Routing

All requests are routed through `index.php?route=<page_name>`. The `index.php` file maps these routes to the corresponding files in the `pages/` directory.

### Backend-Frontend Interaction

- Use the JavaScript `fetch` API for asynchronous communication with PHP scripts in the `scripts/` directory.
- PHP scripts should ideally return JSON responses for data-driven operations.
- For updating UI segments (like pet listings), PHP scripts may return HTML fragments.

### Security

- **Authentication:** Sessions are used to track logged-in users and admins (`$_SESSION['user']`, `$_SESSION['admin']`).
- **Database:** Use prepared statements or proper escaping for SQL queries (Note: current scripts like `login.php` use basic `mysqli_query` and should be refactored for better security).
- **Passwords:** Passwords must be hashed using `password_hash()` and verified using `password_verify()`.

## Important Notes

- `Passwords.txt` in the root directory may contain sensitive information; handle with care and do not commit to production.
