<?php
require ('conf.php');
function kysiKaupadeAndmed($sorttulp="nimetus", $otsisona=''){
    global $yhendus;
    $lubatudtulbad=array("nimetus", "grupinimi", "hind");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT kaubad.id, nimetus, grupinimi, hind, kaubad.kaubagrupiid
FROM kaubad, kaubagrupid
WHERE kaubad.kaubagrupiid=kaubagrupid.id
AND (nimetus LIKE '%$otsisona%' OR grupinimi LIKE '%$otsisona%')
ORDER by $sorttulp");//echo $yhendus->error;
    $kask->bind_result($id, $nimetus, $grupinimi, $hind, $kaubagrupiid);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $kaup=new stdClass();
        $kaup->id=$id;
        $kaup->nimetus=htmlspecialchars($nimetus);
        $kaup->grupinimi=htmlspecialchars($grupinimi);
        $kaup->hind=$hind;
        $kaup->kaubagrupiid = $kaubagrupiid;
        array_push($hoidla, $kaup);
    }
    return $hoidla;
}
function looRippMenyy($sqllause, $valikunimi, $valitudid=""){
    global $yhendus;
    $kask=$yhendus->prepare($sqllause);
    $kask->bind_result($id, $sisu);
    $kask->execute();
    $tulemus="<select name='$valikunimi'>";
    while($kask->fetch()){
        $lisand="";
        if($id==$valitudid){$lisand=" selected='selected'";}
        $tulemus.="<option value='$id' $lisand >$sisu</option>";
    }
    $tulemus.="</select>";
    return $tulemus;
}

function lisaGrupp($grupinimi){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO kaubagrupid (grupinimi) VALUES (?)");
    $kask->bind_param("s", $grupinimi);
    $kask->execute();
}

function lisaKaup($nimetus, $kaubagrupiid, $hind){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO kaubad (nimetus, kaubagrupiid, hind) VALUES (?, ?, ?)");
    $kask->bind_param("sid", $nimetus, $kaubagrupiid, $hind);
    $kask->execute();
}

function kustutaKaup($kauba_id){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM kaubad WHERE id=?");
    $kask->bind_param("i", $kauba_id);
    $kask->execute();
}

function muudaKaup($kauba_id, $nimetus, $kaubagrupiid, $hind){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE kaubad SET nimetus=?, kaubagrupiid=?, hind=? WHERE id=?");
    $kask->bind_param("sidi", $nimetus, $kaubagrupiid, $hind, $kauba_id);
    $kask->execute();
}
function grupinimiKontroll($grupinimi){
    global $yhendus;
    $kask=$yhendus->prepare("SELECT * FROM kaubagrupid WHERE grupinimi LIKE ?");
    $kask->bind_param("s", $grupinimi);
    if($kask->execute()){
        $kask->store_result();
        $rida=$kask->num_rows;
        return $rida;
    }
}
?>