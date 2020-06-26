<header class="in_icon">BOARD</header>
<main class="log_in_form">
  <?php
    if(isset($_POST["login"])) {
      log_in();
    }
  ?>
  <form action="index.php" method="POST">
    <p>ログインID：<br><input type="text" name="user_id"></p>
    <p>パスワード：<br><input type="password" name="password"></p>
    <input type="submit" name="login" value="ログイン">
  </form>
  <p><a href="view/entry/registration_mail_form.php">はじめての方はこちらから登録を</a></p>
</main>
