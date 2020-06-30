<?php
  require_once("../../controller/registration_controller.php");
  require_once("../../model/registration_model.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>メール登録画面</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/entry.css">
  </head>
  <body>
    <header class="in_icon">BOARD</header>
    <form action="registration_mail_check.php" method="post">
      <div class="modal js-modal">
        <div class="modal__bg js-modal-close"></div>
        <div class="modal__content">
            <p>以下の内容で登録しますか？</p>
            <input type="submit" value="YES">
            <input type="button" class="js-modal-close" value="NO">
            <p id="mail_che"></p>
        </div>
      </div>
      <div class="mail_form">
        <h2>メール仮登録画面</h2>
        <p>メールアドレス：<br><input id="mail" type="text" name="mail" onBlur="checkmail()"></p>
        <p>
          <input type="hidden" name="token" value="<?=$token?>">
          <input type="button" class='form_btn' value="登録する" onclick="return checkbutton()">
          <font id="all_msg" color="red">
        </p>
      </div>
    </form>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="../javascript/validation.js"></script>
  </body>
</html>