<?php
//fetch database connection and encryption function
require_once "db.php";
require_once "encryption.php";

session_start();
//clear messages when opened the page
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get from inpts
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    try {
        $db = new Database();
        $conn = $db->getConnection();

        //does the user exist?
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //password check
        if ($user && password_verify($password, $user["password_hash"])) {

            //AES decrypt
            $decryptedKey = decryptAES($user["encryption_key"], $password);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["aes_key"] = $decryptedKey;

            //if logined go to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Wrong username or pasword.";
        }
    } catch (PDOException $e) {
        $message = "error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
    <h2>Login</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <p><?php echo $message; ?></p>
</body>

</html>