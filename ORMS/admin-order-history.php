<?php
session_start();
include('includes/conn.php'); // Database connection

// Fetch orders from order_history for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM order_history ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order History</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="lg:mx-40" style="background-image: url('assets/img/background.jpg'); background-repeat: repeat;">
  <div class="bg-white w-full lg:my-10 mx-auto p-4 rounded-lg">
    <header class="flex flex-col md:flex-row items-center justify-between p-4 border-b mb-4">
      <div class="flex items-center mb-4 md:mb-0">
        <h1 class="text-3xl font-bold text-yellow-500">ORMS</h1>
        <div class="ml-4 flex items-center text-gray-600">
        </div>
      </div>
      <div class="flex items-center space-x-4">
        <input type="text" placeholder="Search for restaurant, cuisine or a dish" class="px-4 py-2 border rounded-lg w-full md:w-80">
        <div class="flex items-center space-x-2 relative cursor-pointer" id="dropdownButton">
          <span class="text-sm"><?php echo $_SESSION['fullname'] ?></span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
          <div class="absolute right-0 top-0 mt-2 w-48 bg-white border rounded shadow-lg hidden" id="dropdownMenu">
            <a href="index" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Home</a>
            <a href="admin-order-history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Order History</a>
            <a href="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
          </div>
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

    <h2 class="text-2xl font-bold mb-4">Order History</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php while ($order = $result->fetch_assoc()): ?>
      <div class="border rounded-lg overflow-hidden">
        <div class="p-4">
          <h3 class="font-bold text-lg mb-2">
            Order #<?php echo htmlspecialchars($order['order_id']); ?> -
            <?= $order['order_is_fulfilled'] == 1 ? 'Fulfilled' : 'Preparing'; ?>
          </h3>

          <?php
        // Fetch user's fullname based on user_id
        $user_id = $order['user_id'];
        $query_user = "SELECT fullname, contactnumber FROM users WHERE user_id = ?";
        $stmt_user = $conn->prepare($query_user);
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $user_data = $result_user->fetch_assoc();
        $fullname = htmlspecialchars($user_data['fullname']);
        $contactnumber = htmlspecialchars($user_data['contactnumber']);
        ?>

          <p class="font-semibold mb-1">Customer Name: <?php echo $fullname; ?></p>
          <p class="font-semibold mb-4">Contact: <?php echo $contactnumber; ?></p>

          <div class="mb-4">
            <?php
          $order_id = $order['order_id'];
          $query_products = "SELECT products FROM order_history WHERE order_id = ?";
          $stmt_products = $conn->prepare($query_products);
          $stmt_products->bind_param("s", $order_id);
          $stmt_products->execute();
          $result_products = $stmt_products->get_result();
          $products = json_decode($result_products->fetch_assoc()['products'], true);

          $total_amount = 0;
          foreach ($products as $product):
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];
            
            // Fetch product details
            $query_product = "SELECT product_img, product_name, product_price FROM product WHERE product_id = ?";
            $stmt_product = $conn->prepare($query_product);
            $stmt_product->bind_param("s", $product_id);
            $stmt_product->execute();
            $result_product = $stmt_product->get_result();
            $product_data = $result_product->fetch_assoc();
            $item_total = $product_data['product_price'] * $quantity;
            $total_amount += $item_total;
          ?>
            <div class="flex justify-between items-center mb-2">
              <div class="flex items-center">
                <img src="<?php echo htmlspecialchars($product_data['product_img']); ?>" alt="<?php echo htmlspecialchars($product_data['product_name']); ?>" class="w-8 h-8 rounded-full mr-2">
                <span class="text-gray-600"><?php echo htmlspecialchars($product_data['product_name']); ?></span>
              </div>
              <span class="text-gray-600"><?php echo htmlspecialchars($quantity); ?> x ₹<?php echo htmlspecialchars($product_data['product_price']); ?></span>
            </div>
            <?php endforeach; ?>
            <div class="flex justify-between items-center font-bold">
              <span>Total</span>
              <span>₹<?php echo htmlspecialchars($total_amount); ?></span>
            </div>
          </div>

          <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
          </form>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

  </div>
</body>

</html>