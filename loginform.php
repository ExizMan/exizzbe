<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <link rel="stylesheet" href="login.css">
  <title>Login</title>
</head>
<body>
<div class="msgbox">
  <?php 
    if (!empty($messages)) {
      foreach ($messages as $message) {
        print($message);
      }
    }
  ?>
</div>
<div class="login">
  <form action="" method="post">
    <h1>Вход</h1>
    <p <?php  if ($errors['login1'] || $errors['login2']) print 'class="error"'?>>Логин</p>
    <input name="login" class="form-content line">
    <p <?php  if ($errors['password'] || $errors['login2']) print 'class="error"'?>>Пароль</p>
    <input name="password" type="password" class="form-content line">
    <input type="submit" value="Войти" class="form-content btn">
  </form>
</div>
</body>