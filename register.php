<?php
require_once "db.php";
require_once "encryption.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $userKey = bin2hex(random_bytes(32));

    $encryptedKey = encryptAES($userKey, $password);

    try {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, encryption_key) VALUES (?, ?, ?)");
        $stmt->execute([$username, $passwordHash, $encryptedKey]);

        $message = "Registered";
    } catch (PDOException $e) {
        $message = "error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
    <h2>Register</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Register</button>
    </form>

    <p><?php echo $message; ?></p>
</body>

</html>