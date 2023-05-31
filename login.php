<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (!empty($_SESSION['login'])) {
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $response = array();
  $errors = array();
  $errors['login1'] = !empty($_COOKIE['login_error1']);
  $errors['login2'] = !empty($_COOKIE['login_error2']);
  $errors['password'] = !empty($_COOKIE['password_error']);

  if (!empty($errors['login1'])) {
    setcookie('login_error1', '', 100000);
    $response['login1'] = '<p class="msg">Вы не заполнили логин</p>';
  }
  if (!empty($errors['login2'])) {
    setcookie('login_error2', '', 100000);
    $response['login2'] = '<p class="msg">Такого аккаунта не существует</p>';
  }
  if (!empty($errors['password'])) {
    setcookie('password_error', '', 100000);
    $response['password'] = '<p class="msg">Вы не заполнили пароль</p>';
  }

  include('loginform.php');
}
else {
  $login = $_POST['login'];
  $password = $_POST['password'];

  if (empty($login)) {
    setcookie('login_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  if (empty($password)) {
    setcookie('password_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if ($errors) {
    header('Location: login.php');
    exit();
  }

 require 'config.php';
  $stmt = $db->prepare('SELECT user_id FROM users WHERE (login = ?) AND (password = ?) ');
  $stmt->execute([$login, md5($password)]);

  if ($stmt->rowCount() > 0) {
    $_SESSION['login'] = $_POST['login'];
    $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $_SESSION['uid'] = $stmt->fetchColumn();
    header('Location: ./');
  } else {
    setcookie('login_error2', '1', time() + 24 * 60 * 60);
    header('Location: login.php');
    exit();
  }
}
