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

  ## Screenshorts:

![login](https://github.com/user-attachments/assets/88e3cf57-05e2-4700-9771-ca6b0956bdc8)
![admin](https://github.com/user-attachments/assets/da0688b9-e589-45fa-820f-359608d84de4)
![user](https://github.com/user-attachments/assets/6ee8a7df-f5b0-4ea7-b4b0-c5c94d01bc9b)
![history](https://github.com/user-attachments/assets/22716c6c-d8b4-49cc-9bb4-7e65c8de8e0f)




  
