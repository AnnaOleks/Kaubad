<?php
require("abifunktsioonid.php");
$sorttulp="nimetus";
$otsisona="";
if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}
if(isSet($_REQUEST["otsisona"])){
    $otsisona=$_REQUEST["otsisona"];
}
$kaubad=kysiKaupadeAndmed($sorttulp, $otsisona);
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include("header.php");
?>
<br>
<h2>Kaubad | Kaubagrupid</h2>
<br>
<form action="kaubaotsing.php">
    <label for="otsisona">Otsing:</label>
    <input type="text" name="otsisona" id="otsisona" placeholder="Sisesta otsingusõna">
</form>
<br>
<br>
<br>
<table>
    <tr>
        <th><a href="kaubaotsing.php?sort=nimetus">Nimetus</a></th>
        <th><a href="kaubaotsing.php?sort=grupinimi">Kaubagrupp</a></th>
        <th><a href="kaubaotsing.php?sort=hind">Hind</a></th>
    </tr>
    <?php foreach($kaubad as $kaup): ?>
        <tr>
            <td><?=$kaup->nimetus ?></td>
            <td><?=$kaup->grupinimi ?></td>
            <td><?=$kaup->hind ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
include("footer.php");
?>
</body>
</html>
