<?php
include('conf.php');
session_start();
global $yhendus;
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $sool = 'cool';
    $krypt = crypt($pass, $sool);
    //kontrollime kas andmebaasis on selline kasutaja ja parool
    $paring = $yhendus->prepare("SELECT kasutaja, parool, onadmin FROM kasutajad WHERE kasutaja=? AND parool=?");
    $paring->bind_param('ss', $login, $krypt);
    $paring->execute();
    $paring->bind_result($kasutaja, $parool, $onadmin);
    //$valjund = mysqli_query($yhendus, $paring);
    //kui on, siis loome sessiooni ja suuname
    /*if (mysqli_num_rows($valjund)==1) {
        $_SESSION['tuvastamine'] = 'misiganes';
        header('Location: kaubahaldus.php');
    } else {
        echo "kasutaja või parool on vale";
    }*/
    if ($paring->fetch() && $parool == $krypt) {
        $_SESSION['kasutaja'] = $kasutaja;

        if ($onadmin == 1) {
            $_SESSION['admin'] = true;
            $_SESSION['opilane'] = false;
        } else {
            $_SESSION['admin'] = false;
            $_SESSION['opilane'] = true;
        }

        $paring->close();
        $yhendus->close();
        header("Location: kaubahaldus.php");
        exit();
    } else {
        echo "<p style='color:#40bcc0;'>Kasutaja või parool on vale</p>";
        $paring->close();
        $yhendus->close();
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Kaupade leht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include("header.php");
?>
<br>
<h2>Sisse logimine</h2>
<br>
<form action="" method="post">
    <label for="login">Login:</label>
    <input type="text" id="login" name="login" required>
    <br>
    <br>
    <label for="pass">Password:</label>
    <input type="password" id="pass" name="pass" required>
    <br>
    <br>
    <input type="submit" value="Logi sisse" required>
</form>
<?php
include("footer.php");
?>
</body>
</html>