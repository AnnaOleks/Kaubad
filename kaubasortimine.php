<?php
require("abifunktsioonid.php");
if(isSet($_REQUEST["sort"])){
    $kaubad=kysiKaupadeAndmed($_REQUEST["sort"]);
} else {
    $kaubad=kysiKaupadeAndmed();
}
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
<table>
    <tr>
    <tr>
        <th><a href="kaubasortimine.php?sort=nimetus">Nimetus</a></th>
        <th><a href="kaubasortimine.php?sort=grupinimi">Kaubagrupp</a></th>
        <th><a href="kaubasortimine.php?sort=hind">Hind</a></th>
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