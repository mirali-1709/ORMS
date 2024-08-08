# ORMS

**ORMS** is a mobile-first online ordering system developed using PHP with Tailwind CSS. It allows administrators to manage products and orders, while users can browse the menu, manage their cart, and place orders efficiently.

## Features

### Admin Features
- **Manage Products and Pricing**: Add, edit, and delete product details with corresponding prices.
- **View Orders**: Access order details and manage order status.

### User Features
- **Login**: Users can log in using their mobile number and password. If the account does not exist, it will be created automatically at login.
- **Browse Menu**: Explore available menu items and add desired products to the cart.
- **View Cart**: Review selected items and proceed to checkout.
- **Place Order**: Complete the ordering process and view a thank you page with an order number and order details.
- **Order History**: View past orders.

## Authentication

- **Admin Login**: Admins can log in using their mobile number and password to access administrative features.
- **User Registration**: Users can register automatically by logging in with a mobile number.

## Technologies Used

- **Backend**: PHP
- **Frontend**: Tailwind CSS
- **Database**: MySQL

## Installation

### Prerequisites
- PHP 7.x or later
- MySQL 5.x or later
- Web server (e.g., Apache)

### Setup Instructions
1. **Configure the Database**:
   - Create a new MySQL database named `orms`.
   - Import the SQL file `orms.sql` into your database.
2. **Start the Server**:
   - Ensure your web server is running.
   - Access the application in your browser.

## Usage

### Admin Access
- Navigate to: `http://hostname/orms/login`
- Login with admin credentials:
  - **Username**: Admin
  - **Password**: Mira17

### User Access
- Visit: `http://hostname/orms/login`
- Login using a mobile number. If the user doesn't exist, a new account will be created automatically.
- User Login (9988776655): Username: Admin Password: Mira17 you can also register new one by your self.

## Folder Structure

ORMS/
- ├── assets/
- │ └── img/
- ├── db/
- │ └── orms.sql
- ├── includes/
- │ └── conn.php
- ├── admin-dashboard.php
- ├── admin-order-history.php
- ├── index.php
- ├── login.php
- ├── logout.php
- ├── order.php
- ├── order_history.php



  
