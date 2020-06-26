<?php
  require_once("../../controller/registration_controller.php");
  require_once("../../model/registration_model.php");
  $errors = array();
  //メールアドレスの復号化
  require_once("../../commons/mail_decode.php");
  data_get();
?>
 
<!DOCTYPE html>
<html>
  <head>
    <title>会員登録画面</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/entry.css">
  </head>
  <body>
    <header class="in_icon">BOARD</header>
    <?php if (count($errors) === 0): ?>
    <form action="registration_insert.php" method="post">
      <div class="modal js-modal">
        <div class="modal__bg js-modal-close"></div>
        <div class="modal__content">
            <p>以下の内容で登録しますか？</p>
            <input type="submit" value="YES">
            <input type="button" class="js-modal-close" value="NO">
            <p><?php echo $decrypted; ?></p>
            <p id="account_che"></p>
            <p id="password_che"></p>
        </div>
      </div>
      <div class="insert_form">
      <h2>会員登録画面</h2>
        <p>メールアドレス：<br><?php echo $decrypted; ?></p>
        <p>アカウント名：<br><input id="account" type="text" name="account" onblur="check_account()"></p>
        <p>パスワード：<br><input id="password" type="text" name="password" onblur="check_password()"></p>
        <p>
          <input type="hidden" name="token" value="<?=$token?>">
          <input type="button" value="確認する" onclick="return account_check()">
          <br>
          <font id="all_msg" color="red">
        </p>
      </div>
    </form>
    <?php elseif(count($errors) > 0): ?>
    <?php
    foreach($errors as $value){
      echo "<p>".$value."</p>";
    }
    ?>
    <?php endif; ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="../javascript/validation.js"></script>
  </body>
</html>