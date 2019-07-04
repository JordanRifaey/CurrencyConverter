<!DOCTYPE html>
<?php
require_once("LoginDataModel.php");
if (!isset($_SESSION)) {
    session_start();
}
$lm = new LoginDataModel();
$HTMLnames = $lm->getIniLoginAttributes();
$error = false;
if (array_key_exists($HTMLnames[$lm::HTML_USERNAME_KEY], $_POST) && array_key_exists($HTMLnames[$lm::HTML_PASSWORD_KEY], $_POST)) {

    $username = $_POST[$HTMLnames[$lm::HTML_USERNAME_KEY]];
    $password = $_POST[$HTMLnames[$lm::HTML_PASSWORD_KEY]];
    if ($lm->ValidateUser($username, $password) === true) {
        $_SESSION[$HTMLnames[$lm::HTML_USERNAME_KEY]] = $username;
        $lm = null;
        include("fxCalc.php");
        exit();
    } else {
        $error = true;
    }
} else {
    $username = "";
    $password = "";
}
?>

<html>
<body style="text-align:center">
<?php if ($error) echo
'<script>
                alert("Invalid username or password. Please try again.");
        </script>'
?>
<script>
    alert("Use 'user' for username and 'pw' for password to test my app! You can check out the source on my GitHub ");
</script>
<header>
    <h1><i>Money Banks Login</i></h1>
    <hr>
    <br>
</header>
<form name="<?php echo $HTMLnames[LoginDataModel::formHTMLNameAttribute] ?>" action="index.php" method="post">
    <label>Username:</label>
    <input type="text" name="<?php echo $HTMLnames[LoginDataModel::HTML_USERNAME_KEY] ?>"
           value="<?php echo $username ?>"/>
    <br>
    <br>
    <label>Password:</label>
    <input type="password" name="<?php echo $HTMLnames[LoginDataModel::HTML_PASSWORD_KEY] ?>"
           value="<?php echo $password ?>"/>
    <br>
    <br>
    <button type="submit">Login</button>
    <button type="reset">Reset</button>
</form>
</body>
</html>