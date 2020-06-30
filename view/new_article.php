<!DOCTYPE html>
<html>
  <head>
    <title>掲示板</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/articles.css">
  </head>
  <body>
    <header>
      <a href="../index.php" class="top_icon">BOARD</a>
      <a href="new_article.php">記事の投稿</a>
      <a href="user.php">ユーザー登録の編集</a>
      <a href="../index.php?btn_logout=ログアウト">ログアウト</a>
      <div id="nav-toggle">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </header>
    <div id="gloval-nav">
      <ul>
        <li><a href="new_article.php">記事の投稿</a></li>
        <li><a href="user.php">ユーザー登録の編集</a></li>
        <li><a href="index.php?btn_logout=ログアウト">ログアウト</a></li>
      </ul>
    </div>
    <main>
      <?php
        require_once("../controller/article_controller.php");
        require_once("../model/article_model.php"); 
      ?>
      <form action="../index.php" method="post" enctype="multipart/form-data">
        <div class="modal js-modal">
          <div class="modal__bg js-modal-close"></div>
          <div class="modal__content">
              <p>以下の内容で登録しますか？</p>
              <input type="submit" value="YES" name="new_article">
              <input type="button" class="js-modal-close" value="NO">
              <p id="title_che"></p>
              <p id="article_che"></p>
              <p id="image_che"></p>
          </div>
        </div>
        <div class="article_form">
          <h2>新規投稿</h2>
          <p>タイトル：<br><input id="title" type="text" name="title"></p>
          <p>本文：<br><textarea id="article" name="article"></textarea></p>
          <p><input id="image" type="file" name="image" size="30"></p>
          <p>
            <input type="button" value="確認する" onclick="return check_article()">
            <br>
            <font id="all_msg" color="red"></font>
          </p>
        </div>
      </form>
    </main>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="javascript/validation.js"></script>
    <script type="text/javascript" src="javascript/index.js"></script>
  </body>
</html>