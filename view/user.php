<!DOCTYPE html>
<html>
  <head>
    <title>掲示板</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/users.css">
  </head>
  <body>
    <header>
      <a href="../index.php" class="top_icon">BOARD</a>
      <a href="new_article.php">記事の投稿</a>
      <a href="user.php">ユーザー登録の編集</a>
      <a href="../index.php?btn_logout=ログアウト">ログアウト</a>
    </header>
    <?php
      require_once("../controller/user_controller.php");
      require_once("../model/user_model.php");
      $user_id = $_SESSION["user_id"];
      require_once("../commons/code.php");
      user_data();
      if(!empty($_POST['update_user'])){
        user_update();
      }elseif(!empty($_GET['myarticle_ex'])) {
        myarticle_ex();
      }
    ?>
    <main>
      <form action="user.php" method="post">
        <div class="modal js-modal">
          <div class="modal__bg js-modal-close"></div>
          <div class="modal__content">
              <p>以下の内容で登録しますか？</p>
              <input type="submit" value="YES" name="update_user">
              <input type="button" class="js-modal-close" value="NO">
              <p id="mail_che"></p>
              <p id="account_che"></p>
              <p id="password_che"></p>
          </div>
        </div>
        <div class="user_form">
          <h2>ユーザー登録の編集</h2>
          <p>メールアドレス：<br><?php echo "<input id='mail' type='text' name='mail' onBlur='checkmail()'". 'value="' . $mail_data . '">'; ?></p>
          <p>アカウント名：<br><?php echo "<input id='account' type='text' name='account' onBlur='check_account()'". 'value="' . $account_data . '">'; ?></p>
          <p>パスワード：<br><input id="password" type="text" name="password" onblur="check_password()"></p>
          <p>
            <input type="button" value="確認する" onclick="return check_user()">
            <font id="all_msg" color="red"></font>
          </p>
        </div>
      </form>
      <p><a href="user.php?myarticle_ex=<?php echo $user_id; ?>">自分の投稿の一覧をCSVファイルで抽出する</a></p>
    </main>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="javascript/validation.js"></script>
  </body>
</html>