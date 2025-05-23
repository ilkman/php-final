<?php
//fetch database connection, encryption function, session and password generator
session_start();
require_once "db.php";
require_once "encryption.php";
require_once "passwordgenerator.php";

//not logged in? send back to login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

//clear messages and pass field
$message = "";
$passwordFieldValue = "";

//clear the pass generator fields
$length = $lower = $upper = $numbers = $special = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["generate"])) {

        //get from imputs
        $length = (int)$_POST["length"];
        $lower = (int)$_POST["lower"];
        $upper = (int)$_POST["upper"];
        $numbers = (int)$_POST["numbers"];
        $special = (int)$_POST["special"];

        //generate pass using the class
        $generator = new PasswordGenerator();
        $passwordFieldValue = $generator->generate($length, $lower, $upper, $numbers, $special);

        $_SESSION["generated_password"] = $passwordFieldValue;
    } elseif (isset($_POST["save"])) {
        //get from inputs
        $service = trim($_POST["service"]);
        $passwordFieldValue = $_POST["password"];

        //encrypt the password using AES
        $encryptedPassword = encryptAES($passwordFieldValue, $_SESSION["aes_key"]);

        try {
            $db = new Database();
            $conn = $db->getConnection();

            //insert data to database
            $stmt = $conn->prepare("INSERT INTO passwords (user_id, service_name, encrypted_password) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION["user_id"], $service, $encryptedPassword]);

            $message = "Password saved";
            $passwordFieldValue = "";
            unset($_SESSION["generated_password"]);
        } catch (PDOException $e) {
            $message = "error: " . $e->getMessage();
        }
    } elseif (isset($_SESSION["generated_password"])) {
        $passwordFieldValue = $_SESSION["generated_password"];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h2>Logged in <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>

    <form method="POST">
        <h3>Generate Password</h3>
        Length: <input type="number" name="length" value="<?php echo htmlspecialchars($length); ?>" required><br>
        Lowercase: <input type="number" name="lower" value="<?php echo htmlspecialchars($lower); ?>" required><br>
        Uppercase: <input type="number" name="upper" value="<?php echo htmlspecialchars($upper); ?>" required><br>
        Numbers: <input type="number" name="numbers" value="<?php echo htmlspecialchars($numbers); ?>" required><br>
        Special: <input type="number" name="special" value="<?php echo htmlspecialchars($special); ?>" required><br><br>

        <button type="submit" name="generate">Generate Password</button>
    </form>

    <br>
    <hr><br>

    <form method="POST">
        <h3>Save Password</h3>
        Service: <input type="text" name="service" required><br><br>
        Password: <input type="text" name="password" value="<?php echo htmlspecialchars($passwordFieldValue); ?>"
            required><br><br>
        <button type="submit" name="save">Save Password</button>
    </form>

    <p><?php echo $message; ?></p>
</body>

</html>