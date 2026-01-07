<?php
// Database connection
$servername = "localhost"; // Adjust according to your setup
$username = "root";        // Database username
$password = "looserxbeep9999";            // Database password
$dbname = "clinicdent";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Encrypt the password
    $tel = $_POST['tel'];  // Get telephone number

    try {
        // Insert into Login table
        $sqlLogin = "INSERT INTO Login (email, password) VALUES (?, ?)";
        $stmtLogin = $conn->prepare($sqlLogin);
        $stmtLogin->bind_param("ss", $email, $password);
        $stmtLogin->execute();

        // Get the last inserted ID (id_user)
        $id_user = $conn->insert_id;

        // Insert into Patient table, using the last inserted id_user as foreign key
        $sqlPatient = "INSERT INTO Patient (nom, prenom, email, tel, id_user) VALUES (?, ?, ?, ?, ?)";
        $stmtPatient = $conn->prepare($sqlPatient);
        $stmtPatient->bind_param("ssssi", $nom, $prenom, $email, $tel, $id_user);
        $stmtPatient->execute();

        // Redirect to login page after successful signup
        header("Location: ../login/Login.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="signup.css">
  <script src="signup.js"></script>
</head>
<body>
  
  <header>
    <h2>WEBDENT</h2>
    <nav class="navbar">
      <a href="..\landing\main.php">accueil</a>
      <a id="btn" href="..\login\Login.php">
        <button id="btn" type="button" class="loginBtn">Login</button>
      </a>
    </nav>
  </header>

  <div class="cont-signup">
    <div class="box">
      <div class="textLogin">
        <h2>Sign Up</h2>
        <form id="signupForm" method="POST" enctype="multipart/form-data">
          
          <!-- Nom -->
          <div class="nom">
            <input type="text" name="nom" id="nom" placeholder="Enter your name" required>
          </div>

          <!-- Surname -->
          <div class="prenom">
            <input type="text" name="prenom" id="prenom" placeholder="Enter your surname" required>
          </div>

          <!-- telephone -->
          <div class="tel">
           <input type="tel" name="tel" id="tel" placeholder="Enter your telephone number" required>
          </div>

          <!-- Email -->
          <div class="Email">
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
          </div>
          <!-- Password -->
          <div class="Password">
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
          </div>

          <!-- Submit -->
          <div class="Button">
            <button type="submit">Sign Up</button>
          </div>

          <div class="txt">
            <p>Already have an account? <a href="..\login\Login.php" class="Login">Login</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>