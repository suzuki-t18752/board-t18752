<?php
  require_once("../../controller/registration_controller.php");
  require_once("../../model/registration_model.php");
  $errors = array();
  mail_get();
  if (count($errors) === 0){
    mail_insert();
  }
?>
 
<!DOCTYPE html>
<html>
  <head>
    <title>メール確認画面</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/entry.css">
  </head>
  <body>
    <header class="in_icon">BOARD</header>
    <div class="mail_view">
      <h2>メール確認画面</h2>
      <?php if (count($errors) === 0): ?>
      <p><?=$message?></p>
      <p>↓このURLが記載されたメールが届きます。</p>
      <p><a href="<?=$url?>"><?=$url?></a></p>
      <?php elseif(count($errors) > 0): ?>
      <?php
      foreach($errors as $value){
        echo "<p>".$value."</p>";
      }
      ?>
      <p><input type="button" value="戻る" onClick="history.back()"></p>
      <?php endif; ?>
    </div>
  </body>
</html>