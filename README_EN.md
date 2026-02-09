For the French version, please [click here](README.md)

# Hotel Booking Application - PHP & SQL

## Description

This simple application manages hotel room bookings.  
It is developed in object-oriented PHP and uses a MySQL database to store information about clients, hotels, rooms, and reservations.

The application allows:  
- Management of clients (name, email)  
- Management of hotels (name, address)  
- Management of rooms (room number and associated hotel)  
- Creation and viewing of reservations with start and end dates  

## Technologies Used

- PHP 8.x (OOP)  
- MySQL / MariaDB  
- PDO for secure database connection  
- Simple HTML/CSS frontend  

## Installation

1. Clone this repository:  

   git clone https://github.com/your-username/your-project.git

2. Import the database:

Use the provided schema.sql file to create tables and structure.

Import via phpMyAdmin or command line:

mysql -u user -p database_name < schema.sql

3. Configure the database connection in the Database.php file (host, dbname, user, password).

4. Deploy the files on a local server (e.g., XAMPP, MAMP) or remote server supporting PHP.
