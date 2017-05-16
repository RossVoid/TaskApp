# Task App Example for Tim

Application interacts with database to create users then allow the users to add, modify & delete tasks.

Uses: 
1. php >= version 7
2. slim
3. slim/twig-view
4. illuminate/database
5. respect/validation
6. slim/csrf
7. slim/flash

Installation Instructions

1. Create database and run /sql/exampletasks.sql on created database to generate needed tables.
2. Composer install in command line to install dependencies.
3. Update Database information in /app/app.php in the \Slim\App Settings.