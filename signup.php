<?php
include('conf.php');
session_start();
global $yhendus;
$error = "";
$success = "";

if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    $sool = "cool";
    $krypt = crypt($pass, $sool);

    // Проверим, есть ли уже такой пользователь
    $paring = $yhendus->prepare("SELECT kasutaja FROM kasutajad WHERE kasutaja=?");
    $paring->bind_param("s", $login);
    $paring->execute();
    $paring->store_result();

    if ($paring->num_rows > 0) {
        $error = "Kasutaja on juba olemas!";
    } else {
        $paring->close();
        $onadmin = 0;
        $paring = $yhendus->prepare("INSERT INTO kasutajad (kasutaja, parool, onadmin) VALUES (?, ?, ?)");
        $paring->bind_param("ssi", $login, $krypt, $onadmin);
        if ($paring->execute()) {
            $success = "Kasutaja on registreeritud edukalt!";
        } else {
            $error = " Viga registreerimisel.";
        }
    }

    $paring->close();
    $yhendus->close();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Registreerimine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include("header.php");
?>
<br>
<h2>Registreeri</h2>
<br>
<?php if ($error): ?>
    <p style="color: #40bcc0; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: #40bcc0; font-weight: bold;"><?= $success ?></p>
<?php endif; ?>
    <form action="signup.php" method="post">
        <label for="login">Kasutajanimi:</label>
        <input type="text" name="login" placeholder="Kasutajanimi">
        <br>
        <br>
        <label for="parool">Parool:</label>
        <input type="password" name="pass" placeholder="********">
        <br>
        <br>
        <input type="submit" value="Registreeri">
    </form>

