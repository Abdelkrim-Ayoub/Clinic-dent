<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "looserxbeep9999";
$dbname = "clinicdent";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request
if (isset($_POST['ajax']) && $_POST['ajax'] == 'true') {
    $email = isset($_POST['email']) ? trim($conn->real_escape_string($_POST['email'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check if email exists
    $sql = "SELECT id_user, email, password, role FROM Login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Store session data
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['role'] = $row['role'];

            // Response for successful login
            echo json_encode([
                'status' => 'success',
                'redirect' => $row['role'] == 'Patient' ? '../user/user.php' : '../admin/admin/adm.php'
            ]);
        } else {
            // Incorrect password
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
        }
    } else {
        // Email not found
        echo json_encode(['status' => 'error', 'message' => 'No user found with that email']);
    }

    $stmt->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="loginstyle.css">
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const loginForm = document.getElementById("loginForm");
        const errorMessage = document.getElementById("errorMessage");

        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault(); // Prevent page refresh

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            // Send login request via AJAX
            try {
                const response = await fetch("login.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({
                        email,
                        password,
                        ajax: true // This flag distinguishes AJAX requests
                    }),
                });
                const result = await response.json();

                if (result.status === "success") {
                    // Redirect to the appropriate page
                    window.location.href = result.redirect;
                } else {
                    // Display error message
                    errorMessage.textContent = result.message;
                    errorMessage.style.color = "red";
                }
            } catch (error) {
                console.error("Error during login:", error);
                errorMessage.textContent = "An unexpected error occurred. Please try again.";
                errorMessage.style.color = "red";
            }
        });
    });
</script>
</head>
<body>
<header>
    <h2>WEBDENT</h2>
    <nav class="navbar">
        <a href="../landing/main.php">Home</a>
    </nav>
</header>

<div class="cont">
    <div class="box">
        <div class="textLogin">
            <h2>Login</h2>
            <form id="loginForm">
                <!-- Email Input -->
                <div class="Email">
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>

                <!-- Password Input -->
                <div class="Password">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>

                <!-- Error Message -->
                <div id="errorMessage"></div>

                <!-- Submit Button -->
                <div class="Button">
                    <button type="submit">Login</button>
                </div>
                <a href="../signUp/signUp.php" class="CreeUnCompte">Create an account</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>