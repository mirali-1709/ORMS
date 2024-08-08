<?php
include 'includes/conn.php';

function generateRandomString($length = 10) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            // Validate and sanitize inputs
            $category_name = htmlspecialchars($_POST['category_name'] ?? '');
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($action === 'addCategory') {
                if (isset($_FILES['category_img']) && $_FILES['category_img']['error'] === UPLOAD_ERR_OK) {
                    $category_img = 'assets/img/' . basename($_FILES['category_img']['name']);
                    move_uploaded_file($_FILES['category_img']['tmp_name'], $category_img);
                } else {
                    throw new Exception('Error uploading image');
                }

                $category_id = generateRandomString(6);
                $stmt = $conn->prepare("INSERT INTO category (category_id, category_name, category_img, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $category_id, $category_name, $category_img, $status);
                $stmt->execute();
                $stmt->close();

            } elseif ($action === 'editCategory') {
                $category_id = $_POST['category_id'];
                if (isset($_FILES['category_img']) && $_FILES['category_img']['error'] === UPLOAD_ERR_OK) {
                    $category_img = 'assets/img/' . basename($_FILES['category_img']['name']);
                    move_uploaded_file($_FILES['category_img']['tmp_name'], $category_img);
                } else {
                    throw new Exception('Error uploading image');
                }

                $stmt = $conn->prepare("UPDATE category SET category_name = ?, category_img = ?, status = ? WHERE category_id = ?");
                $stmt->bind_param("ssss", $category_name, $category_img, $status, $category_id);
                $stmt->execute();
                $stmt->close();

            } elseif ($action === 'deleteCategory') {
                $category_id = $_POST['category_id'];
                $stmt = $conn->prepare("DELETE FROM category WHERE category_id = ?");
                $stmt->bind_param("s", $category_id);
                $stmt->execute();
                $stmt->close();

            } elseif ($action === 'addProduct') {
                if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
                    $product_img = 'assets/img/' . basename($_FILES['product_img']['name']);
                    move_uploaded_file($_FILES['product_img']['tmp_name'], $product_img);
                } else {
                    throw new Exception('Error uploading image');
                }

                $product_name = htmlspecialchars($_POST['product_name'] ?? '');
                $product_description = htmlspecialchars($_POST['product_description'] ?? '');
                $product_category_id = htmlspecialchars($_POST['product_category_id'] ?? '');
                $product_price = floatval($_POST['product_price']);
                $isveg = htmlspecialchars($_POST['isveg'] ?? '');
                $product_id = generateRandomString(10);

                $stmt = $conn->prepare("INSERT INTO product (product_id, product_name, product_img, product_description, product_category_id, product_price, isveg, product_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $product_id, $product_name, $product_img, $product_description, $product_category_id, $product_price, $isveg, $status);
                $stmt->execute();
                $stmt->close();

            } elseif ($action === 'editProduct') {
                if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
                    $product_img = 'assets/img/' . basename($_FILES['product_img']['name']);
                    move_uploaded_file($_FILES['product_img']['tmp_name'], $product_img);
                } else {
                    throw new Exception('Error uploading image');
                }

                $product_id = $_POST['product_id'];
                $product_name = htmlspecialchars($_POST['product_name'] ?? '');
                $product_description = htmlspecialchars($_POST['product_description'] ?? '');
                $product_category_id = htmlspecialchars($_POST['product_category_id'] ?? '');
                $product_price = floatval($_POST['product_price']);
                $isveg = htmlspecialchars($_POST['isveg'] ?? '');
                $product_status = htmlspecialchars($_POST['product_status'] ?? '');

                $stmt = $conn->prepare("UPDATE product SET product_name = ?, product_img = ?, product_description = ?, product_category_id = ?, product_price = ?, isveg = ?, product_status = ? WHERE product_id = ?");
                $stmt->bind_param("ssssssss", $product_name, $product_img, $product_description, $product_category_id, $product_price, $isveg, $product_status, $product_id);
                $stmt->execute();
                $stmt->close();

            } elseif ($action === 'deleteProduct') {
                $product_id = $_POST['productId'];
                $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
                $stmt->bind_param("s", $product_id);
                $stmt->execute();
                $stmt->close();

            } elseif ($action === 'fulfillOrder') {
                $order_id = $_POST['order_id'];
                $stmt = $conn->prepare("UPDATE order_history SET order_is_fulfilled = 1 WHERE order_id = ?");
                $stmt->bind_param("s", $order_id);
                $stmt->execute();
                $stmt->close();
            }
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="lg:mx-40" style="background-image: url('assets/img/background.jpg'); background-repeat: repeat;">
  <div class="bg-white w-full lg:my-10 mx-auto p-4 rounded-lg">
    <header class="flex flex-col md:flex-row items-center justify-between p-4 border-b">
      <div class="flex items-center mb-4 md:mb-0">
        <h1 class="text-3xl font-bold text-yellow-500">ORMS</h1>
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

    <div class="flex flex-wrap -mx-4">
      <!-- Categories Section -->
      <div class="w-full md:w-3/4 px-4 mb-8">
        <h2 class="text-2xl font-bold mb-4 mt-6">Manage Categories</h2>
        <button id="addCategoryBtn" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300">Add Category</button>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
          <?php
                $result = $conn->query("SELECT * FROM category");
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="border rounded-lg overflow-hidden">
                            <div class="relative">
                                <img src="'.$row['category_img'].'" alt="Category Image" class="w-full h-48 object-cover">
                                <button class="absolute top-2 right-2 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300" onclick="editCategory(\''.$row['category_id'].'\', \''.$row['category_name'].'\', \''.$row['category_img'].'\', '.$row['status'].')">Edit</button>
                                <button class="absolute top-2 left-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300" onclick="deleteCategory(\''.$row['category_id'].'\')">Delete</button>
                            </div>
                            <div class="p-4">
                                <h3 class="text-xl font-bold mb-2">'.$row['category_name'].'</h3>
                                <p>Status: '.($row['status'] ? 'Active' : 'Inactive').'</p>
                            </div>
                        </div>';
                }
                ?>
        </div>

        <!-- Products Section -->
        <h2 class="text-2xl font-bold mb-4 mt-6">Manage Products</h2>
        <button id="addProductBtn" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300">Add Product</button>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
          <?php
                $result = $conn->query("SELECT * FROM product");
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="border rounded-lg overflow-hidden">
                            <div class="relative">
                                <img src="'.$row['product_img'].'" alt="Product Image" class="w-full h-48 object-cover">
                                <button class="absolute top-2 right-2 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300" onclick="editProduct(\''.$row['product_id'].'\', \''.$row['product_name'].'\', \''.$row['product_img'].'\', \''.$row['product_description'].'\', \''.$row['product_category_id'].'\', '.$row['product_price'].', \''.$row['isveg'].'\', '.$row['product_status'].')">Edit</button>
                                <button class="absolute top-2 left-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300" onclick="deleteProduct(\''.$row['product_id'].'\')">Delete</button>
                            </div>
                            <div class="p-4">
                                <h3 class="text-xl font-bold mb-2">'.$row['product_name'].'</h3>
                                <p>Price: ₹'.$row['product_price'].'</p>
                                <p>Status: '.($row['product_status'] ? 'Available' : 'Out of Stock').'</p>
                            </div>
                        </div>';
                }
                ?>
        </div>
      </div>

      <!-- Orders Section -->

      <div class="w-full md:w-1/4 mt-6">
        <h2 class="text-2xl font-bold mb-4">Orders</h2>
        <div class="space-y-4">
          <?php
    $result = $conn->query("SELECT * FROM order_history WHERE order_is_fulfilled = 0");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="border rounded-lg p-3 bg-white">
                    <div class="flex items-center mb-2">
                    <div class="flex-1">
                    <p class="text-gray-800 font-bold">Order ID: '.$row['order_id'].'</p>';

            // Decode the products JSON data
            $products = json_decode($row['products'], true);
            if (is_array($products)) {
                foreach ($products as $product) {
                    $product_id = $product['product_id'];
                    $quantity = $product['quantity'];

                    // Fetch product details from the products table
                    $product_result = $conn->query("SELECT * FROM product WHERE product_id = '$product_id'");
                    if ($product_result) {
                        if ($product_row = $product_result->fetch_assoc()) {
                            echo '<div class="flex items-center mt-2">';
                            echo '<img src="'.$product_row['product_img'].'" alt="'.$product_row['product_name'].'" class="w-12 h-12 object-cover rounded mr-2">';
                            echo '<div>';
                            echo '<p class="text-gray-800 font-bold">'.$product_row['product_name'].'</p>';
                            echo '<p class="text-gray-600">Quantity: '.$quantity.'</p>';
                            echo '<p class="text-gray-600">Price: '.$product_row['product_price'].'</p>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<p class="text-red-500">Error fetching product details for product ID: '.$product_id.'</p>';
                        }
                    } else {
                        echo '<p class="text-red-500">Error executing query for product ID: '.$product_id.'</p>';
                    }
                }
            } else {
                echo '<p class="text-red-500">Error decoding products data</p>';
            }

            echo '<p class="text-gray-600">Total Price: '.$row['total_price'].'</p>
                  <p>Status: '.($row['order_is_fulfilled'] ? 'Fulfilled' : 'Pending').'</p>
                  <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300 mt-2" onclick="fulfillOrder(\''.$row['order_id'].'\')">Fulfill Order</button>
                  </div>
                  </div>
                </div>';
        }
    } else {
        echo '<p class="text-red-500">Error executing order history query</p>';
    }
    ?>
        </div>

      </div>
    </div>
  </div>

  <!-- Modals for Add/Edit Category/Product -->
  <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-11/12 md:w-1/2 lg:w-1/3 max-h-full overflow-y-auto">
      <h3 class="text-xl font-bold mb-4" id="categoryModalTitle">Add Category</h3>
      <form id="categoryForm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" id="categoryAction" value="addCategory">
        <input type="hidden" name="category_id" id="categoryId">
        <div class="mb-4">
          <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
          <input type="text" name="category_name" id="category_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
          <label for="category_img" class="block text-sm font-medium text-gray-700">Category Image</label>
          <input type="file" name="category_img" id="category_img" class="mt-1 block w-full">
          <img id="category_img_preview" src="" alt="Current Product Image" class="mt-2 max-w-full h-auto h-40" style="display: none;">
        </div>
        <div class="mb-4">
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="flex justify-end">
          <button type="button" id="closeCategoryModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">Cancel</button>
          <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300 ml-4">Save</button>
        </div>
      </form>
    </div>
  </div>

  <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-11/12 md:w-1/2 lg:w-1/3 max-h-full overflow-y-auto">
      <h3 class="text-xl font-bold mb-4" id="productModalTitle">Add Product</h3>
      <form id="productForm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" id="productAction" value="addProduct">
        <input type="hidden" name="product_id" id="productId">
        <div class="mb-4">
          <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
          <input type="text" name="product_name" id="product_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
          <label for="product_img" class="block text-sm font-medium text-gray-700">Product Image</label>
          <input type="file" name="product_img" id="product_img" class="mt-1 block w-full">
          <img id="product_img_preview" src="" alt="Current Product Image" class="mt-2 max-w-full h-auto" style="display: none;">
        </div>
        <div class="mb-4">
          <label for="product_description" class="block text-sm font-medium text-gray-700">Product Description</label>
          <textarea name="product_description" id="product_description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
        <div class="mb-4">
          <label for="product_category_id" class="block text-sm font-medium text-gray-700">Category</label>
          <select name="product_category_id" id="product_category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            <?php
                    $categories = $conn->query("SELECT * FROM category");
                    while ($row = $categories->fetch_assoc()) {
                        echo '<option value="'.$row['category_id'].'">'.$row['category_name'].'</option>';
                    }
                    ?>
          </select>
        </div>
        <div class="mb-4">
          <label for="product_price" class="block text-sm font-medium text-gray-700">Product Price</label>
          <input type="number" name="product_price" id="product_price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" step="0.01" required>
        </div>
        <div class="mb-4">
          <label for="isveg" class="block text-sm font-medium text-gray-700">Is Veg?</label>
          <select name="isveg" id="isveg" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
        <div class="mb-4">
          <label for="product_status" class="block text-sm font-medium text-gray-700">Status</label>
          <select name="product_status" id="product_status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            <option value="1">Available</option>
            <option value="0">Out of Stock</option>
          </select>
        </div>
        <div class="flex justify-end">
          <button type="button" id="closeProductModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">Cancel</button>
          <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300 ml-4">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('addCategoryBtn').addEventListener('click', function() {
      document.getElementById('categoryModal').classList.remove('hidden');
      document.getElementById('categoryModalTitle').textContent = 'Add Category';
      document.getElementById('categoryForm').reset();
      document.getElementById('categoryAction').value = 'addCategory';
      document.getElementById('categoryId').value = '';
    });
    document.getElementById('addProductBtn').addEventListener('click', function() {
      document.getElementById('productModal').classList.remove('hidden');
      document.getElementById('categoryModalTitle').textContent = 'Add Category';
      document.getElementById('categoryForm').reset();
      document.getElementById('categoryAction').value = 'addCategory';
      document.getElementById('categoryId').value = '';
    });
    document.getElementById('closeProductModal').addEventListener('click', function() {
      document.getElementById('productModal').classList.add('hidden');
    });

    function editCategory(id, name, img, status) {
      document.getElementById('categoryModal').classList.remove('hidden');
      document.getElementById('categoryModalTitle').textContent = 'Edit Category';
      document.getElementById('category_name').value = name;
      document.getElementById('category_img').value = '';
      // Set preview image source
      const imgPreview = document.getElementById('category_img_preview');
      imgPreview.src = img; // Set to the URL of the existing image
      imgPreview.style.display = 'block'; // Show the image
      document.getElementById('status').value = status;
      document.getElementById('categoryAction').value = 'editCategory';
      document.getElementById('categoryId').value = id;
    }
    document.getElementById('closeCategoryModal').addEventListener('click', function() {
      document.getElementById('categoryModal').classList.add('hidden');
    });
    document.getElementById('productModal').addEventListener('click', function(e) {
      if (e.target === this) {
        document.getElementById('productModal').classList.add('hidden');
      }
    });

    function editProduct(id, name, img, description, categoryId, price, isVeg, status) {
      document.getElementById('productModal').classList.remove('hidden');
      document.getElementById('productModalTitle').textContent = 'Edit Product';
      document.getElementById('product_name').value = name;
      document.getElementById('product_img').value = '';
      // Set preview image source
      const imgPreview = document.getElementById('product_img_preview');
      imgPreview.src = img; // Set to the URL of the existing image
      imgPreview.style.display = 'block'; // Show the image
      document.getElementById('product_description').value = description;
      document.getElementById('product_category_id').value = categoryId;
      document.getElementById('product_price').value = price;
      document.getElementById('isveg').value = isVeg;
      document.getElementById('product_status').value = status;
      document.getElementById('productAction').value = 'editProduct';
      document.getElementById('productId').value = id;
    }

    function deleteProduct(productId) {
      if (confirm('Are you sure you want to delete this product?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        const productIdInput = document.createElement('input');
        productIdInput.type = 'hidden';
        productIdInput.name = 'productId';
        productIdInput.value = productId;
        form.appendChild(productIdInput);
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'deleteProduct';
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
      }
    }

    function deleteCategory(category_id) {
      if (confirm('Are you sure you want to delete this category?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        const categoryIdInput = document.createElement('input');
        categoryIdInput.type = 'hidden';
        categoryIdInput.name = 'category_id';
        categoryIdInput.value = category_id;
        form.appendChild(categoryIdInput);
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'deleteCategory';
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
      }
    }

    function fulfillOrder(order_id) {
      if (confirm('Are you sure you want to fulfill this order?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        const orderIdInput = document.createElement('input');
        orderIdInput.type = 'hidden';
        orderIdInput.name = 'order_id';
        orderIdInput.value = order_id;
        form.appendChild(orderIdInput);
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'fulfillOrder';
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>

</body>

</html>
