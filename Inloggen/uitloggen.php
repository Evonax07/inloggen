<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    session_unset();
    session_destroy();
    $confirmationMessage = "Uitloggen succesvol. Tot ziens, $username!";
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uitloggen</title>
</head>
<body>
    <h2>Uitloggen</h2>
    <?php if (isset($confirmationMessage)) : ?>
        <div style="color: green;">
            <?php echo $confirmationMessage; ?>
        </div>
    <?php endif; ?>
    <p>U wordt automatisch doorgestuurd naar de <a href="index.php">startpagina</a>.</p>
    <script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 3000);
    </script>
</body>
</html>