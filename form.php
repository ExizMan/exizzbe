<!DOCTYPE html>

<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="initial-scale=1.0, width=device-width">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title> Task 3 </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css">
  
</head>

<body>
  
<?php

  if (!empty($messages1['login'])) {
    print($messages1['login']);
  }
  if (empty($_SESSION['login'])) {
    
      echo'<div id="header"><a href=login.php>Войти</a></div>';
    
  }
?>

  <div class="mainbox">
    <div class="headbox">
      <h4> Форма </h4>
    </div>
    <div class="contentbox">
      <form action="" method="POST">
        <label>
          Ваше имя: <?php if(isset($response['name'])) echo '<span class="rsp" title="Заполните Имя"> *</span>' ?>
          <input name="fio" value="<?php echo $values['name']; ?>"/>

        </label>
        <label>
          E-mail поле: <?php if(isset($response['email'])) echo '<span class="rsp" title="Заполните почту"> *</span>' ?>
          <input type="email" class="formAd" name="emailfield" value="<?php echo $values['email']; ?>">
        </label>
        <div class="yerr">
        <label>
          <select name="year">
            <?php
            for ($i = 2023; $i >= 1922; $i--) {
              if ($i == $values['year']) {
                printf('<option selected value="%d">%d год</option>', $i, $i);
              } else {
              printf('<option value="%d">%d год</option>', $i, $i);
              }
            }
            ?>
          </select> 
          <?php if(isset($response['gender'])) echo '<span class="rsp" title="Выберите год"> *</span>' ?>
        </label>
        </div>
        <p>
        Пол: 
        <label>
          М
          <input type="radio" class="formAd" name="gender" value="m" <?php if ($values['gender'] == 'm') {print 'checked';} ?>>
        </label>
        <label>
          Ж
          <input type="radio" class="formAd" name="gender" value="f" <?php if ($values['gender'] == 'f') {print 'checked';} ?>>
          <?php if(isset($response['gender'])) echo '<span class="rsp" title="Выберите пол"> *</span>' ?> </p>
          <label>
          Сверхспособности: <?php if(isset($response['abilities'])) echo '<span class="rsp" title="Выберите способность"> *</span>' ?>
          <li>
            <input type="checkbox" id="god" name="abilities[]" value=1 <?php if (isset($values['abilities']) && !empty($values['abilities']) && in_array(1, unserialize($values['abilities']))) {print 'checked';}?>>
            <label for="god">бессмертие</label>
          </li>
          <li>
            <input type="checkbox" id="noclip" name="abilities[]" value=2 <?php if (isset($values['abilities']) && !empty($values['abilities']) && in_array(2, unserialize($values['abilities']))) {print 'checked';}?>>
            <label for="noclip">прохождение сквозь стены</label>
          </li>
          <li>
            <input type="checkbox" id="levitation" name="abilities[]" value=3 <?php if (isset($values['abilities']) && !empty($values['abilities']) && in_array(3, unserialize($values['abilities']))) {print 'checked';}?>>
            <label for="levitation">левитация</label>
          </label>

          <label> 

            Биография: <?php if(isset($response['biography'])) echo '<span class="rsp" title="Напишите что нибудь> *</span>' ?>

            <textarea name="textfield"> <?php if (!empty($values['biography'])) {print $values['biography'];} ?> </textarea>
          </label>
          <p>
            <label for="contract"> 
              <input id="contract" type="checkbox" name="checkR" <?php if ($values['checkboxContract']) {print 'checked';} ?>>
              Ознакомлен с контрактом <?php if(isset($response['checkboxContract'])) echo '<span class="rsp" title="Дайте согласие"> *</span>' ?>
            </label>
          </p>
          <p>

            <input type="submit" value="Отправить">

          </p>
      </form>

    </div>
  </div>
            <div class="bd"> <a href="../DB"> база </a></div>
            <form action="" method="POST"><input name="delcooc" id="del" type="submit" value="Удалить куки"> </form>

            
</body>



</html>