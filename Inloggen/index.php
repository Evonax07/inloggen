<?php
session_start();

include_once 'config.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['identifier'])) {
        $errors[] = "Gebruikersnaam of e-mail is verplicht.";
    } else {
        $identifier = $_POST['identifier'];
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors[] = "Wachtwoord is verplicht.";
    } else {
        $user->password = $_POST['password'];
    }

    if (empty($errors)) {
        $stmt = $user->login($identifier);

        if ($stmt->rowCount() > 0) {
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $userRow['password'];

            if (password_verify($user->password, $hashed_password)) {
                $_SESSION['username'] = $userRow['username'];
                header("Location: welkom.php");
            } else {
                $errors[] = "Ongeldige inloggegevens.";
            }
        } else {
            $errors[] = "Ongeldige inloggegevens.";
        }
    }
}

function checkPasswordStrength($password) {
    $minLength = 8;
    $minUpperCase = 1;
    $minLowerCase = 1;
    $minDigits = 1;

    $uppercaseCount = preg_match_all('/[A-Z]/', $password);
    $lowercaseCount = preg_match_all('/[a-z]/', $password);
    $digitCount = preg_match_all('/\d/', $password);

    $strength = 0;

    if (strlen($password) >= $minLength) {
        $strength++;
    }

    if ($uppercaseCount >= $minUpperCase) {
        $strength++;
    }

    if ($lowercaseCount >= $minLowerCase) {
        $strength++;
    }

    if ($digitCount >= $minDigits) {
        $strength++;
    }

    return $strength;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <style>
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h2>Inloggen</h2>
    
    <?php if (!empty($errors)) : ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="identifier">Gebruikersnaam of E-mail:</label>
        <input type="text" name="identifier" required><br>

        <label for="password">Wachtwoord:</label>
        <input type="password" name="password" id="password" required>
        <div class="password-strength">
            Sterkte: <?php echo checkPasswordStrength($_POST['password']); ?>/4
        </div>
        
        <input type="submit" value="Inloggen">
    </form>
</body>
</html>
