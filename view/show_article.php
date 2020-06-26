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
    </header>
    <?php
      require_once("../controller/article_controller.php");
      require_once("../model/article_model.php");
      $article_id = $_GET['id'];
      if(!empty($_POST['comment_post'])){
        comment_post();
      }elseif(!empty($_POST['reply_post'])){
        reply_post();
      }elseif(!empty($_POST['update_article'])){
        article_update();
      }elseif(!empty($_POST['comment_update'])){
        comment_update();
      }elseif(!empty($_POST['reply_update'])){
        reply_update();
      }elseif(!empty($_GET['comment_delete'])) {
        comment_delete();
      }elseif(!empty($_GET['reply_delete'])) {
        reply_delete();
      }
    ?> 
    <main>
      <div class="article_content">
        <h3>記事詳細</h3>
        <?php
          show_article();
          if($_SESSION["user_id"] == $row["user_id"]){
            echo "<a href='index.php?article_delete=" . $article_id ."' class='menu_botton' onclick='return delete_check()'>削除/</a>";
            echo "<a class='acdn-button menu_botton'>編集</a>";
          }
        ?>
        <div class="acdn-target article_up_form">
          <form action="show_article.php?id=<?php echo $article_id; ?>" method="post" enctype="multipart/form-data">
            <div class="modal js-modal">
              <div class="modal__bg js-modal-close"></div>
              <div class="modal__content">
                  <p>以下の内容で登録しますか？</p>
                  <input type="submit" value="YES" name="update_article">
                  <input type="button" class="js-modal-close" value="NO">
                  <p id="title_che"></p>
                  <p id="article_che"></p>
                  <p id="image_che"></p>
              </div>
            </div>
            <h2>記事の編集</h2>
            <p>タイトル：<br><input id="title" type="text" name="title" value='<?php echo $row["title"];?>'></p>
            <p>本文：<br><textarea id="article" name="article"><?php echo $row["article"];?></textarea></p>
            <p><input id="image" type="file" name="image" size="30"></p>
            <p>
              <input type="button" value="確認する" onclick="return check_article()"><br>
              <font id="all_msg" color="red"></font>
            </p>
          </form>
        </div>
        <p><strong><?php echo $row["title"];?></strong></p>
        <span>投稿者：<?php echo $row2['account'];?></span>
        <span>投稿日時：<?php echo $row["created_at"];?></span>
        <?php if(!($row["image"] == null)){ echo "<p><img src='../img/" . $row["image"] . "' alt='画像'></p>";}?>
        <p><?php echo $row["article"];?></p>
      </div>
      <div>
        <form action="show_article.php?id=<?php echo $article_id; ?>" method="post">
          <div class="comment_form">
            <span>コメント</span>
            <input id='comment' type='text' name='comment'>
            <input type="submit" name="comment_post" value="コメント" onclick="return comment_check()">
          </div>
        </form>
        <div>
          <?php comment_list(); ?>
        </div>
      </div>
    </main>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="javascript/validation.js"></script>
    <script type="text/javascript" src="javascript/article.js"></script>
  </body>
</html>