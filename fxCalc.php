<!DOCTYPE html>
<!--
Author: Jordan Rifaey
Date:   started on 3/4/2019
Project: Midterm
-->
<?php
require_once("FxDataModel.php");
if (!isset($_SESSION)) {
    session_start();
}
if (!array_key_exists("username", $_SESSION)) {
    include("index.php");
    exit();
}
//if (array_key_exists(FxDataModel::SESSION_CLASS_KEY, $_SESSION)) {
if (!isset($_SESSION)) {
    //This is an existing session so unserialize FxDataModel object from superglobal session array.
    $dm = unserialize($_SESSION[FxDataModel::SESSION_CLASS_KEY]);
} else {
    //This is a new session so instantiate new FxDataModel object, serialize it, then store it in superglobal session array.
    $dm = new FxDataModel();
    $_SESSION[FxDataModel::SESSION_CLASS_KEY] = serialize($dm);
}

$FxCurrencies = $dm->getFxCurrencies();
$len = sizeof($FxCurrencies);
$iniArray = $dm->getIniArray();

if (array_key_exists($iniArray[FxDataModel::SRC_AMT_KEY], $_POST) && is_numeric($_POST[$iniArray[FxDataModel::SRC_AMT_KEY]])) {
    $fromVal = $_POST[$iniArray[FxDataModel::SRC_AMT_KEY]];
    $fromFX = $_POST[$iniArray[FxDataModel::SRC_CUCY_KEY]];
    $toFX = $_POST[$iniArray[FxDataModel::DST_CUCY_KEY]];
    $toVal = $dm->getFxRate($fromFX, $toFX) * $fromVal;
} else {
    $fromVal = "";
    $fromFX = $FxCurrencies[0];
    $toFX = $FxCurrencies[0];
    $toVal = "";
}
?>

<html>
    <body style="text-align:center">
        <header>
            <h1>Money Banks F/X Calculator</h1>
            <hr>
            <br>
            <h2>Welcome <?php echo $_SESSION["username"] ?></h2>
            <br>
        </header>
        <form name="fxCalc" action="fxCalc.php" method="post">
            <select name="<?php echo $iniArray[FxDataModel::SRC_CUCY_KEY] ?>">
                <?php
                for ($i = 0; $i < $len; $i++) {
                    echo "\t\t\t\t<option value=\"$FxCurrencies[$i]\"";
                    if ($fromFX === $FxCurrencies[$i]) {
                        echo " selected";
                    }
                    echo ">$FxCurrencies[$i]</option>\n";
                }
                ?>
            </select>
            <input type="text" name="<?php echo $iniArray[FxDataModel::SRC_AMT_KEY] ?>" value="<?php echo $fromVal ?>">
            <select name="<?php echo $iniArray[FxDataModel::DST_CUCY_KEY] ?>">
                <?php
                for ($i = 0; $i < $len; $i++) {
                    echo "\t\t\t\t<option value=\"$FxCurrencies[$i]\"";
                    if ($toFX === $FxCurrencies[$i]) {
                        echo " selected";
                    }
                    echo ">$FxCurrencies[$i]</option>\n";
                }
                ?>
            </select>
            <input name="<?php echo $iniArray[FxDataModel::DST_AMT_KEY] ?>" type="text" value ="<?php echo $toVal ?>" disabled>
            <br>
            <br>
            <button type="submit">Convert</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>