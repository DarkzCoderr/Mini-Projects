<?php
session_start();

$host = "localhost";
$user = "root";  
$pass = "";
$dbname = "user_auth";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "signup") {
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            die("All fields are required!");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format!");
        }
        if ($password !== $confirm_password) {
            die("Passwords do not match!");
        }
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Signup successful! You can now login.";
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
    } 

    elseif ($action == "login") {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        if (empty($email) || empty($password)) {
            die("Email and password are required!");
        }
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $id;
                echo "Login successful! Redirecting...";
                header("Refresh:2; url=dashboard.php");
                exit();
            } else {
                echo "Invalid credentials!";
            }
        } else {
            echo "User not found!";
        }
        $stmt->close();
    }
}
$conn->close();
?>
