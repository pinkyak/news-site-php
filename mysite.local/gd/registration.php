<?php 
// require_once "noise-picture.php";
session_start();
$captchaText = $_SESSION['captcha'];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $userAnswer = $_POST["answer"];
}
$resultString = implode('',$captchaText);

?>
<!DOCTYPE HTML>
<html>

<head>
  <meta charset="utf-8" />
  <title>Регистрация</title>
</head>

<body>
  <h1>Регистрация</h1>
  <form action="" method="post">
    <div>
      <img src="noise-picture.php">
    </div>
    <div>
      <label>Введите строку</label>
      <input type="text" name="answer" size="6">
    </div>
    <input type="submit" value="Подтвердить">
  </form>
  <?php 
  if($userAnswer === $resultString){
    echo 'Molodec all is good';
  }else{
    echo 'Даннные Captcha веденны неаправильно';
  }
  unset($_SESSION['captcha']);
  ?>
</body>

</html>