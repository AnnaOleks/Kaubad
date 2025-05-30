<?php
require ('conf.php');
session_start();
require("abifunktsioonid.php");
if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = false;
}
if (!isset($_SESSION['opilane'])) {
    $_SESSION['opilane'] = false;
}

// ei luba !empty ja trim - tühiku lisamine
function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'];
}
function isOpilane() {
    return isset($_SESSION['opilane']) && $_SESSION['opilane'];
}
// Добавление группы (только для админа)
if (isset($_POST["grupilisamine"]) && !empty(trim($_POST["uuegrupinimi"])) && isAdmin()) {
    if (grupinimiKontroll(trim($_POST["uuegrupinimi"])) == 0) {
        lisaGrupp(trim($_POST["uuegrupinimi"]));
        header("Location: kaubahaldus.php");
        exit();
    }
}

// Добавление товара (только для админа)
if (isset($_POST["kaubalisamine"]) && !empty(trim($_POST["nimetus"])) && isAdmin()) {
    lisaKaup(trim($_POST["nimetus"]), $_POST["kaubagrupiid"], $_POST["hind"]);
    header("Location: kaubahaldus.php");
    exit();
}

// Удаление товара (только для админа)
if (isset($_GET["kustutusid"]) && isAdmin()) {
    kustutaKaup($_GET["kustutusid"]);
    header("Location: kaubahaldus.php");
    exit();
}

// Изменение товара (только для админа)
if (isset($_POST["muutmine"]) && isAdmin()) {
    muudaKaup($_POST["muudetudid"], $_POST["nimetus"], $_POST["kaubagrupiid"], $_POST["hind"]);
    header("Location: kaubahaldus.php");
    exit();
}

$kaubad = kysiKaupadeAndmed();
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
<h2>Kaubad | Kaubagrupid</h2>
<br>
<div class="container">
    <div class="flexcontainer">
        <div id="tabel">
            <h4>Kaupade loetelu</h4>
                <table>
                    <tr>
                        <th>Haldus</th>
                        <th>Nimetus</th>
                        <th>Kaubagrupp</th>
                        <th>Hind</th>
                    </tr>

                    <?php foreach ($kaubad as $kaup): ?>
                        <?php if (isset($_GET["muutmisid"]) && intval($_GET["muutmisid"]) == $kaup->id && isAdmin()): ?>
                            <tr>
                                <td colspan="4">
                                    <form action="kaubahaldus.php" method="post" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                                        <input type="hidden" name="muudetudid" value="<?= $kaup->id ?>">

                                        <input type="text" name="nimetus" value="<?= htmlspecialchars($kaup->nimetus) ?>" required>

                                        <?= looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupiid", $kaup->kaubagrupiid); ?>

                                        <input type="text" name="hind" value="<?= htmlspecialchars($kaup->hind) ?>" required>

                                        <input type="submit" name="muutmine" value="Muuda">
                                        <input type="submit" name="katkestus" value="Katkesta" onclick="window.location='kaubahaldus.php'; return false;">
                                    </form>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <?php if (isAdmin()): ?>
                                        <a href="kaubahaldus.php?kustutusid=<?= $kaup->id ?>" onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                                        <a href="kaubahaldus.php?muutmisid=<?= $kaup->id ?>">m</a>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($kaup->nimetus) ?></td>
                                <td><?= htmlspecialchars($kaup->grupinimi) ?></td>
                                <td><?= htmlspecialchars($kaup->hind) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
        <div id="forms">
        <?php if (isset($_SESSION['kasutaja'])): ?>
        <br>
        <h4>Tere, <?= htmlspecialchars($_SESSION['kasutaja']) ?>!</h4>
        <form action="logout.php" method="post">
            <input type="submit" name="logout" value="Logi välja">
        </form>
        <br>
    <?php if (isAdmin()): ?>
        <h4>Kauba lisamine</h4>
        <form action="kaubahaldus.php" method="post">
            <label for="username">Nimetus:</label>
            <input type="text" name="nimetus" required>
            <br>
            <br>
            <?= looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupiid"); ?>
            <br>
            <br>
            <label for="hind">Hind:</label>
            <input type="text" name="hind" required>
            <br>
            <br>
            <input type="submit" name="kaubalisamine" value="Lisa kaup">
        </form>
        <br>
        <h4>Grupi lisamine</h4>
        <form action="kaubahaldus.php" method="post">
            <label for="uuegrupinimi">Gruppi nimetus:</label>
            <input type="text" name="uuegrupinimi" required>
            <br>
            <br>
            <input type="submit" name="grupilisamine" value="Lisa grupp">
        </form>
        <?php
        if (isset($_POST["grupilisamine"]) && grupinimiKontroll(trim($_POST["uuegrupinimi"])) > 0) {
            echo "<p style='color:red;'>Sisestatud grupinimi on juba olemas!</p>";
        }
        ?>
        <?php endif; ?>
        </div>
    </div>
</div>



    <!-- Список товаров -->

<?php else: ?>
    <p>Palun logi sisse.</p>
<?php endif; ?>
</body>
</html>

