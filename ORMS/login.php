<?php
include 'includes/conn.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactnumber = $_POST['contactnumber'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'] ?? '';

    // Check if user exists
    $sql = "SELECT * FROM users WHERE contactnumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $contactnumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // User does not exist, create new user
        if (empty($fullname)) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide your full name']);
            exit();
        }

        $sql = "INSERT INTO users (user_id, contactnumber, password, fullname, is_admin, status) VALUES (?, ?, ?, ?, 0, 1)";
        $user_id = bin2hex(random_bytes(8)); // Generate random 16-bit string
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash password
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $user_id, $contactnumber, $hashed_password, $fullname);
        $stmt->execute();
        $is_admin = 0;
    } else {
        // User exists, check password
        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            exit();
        }
        $user_id = $user['user_id'];
        $is_admin = $user['is_admin'];
        $fullname = $user['fullname'];
    }

    // Log in the user
    $_SESSION['contactnumber'] = $contactnumber;
    $_SESSION['user_id'] = $user_id; // Save user_id in session
    $_SESSION['is_admin'] = $is_admin;
    $_SESSION['fullname'] = $fullname;

    echo json_encode(['status' => 'success', 'redirect' => 'index']); // Redirect to the main page after successful login
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function handleLogin(event) {
            event.preventDefault();
            
            let contactnumber = document.getElementById('contactnumber').value;
            let password = document.getElementById('password').value;
            let fullname = document.getElementById('fullname') ? document.getElementById('fullname').value : '';

            let formData = new FormData();
            formData.append('contactnumber', contactnumber);
            formData.append('password', password);
            formData.append('fullname', fullname);

            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    document.getElementById('error-message').innerText = data.message;
                    if (data.message === 'Please provide your full name') {
                        document.getElementById('fullname-container').style.display = 'block';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body class="flex items-center justify-center h-screen" style="background-image: url('assets/img/background.jpg'); background-repeat: repeat;">
<div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
  <header class="flex items-center mb-8">
    <h1 class="text-3xl font-bold text-yellow-500">ORMS</h1>
  </header>

  <div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-700">Login to Your Account</h2>
    <p class="text-gray-600">Please enter your credentials to continue.</p>
  </div>

  <form id="login-form" onsubmit="handleLogin(event)">
    <div class="mb-4">
      <label for="contactnumber" class="block text-gray-700 font-medium mb-2">Mobile Number</label>
      <input type="text" id="contactnumber" name="contactnumber" placeholder="+91 99887 77882" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
    </div>
    <div class="mb-6">
      <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
      <input type="password" id="password" name="password" placeholder="********" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
    </div>
    <div id="fullname-container" class="mb-6" style="display: none;">
      <label for="fullname" class="block text-gray-700 font-medium mb-2">Full Name</label>
      <input type="text" id="fullname" name="fullname" placeholder="John Doe" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
    </div>
    <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition duration-300">Login</button>
    <div id="error-message" class="mt-4 text-red-500"></div>
  </form>
</div>
</body>
</html>
