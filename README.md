# Mini Products Platform (Native PHP)

A minimal PHP + MySQL back-office for product CRUD with authentication.

## Features
- Login/Logout with hashed passwords
- Session-protected back-office
- Product CRUD (Create, Read, Update, Delete)
- Image upload for a single main product image
- Simple, responsive UI with native HTML/CSS

## This README explains
- How to install the project
- How to configure the database
- How to run the application

## Quick Start
  1. Import the database schema (path: `database/schema.sql`):
  
      ```bash
      mysql -u <user> -p < database/schema.sql
      ```
  
  2. Set DB credentials in `config/database.php`.
  
  3. Run the dev server and open the app:
  
      ```bash
      php -S localhost:8000
      ```
  
  Then visit: [http://localhost:8000/](http://localhost:8000/)

Alternatively, use Apache/Nginx to serve the project directory.

## Live Demo

Hosted on InfinityFree:

- [http://mini-platform.page.gd/](http://mini-platform.page.gd/)

## Demo Credentials

The schema seeds a default admin user:

- Email: `admin@example.com`
- Password: `admin123`

## License
This project is provided for evaluation and demonstration purposes.
