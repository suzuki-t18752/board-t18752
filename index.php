<!DOCTYPE html>
<html>
  <head>
    <title>掲示板</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <?php
      // require_once("../controller/index_controller.php");
      // require_once("../model/index_model.php");
      if(!empty($_POST['new_article'])){
        new_article();
      }
      if(!empty($_GET['btn_logout'])) {
        log_out();
      }
      if(!empty($_GET['article_delete'])) {
        article_delete();
      }
    ?>
    <?php 
      // ログイン前とログイン後の画面の変化
      if(!isset($_SESSION["user_id"])) {
        require_once("log_in.php");
      }else{
        require_once("log_in_success.php");
      }
    ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src=""></script>
  </body>
</html>