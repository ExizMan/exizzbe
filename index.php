<?php

header('Content-Type: text/html; charset=UTF-8');
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $response = array();
  $messages1 = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', time() + 3600);
    print('Thanx ');
    if (!empty($_COOKIE['password'])) {
      $messages1['login'] = sprintf('<div class="login">Логин: <strong>%s</strong><br>
        Пароль: <strong>%s</strong><br>Войдите в аккаунт с этими данными,<br>чтобы изменить введёные значения формы</div>',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
    setcookie('login', '', 100000);
    setcookie('password', '', 100000);
  
  }
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['checkboxContract'] = !empty($_COOKIE['cont_error']);

  if ($errors['name']) {
    setcookie('name_error', '', time() + 3600);
    $response['name'] = '<p class="msg">Заполните имя</p>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', time() + 3600);
    $response['email'] = '<p class="msg">Заполните email</p>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', time() + 3600);
    $response['year'] = '<p class="msg">Заполните год</p>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', time() + 3600);
    $response['gender'] = '<p class="msg">выберете пол</p>';
  }
  if ($errors['abilities']) {
    setcookie('abilities_error', '', time() + 3600);
    $response['abilities'] = '<p class="msg">Выберете способности</p>';
  }
  if ($errors['biography']) {
    setcookie('biography_error', '', time() + 3600);
    $response['biography'] = '<p class="msg">Заполните биографию на кириллице</p>';
  }
  if ($errors['checkboxContract']) {
    setcookie('cont_error', '', time() + 3600);
    $response['checkboxContract'] = '<p class="msg">Поставьте галочку</p>';
  }
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['abilities'] = empty($_COOKIE['abilities_value']) ? '' : $_COOKIE['abilities_value'];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['checkboxContract'] = empty($_COOKIE['cont_value']) ? '' : $_COOKIE['cont_value'];
  if (count(array_filter($errors)) === 0 && !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    $login = $_SESSION['login'];
    try {
      //id ,name, email, year, gender, biography
      $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
      $stmt->execute([$login]);
      $app_id = $stmt->fetchColumn();

      $stmt = $db->prepare("SELECT id, name, email, year, gender, biography FROM application WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $db->prepare("SELECT superpower_id FROM abilities WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $abilities = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

      if (!empty($dates[0]['name'])) {
        $values['name'] = $dates[0]['name'];
      }
      if (!empty($dates[0]['email'])) {
        $values['email'] = $dates[0]['email'];
      }
      if (!empty($dates[0]['year'])) {
        $values['year'] = $dates[0]['year'];
      }
      if (!empty($dates[0]['gender'])) {
        $values['gender'] = $dates[0]['gender'];
      }

      if (!empty($abilities)) {
        $values['abilities'] =  serialize($abilities);
      }
      if (!empty($dates[0]['biography'])) {
        $values['biography'] = $dates[0]['biography'];
      }

    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }

    printf('<div id="header"><p>Вход с логином %s; uid: %d</p><a href=logout.php>Выйти</a></div>', $_SESSION['login'], $_SESSION['uid']);
  }
  

  include('form.php');
  exit();
} else {
  if (isset($_POST['delcooc'])) {
    if (isset($_SERVER['HTTP_COOKIE'])) {
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach ($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time() - 1000);
        setcookie($name, '', time() - 1000, '/');
      }
    }
    header('Location: ./');
    exit();
  }



  $errors = FALSE;
  $name = $_POST['fio'];
  $year = $_POST['year'];
  $email = $_POST['emailfield'];
  $biography = $_POST['textfield'];
  $gender = $_POST['gender'];

  $checkboxContract = isset($_POST['checkR']);

  if (empty($name)) {
    setcookie('name_error', '1', time() + 3600);
    $errors = true;
  } else {
    setcookie('name_value', $name, time() + 3600);
  }

  if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
    setcookie('year_error', '1', time() + 3600);
    $errors = TRUE;
  } else {
    setcookie('year_value', $name, time() + 3600);
  }

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error', '1', time() + 3600);
    $errors = TRUE;
  } else {
    setcookie('email_value', $email, time() + 3600);
  }

  if (!isset($_POST['gender'])) {
    setcookie('gender_error', '1', time() + 3600);
    $errors = TRUE;
  } else {
    setcookie('gender_value', $gender, time() + 3600);
  }

  if (isset($_POST['gender']))
    if ($_POST['gender'] != 'm' && $_POST['gender'] != 'f') {
      setcookie('gender_error', '1', time() + 3600);
      $errors = TRUE;
    }

  if (isset($_POST["abilities"])) {
    $abilities = $_POST["abilities"];
    $filtred_abilities = array_filter(
      $abilities,
      function ($value) {
        return ($value == 1 || $value == 2 || $value == 3);
      }
    );
  }
  if (empty($abilities)) {
    setcookie('abilities_error', '1', time() + 3600);
    $errors = TRUE;

  } else {
    setcookie('abilities_value', serialize($abilities), time() + 3600);
  }

  if (empty($biography) || !preg_match('/^[\p{Cyrillic}\d\s,.!?-]+$/u', $biography)) {
    setcookie('biography_error', '1', time() + 3600);
    $errors = TRUE;
  } else {
    setcookie('biography_value', $biography, time() + 3600);
  }


  if (!isset($_POST['checkR'])) {
    setcookie('cont_error', '1', time() + 3600);
    $errors = true;
  } else {
    setcookie('cont_value', $checkboxContract, time() + 3600);
  }

  if ($errors) {
    header('Location: index.php');
    exit();
  } else {
    setcookie('name_error', '', time() + 3600);
    setcookie('year_error', '', time() + 3600);
    setcookie('email_error', '', time() + 3600);
    setcookie('gender_error', '', time() + 3600);
    setcookie('abilities_error', '', time() + 3600);
    setcookie('biography_error', '', time() + 3600);
    setcookie('cont_error', '', time() + 3600);
  }



  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    $login = $_SESSION['login'];
    try {
      $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
      $stmt->execute([$login]);
      $app_id = $stmt->fetchColumn();

      $stmt = $db->prepare("UPDATE application SET name = ?, email = ?, year = ?, gender = ?, biography = ?
        WHERE application_id = ?");
      $stmt->execute([$name, $email, $year, $gender, $biography, $app_id]);

      $stmt = $db->prepare("SELECT superpower_id FROM abilities WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $abil = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

      if (array_diff($abil, $abilities)) {
        $stmt = $db->prepare("DELETE FROM abilities WHERE application_id = ?");
        $stmt->execute([$app_id]);

        $stmt = $db->prepare("INSERT INTO abilities (application_id, superpower_id) VALUES (?, ?)");
        foreach ($abilities as $superpower_id) {
          $stmt->execute([$app_id, $superpower_id]);
        }
      }

    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }

  }
  else {
    $login = 'user' . rand(1, 1000);
    $password = rand(1, 100);
    setcookie('login', $login);
    setcookie('password', $password);
  try {
    $stmt = $db->prepare("INSERT INTO application (id ,name, email, year, gender, biography) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$db->lastInsertId(), $name, $email, $year, $gender, $biography]);
    $application_id = $db->lastInsertId();
    $stmt = $db->prepare("INSERT INTO abilities (application_id, superpower_id) VALUES (?, ?)");
    foreach ($abilities as $superpower_id) {
      $stmt->execute([$application_id, $superpower_id]);
    }
    $stmt = $db->prepare("INSERT INTO users (application_id, login, password) VALUES (?, ?, ?)");
    $stmt->execute([$application_id, $login, md5($password)]);
  } catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
  }
  }
  setcookie('save', '1');
  header('Location: ./');
}