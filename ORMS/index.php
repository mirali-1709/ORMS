<?php
include 'includes/conn.php';
session_start();

if (isset($_SESSION['is_admin'])) {
    if ($_SESSION['is_admin'] == 1) {
        include 'admin-dashboard.php';
        exit();
    }
}

// Function to generate a unique order ID
function generateOrderID() {
    return 'ORDER_' . strtoupper(bin2hex(random_bytes(6)));
}

// Handle cart update, order processing, and removal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        
        // Add to cart logic
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 0;
        }
        $_SESSION['cart'][$product_id] += $quantity;

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'checkout') {
        $order_id = generateOrderID();
        $user_id = $_SESSION['user_id'];
        $total_price = 0;
        $products = [];

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Fetch product price
            $stmt = $conn->prepare("SELECT product_price FROM product WHERE product_id = ?");
            $stmt->bind_param("s", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            $product_price = $product['product_price'];
            $total_price += $product_price * $quantity;

            $products[] = ['product_id' => $product_id, 'quantity' => $quantity];
        }

        // Insert into order_history
        $stmt = $conn->prepare("INSERT INTO order_history (order_id, user_id, products, total_price) VALUES (?, ?, ?, ?)");
        $products_json = json_encode($products);
        $stmt->bind_param("sssd", $order_id, $user_id, $products_json, $total_price);
        $stmt->execute();

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect or display success message
        header('Location: order?ord=' . $order_id);
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
        $product_id = $_POST['product_id'];

        // Remove from cart logic
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);

            // Redirect to refresh the page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}


// Fetch products
if (isset($_GET['category'])) {
    // Category specified, fetch products for the selected category
    $products = [];
    $category = $_GET['category'];
    
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_status = 1 AND product_category_id = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    
    // Fetch results
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    // No category specified, fetch all active products
    $products = [];
    $stmt = $conn->query("SELECT * FROM product WHERE product_status = 1");
    while ($row = $stmt->fetch_assoc()) {
        $products[] = $row;
    }  
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ORMS</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>

<body style="background-image: url('assets/img/background.jpg'); background-repeat: repeat;" class="lg:mx-40">
  <div class="bg-white w-full mx-auto lg:my-10 rounded-lg">
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

    <div class="p-4 flex flex-col md:flex-row">
      <!-- Main Content Section -->
      <div class="w-full <?php if (isset($_SESSION['user_id'])) {?> md:w-3/4 <?php }?> pr-4">

        <h2 class="text-2xl font-bold mb-4">Eat what makes you happy</h2>
        <div class="flex flex-wrap gap-4 mb-8 justify-center">
          <div class="text-center cursor-pointer" onclick="window.location.href = '?'">
            <img src="assets/img/fast-food-flat-illustration-delicious-food-arranged-circle_58813-438.jpg" alt="Pizza" class="w-24 h-24 rounded-full mb-2">
            <span>ALL</span>
          </div>
          <?php
                    // Query to fetch categories where status = 1
                    $categories_result = $conn->query("SELECT category_id, category_name, category_img FROM category WHERE status = 1");

                    // Check if the query was successful
                    if ($categories_result) {
                        // Loop through the result set and generate the HTML for each category
                        while ($category = $categories_result->fetch_assoc()) {
                            echo '<div class="text-center cursor-pointer" onclick="window.location.href = \'?category='.$category['category_id'].'\'">
                                    <img src="'.$category['category_img'].'" alt="'.$category['category_name'].'" class="w-24 h-24 rounded-full mb-2">
                                    <span>'.$category['category_name'].'</span>
                                  </div>';
                        }
                    } else {
                        // Display an error message if the query fails
                        echo '<p class="text-red-500">Error fetching categories.</p>';
                    }
                    ?>
        </div>

        <h2 class="text-2xl font-bold mb-4">Order food online</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:grid-cols-3 gap-6">
          <?php foreach ($products as $product): ?>
          <div class="border rounded-lg overflow-hidden">
            <img src="<?php echo $product['product_img']; ?>" alt="<?php echo $product['product_name']; ?>" class="w-full h-48 object-cover">
            <div class="p-4">
              <div class="flex justify-between items-center mb-2">
                <h3 class="font-bold text-sm md:text-base"><?php echo $product['product_name']; ?></h3>
              </div>
              <p class="text-gray-600 text-xs md:text-sm mb-2"><?php echo $product['product_description']; ?></p>
              <p class="text-gray-600 text-xs md:text-sm">₹<?php echo $product['product_price']; ?> for one</p>
              <form method="POST" class="flex flex-col mt-4">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <label for="quantity-<?php echo $product['product_id']; ?>" class="text-xs md:text-sm">Quantity:</label>
                <input type="number" id="quantity-<?php echo $product['product_id']; ?>" name="quantity" value="1" min="1" class="border rounded-lg p-1 mb-2 p-2">
                <button type="submit" name="action" value="add_to_cart" class="w-full bg-yellow-500 text-white py-2 rounded-lg">Add to Cart</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if (isset($_SESSION['user_id'])) {?>
      <!-- Cart Section -->
      <div class="w-full md:w-1/4 mt-8 md:mt-0 bg-gray-100 p-4 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Cart</h2>
        <div class="border-b pb-4 mb-4">
          <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
          <?php $total_amount = 0; ?>
          <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
          <?php
                $stmt = $conn->prepare("SELECT product_img, product_name, product_price FROM product WHERE product_id = ?");
                $stmt->bind_param("s", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $item_total = $product['product_price'] * $quantity;
                $total_amount += $item_total;
                ?>
          <div class="flex justify-between items-center mb-2">
            <div class="flex items-center">
              <img src="<?php echo $product['product_img']; ?>" alt="<?php echo $product['product_name']; ?>" class="w-16 h-16 rounded-3xl mr-2">
              <span class="text-gray-600"><?php echo $product['product_name']; ?></span>
            </div>
            <div class="flex items-center">
              <span class="text-gray-600 mr-4"><?php echo $quantity; ?> x ₹<?php echo $product['product_price']; ?></span>
              <form method="POST" class="ml-2">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button type="submit" name="action" value="remove_from_cart" class="text-red-500 hover:text-red-700">Remove</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
          <div class="flex justify-between items-center font-bold">
            <span>Total</span>
            <span>₹<?php echo $total_amount; ?></span>
          </div>
          <?php else: ?>
          <p class="text-gray-600">Your cart is empty.</p>
          <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <form method="POST">
          <button type="submit" name="action" value="checkout" class="w-full bg-yellow-500 text-white py-2 rounded-lg">Checkout</button>
        </form>
        <?php endif; ?>
      </div>
      <?php }?>

    </div>
  </div>
</body>

</html>