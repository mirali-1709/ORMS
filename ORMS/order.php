<?php
// Database connection
include 'includes/conn.php';
session_start();

// Fetch order ID from query string
$order_id = isset($_GET['ord']) ? $_GET['ord'] : '';

if ($order_id) {
    // Fetch order details
    $stmt = $conn->prepare("SELECT products, total_price FROM order_history WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        $products = json_decode($order['products'], true);
        $total_price = $order['total_price'];
    } else {
        // Handle case where order is not found
        $products = [];
        $total_price = 0;
    }
} else {
    // Handle case where order ID is not provided
    $products = [];
    $total_price = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Function to start countdown and redirect
        function startCountdown(duration, redirectUrl) {
            let timer = duration, seconds;
            const countdownElement = document.getElementById('countdown');
            const interval = setInterval(function () {
                seconds = parseInt(timer % 60, 10);
                seconds = seconds < 10 ? '0' + seconds : seconds;
                countdownElement.textContent = seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    window.location.href = redirectUrl;
                }
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            startCountdown(4, 'index'); // Redirect to index.php after 5 seconds
        });
    </script>
</head>
<body class="lg:mx-40" style="background-image: url('assets/img/background.jpg'); background-repeat: repeat;">
<div class="bg-white w-full lg:my-10 mx-auto p-4 rounded-lg">
        <header class="flex flex-col md:flex-row items-center justify-between p-4 border-b">
            <div class="flex items-center mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-yellow-500">ORMS</h1>
                <div class="ml-4 flex items-center text-gray-600">
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search for restaurant, cuisine or a dish" class="px-4 py-2 border rounded-lg w-full md:w-80">
                <div class="flex items-center space-x-2 relative cursor-pointer" id="dropdownButton">
                    <span class="text-sm"><?php if (isset($_SESSION['fullname'])) {
                        echo $_SESSION['fullname'];?>
                    </span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <div class="absolute right-0 top-0 mt-2 w-48 bg-white border rounded shadow-lg hidden" id="dropdownMenu">
                        <a href="index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Home</a>
                        <a href="order_history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Order History</a>
                        <a href="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                <?php } else{?>
                     Login
                    </span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <div class="absolute right-0 top-0 mt-2 w-48 bg-white border rounded shadow-lg hidden" id="dropdownMenu">
                        <a href="index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Home</a>
                        <a href="login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
                    </div>
                <?php }?>
                </div>

                <script>
                    document.getElementById('dropdownButton').addEventListener('click', function() {
                        var menu = document.getElementById('dropdownMenu');
                        menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' : 'none';
                    });

                    // Optional: Close the dropdown if the user clicks outside of it
                    window.addEventListener('click', function(e) {
                        var menu = document.getElementById('dropdownMenu');
                        if (!document.getElementById('dropdownButton').contains(e.target) && !menu.contains(e.target)) {
                            menu.style.display = 'none';
                        }
                    });
                </script>
            </div>
        </header>

  <div class="text-center mb-8">
    <h2 class="text-3xl font-bold text-yellow-500 mb-4">Thank You for Your Order!</h2>
    <p class="text-lg text-gray-700 mb-4">Your order has been successfully placed. Here is a summary of your order:</p>
  </div>

  <div class="border rounded-lg overflow-hidden p-4 bg-gray-50">
    <h3 class="font-bold text-xl mb-4">Order Summary</h3>
    <div class="border-b pb-4 mb-4">
      <?php if (!empty($products)): ?>
        <?php $total_amount = 0; ?>
        <?php foreach ($products as $product): ?>
            <?php
            // Fetch product details
            $stmt = $conn->prepare("SELECT product_img, product_name, product_price FROM product WHERE product_id = ?");
            $stmt->bind_param("s", $product['product_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $product_info = $result->fetch_assoc();
            $item_total = $product_info['product_price'] * $product['quantity'];
            $total_amount += $item_total;
            ?>
            <div class="flex justify-between items-center mb-2">
              <div class="flex items-center">
                <img src="<?php echo $product_info['product_img']; ?>" alt="<?php echo $product_info['product_name']; ?>" class="w-8 h-8 rounded-full mr-2">
                <span class="text-gray-600"><?php echo $product_info['product_name']; ?></span>
              </div>
              <span class="text-gray-600"><?php echo $product['quantity']; ?> x ₹<?php echo $product_info['product_price']; ?></span>
            </div>
        <?php endforeach; ?>
        <div class="flex justify-between items-center font-bold">
          <span>Total</span>
          <span>₹<?php echo $total_amount; ?></span>
        </div>
      <?php else: ?>
        <p class="text-gray-600">No products found for this order.</p>
      <?php endif; ?>
    </div>
    <div class="text-center">
      <p class="text-gray-600 mb-4">You will be redirected to the homepage in <span id="countdown" class="font-bold text-yellow-500">05</span> seconds.</p>
      <button class="w-full bg-yellow-500 text-white py-2 rounded-lg mt-4" onclick="window.location.href='index'">Continue Shopping Now</button>
    </div>
  </div>
</div>
</body>
</html>
