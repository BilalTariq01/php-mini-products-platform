Mini Platform with Auth & Products

This test must be done only in native PHP (without a framework).
If you have any questions, feel free to ask me.

🎯Goal
Create a small web application with:
    • An authentication system (login / logout).
    • A simple back-office accessible only after login.
    • Basic product management (CRUD: Create, Read, Update, Delete).
⚙️ Technical constraints
    • Database: MySQL
    • Language: Free choice (PHP, Node, Python, etc.)
    • Session / Authentication: via session token or equivalent
    • UI: Simple (native HTML/CSS or minimal front-end framework)
📂 Expected features
    1. Authentication
        ◦ Login page (email + password). (no password reset or email sending, keep it simple)
        ◦ Database check.
        ◦ Access to back-office only if logged in.
    2. Dashboard
        ◦ A simple home page after login (e.g., “Welcome”).
    3. Product management (CRUD)
        ◦ Add a product.
        ◦ List products.
        ◦ Edit a product.
        ◦ Delete a product.
Product model:
    • Product name
    • Description
    • Price
    • Stock
    • Image (one main image only)
📦 Deliverable
A .zip folder containing:
    • The project source code
    • A README.md file explaining:
        ◦ How to install the project
        ◦ How to configure the database
        ◦ How to run the application
👉 The goal is not to create a perfect project, but to evaluate:
    • Your code organization
    • Your technical choices
    • The clarity of the README
