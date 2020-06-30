<?php 
  function show_article(){
    global $dbh, $article_id, $row, $row2;
    // 投稿のデーたを取得
    $stmh = $dbh->prepare("SELECT * FROM article WHERE article_id = :article_id;");
    $stmh->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $stmh->execute();
    $row = $stmh->fetch();
    $user_id = $row['user_id'];
    $stmt = $dbh->prepare("SELECT * FROM main_user WHERE user_id = :user_id;");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row2 = $stmt->fetch();
  }

  function comment_list(){
    global $dbh, $article_id, $comment_id;
    // ページネーションの為に投稿の総数を取得
    $stmn = $dbh->prepare("SELECT count(*) FROM comment WHERE article_id = :article_id AND delete_flg = 0;");
    $stmn->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $stmn->execute();
    $row = $stmn->fetchAll(PDO::FETCH_ASSOC);
    $cnt = $row[0]["count(*)"];
    $max_page = ceil($cnt / 10);
    if(!isset($_GET['page_id'])){
        $now = 1;
    }else{
        $now = $_GET['page_id'];
    }
    $start_no = ($now - 1) * 10;
    // 表示するコメントを取得
    $stmh = $dbh->prepare("SELECT * FROM comment WHERE article_id = :article_id AND delete_flg = 0 AND rep_flg = 0;");
    $stmh->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $stmh->execute();
    $data = $stmh->fetchAll(PDO::FETCH_ASSOC);
    $data = array_slice($data, $start_no, 10, true);
    foreach($data as $row){
      echo "<div class='comment_list'>" . $row['comment'];
      // コメントのユーザーを取得
      $user_id = $row['user_id'];
      $stmt = $dbh->prepare("SELECT * FROM main_user WHERE user_id = :user_id;");
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();
      while ($row2 = $stmt->fetch()) {
        echo "<br>" . $row2['account'] . "/";
      }
      echo $row['created_at'];
      $comment_id = $row['comment_id'];
      echo "<br>";
      if($row['user_id'] == $_SESSION["user_id"]){
        echo '<a class="acdn-button menu_botton link_btn">編集</a>/';
        echo '<div class="acdn-target">';
        echo 
          "<div class='comment_menu'>
            <form action='show_article.php?id=" . $article_id . "'method='post'>
              <input id='comment' type='text' name='come_up'>
              <input type='hidden' name='comment_id_post' value='" . $comment_id ."'>
              <input type='submit' class='form_btn' name='comment_update' value='更新' onclick=''>
            </form>
            <a href='show_article.php?id=" . $article_id . "&comment_delete=" . $comment_id ."' class='menu_botton delete_btn' onclick='return delete_check()'>削除</a>
          </div>"
        ;
        echo "</div>";
      }
      // コメントへの返信を表示
      reply_list();
      echo '</div></div>';
    }
    echo '<div>全件数'. $cnt. '件'. '　'; // 全データ数の表示です。
    if($now > 1){ // リンクをつけるかの判定
      echo '<a href=show_article.php?id=' . $article_id .'&page_id='. ($now - 1) .'>前へ</a>'. '　';
    }
    for($i = 1; $i <= $max_page; $i++){
      if ($i == $now) {
        // echo $now. '　'; 
      } else {
        echo '<a href=show_article.php?id=' . $article_id .'&page_id='. $i. '>'. $i. '</a>'. '　';
      }
    }
    if($now < $max_page){ // リンクをつけるかの判定
      echo '<a href=show_article.php?id=' . $article_id .'&page_id='.($now + 1).'>次へ</a></div>';
    }
  }

  function reply_list(){
    global $dbh, $comment_id, $article_id;
    // コメントの返信を取得
    $stmu = $dbh->prepare("SELECT * FROM comment WHERE comment_id = :comment_id AND delete_flg = 0 AND rep_flg != 0;");
    $stmu->bindValue(':comment_id', $comment_id, PDO::PARAM_STR);
    $stmu->execute();
    echo
      "<a class='acdn-button menu_botton link_btn'>返信</a>
        <div class='acdn-target reply_list'>
          <div>
            <form action='show_article.php?id=" . $article_id . "'method='post'>
              <input id='reply' type='text' name='reply'>
              <input type='hidden' name='comment_id_post' value='" . $comment_id ."'>
              <input type='submit' class='form_btn' name='reply_post' value='返信' onclick=''>
            </form>
          </div>"
    ;
    while ($row3 = $stmu->fetch()) {
      echo "<div class='reply_form'>" . $row3['comment'];
      // コメントの返信のユーザーを取得
      $user_id = $row3['user_id'];
      $stmt = $dbh->prepare("SELECT * FROM main_user WHERE user_id = :user_id;");
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();
      while ($row4 = $stmt->fetch()) {
        echo "<br>" . $row4['account'] . "/";
      }
      echo $row3['created_at'];
      $rep_flg = $row3['rep_flg'];
      echo "<br>";
      if($row3["user_id"] == $_SESSION["user_id"]){
      echo 
        "<a class='acdn-button menu_botton link_btn'>編集</a>
          <div class='acdn-target'>
            <div>
              <form action='show_article.php?id=" . $article_id . "'method='post'>
                <input id='comment' type='text' name='rep_up'>
                <input type='hidden' name='reply_id_post' value='" . $rep_flg ."'>
                <input type='submit' class='form_btn' name='reply_update' value='更新' onclick=''>
              </form>
              <a href='show_article.php?id=" . $article_id . "&reply_delete=" . $rep_flg ."'class='menu_botton delete_btn' onclick='return delete_check()'>削除</a>
            </div>
          </div>";
      }
      echo "</div>";
    }
  }

  function comment_post(){
    global $dbh, $article_id;
    $user_id = $_SESSION["user_id"];
    $comment = isset($_POST['comment']) ? $_POST['comment'] : NULL;
    $comment_id = uniqid();
    //テーブルに本登録する
    $statement = $dbh->prepare("INSERT INTO comment (comment_id,article_id,user_id,comment,created_at) VALUES (:comment_id,:article_id,:user_id,:comment,now())");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_STR);
    $statement->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':comment', $comment, PDO::PARAM_STR);
    $statement->execute();
  }
  function reply_post(){
    global $dbh, $article_id;
    $user_id = $_SESSION["user_id"];
    $comment = isset($_POST['reply']) ? $_POST['reply'] : NULL;
    $comment_id = isset($_POST['comment_id_post']) ? $_POST['comment_id_post'] : NULL;
    $rep_flg = uniqid();
    //テーブルに本登録する
    $statement = $dbh->prepare("INSERT INTO comment (comment_id,article_id,user_id,comment,created_at,rep_flg) VALUES (:comment_id,:article_id,:user_id,:comment,now(),:rep_flg)");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_STR);
    $statement->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':comment', $comment, PDO::PARAM_STR);
    $statement->bindValue(':rep_flg', $rep_flg, PDO::PARAM_STR);
    $statement->execute();
  }
  function article_update(){
    global $dbh, $article_id;
    $title = isset($_POST['title']) ? $_POST['title'] : NULL;
    $article = isset($_POST['article']) ? $_POST['article'] : NULL;
    $image = $_FILES['image']['name'];
    if($image == ""){
      $stmh = $dbh->prepare("SELECT * FROM article WHERE article_id = :article_id;");
      $stmh->bindValue(':article_id', $article_id, PDO::PARAM_STR);
      $stmh->execute();
      $row = $stmh->fetch();
      $image = $row["image"];
    }else{
      move_uploaded_file($_FILES['image']['tmp_name'],'C:/xampp/htdocs/board/img/' . $image);
    }
    //テーブルに本登録する
    $statement = $dbh->prepare("UPDATE article SET title = :title, article = :article, image = :image WHERE article_id = :article_id");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $statement->bindValue(':title', $title, PDO::PARAM_STR);
    $statement->bindValue(':article', $article, PDO::PARAM_STR);
    $statement->bindValue(':image', $image);
    $statement->execute();
    echo "投稿の更新が完了しました。";
  }
  function comment_update(){
    global $dbh;
    $comment = isset($_POST['come_up']) ? $_POST['come_up'] : NULL;
    $comment_id = isset($_POST['comment_id_post']) ? $_POST['comment_id_post'] : NULL;
    //テーブルに本登録する
    $statement = $dbh->prepare("UPDATE comment SET comment = :comment WHERE comment_id = :comment_id");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_STR);
    $statement->bindValue(':comment', $comment, PDO::PARAM_STR);
    $statement->execute();
    echo "コメントの更新が完了しました。";
  }
  function reply_update(){
    global $dbh;
    $comment = isset($_POST['rep_up']) ? $_POST['rep_up'] : NULL;
    $rep_flg = isset($_POST['reply_id_post']) ? $_POST['reply_id_post'] : NULL;
    //テーブルに本登録する
    $statement = $dbh->prepare("UPDATE comment SET comment = :comment WHERE rep_flg = :rep_flg");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':rep_flg', $rep_flg, PDO::PARAM_STR);
    $statement->bindValue(':comment', $comment, PDO::PARAM_STR);
    $statement->execute();
    echo "コメントの更新が完了しました。";
  }
  function comment_delete(){
    global $dbh;
    $comment_id = $_GET['comment_delete'];
    try{
      $stmt = $dbh->prepare("UPDATE comment SET delete_flg = 1 WHERE comment_id = :comment_id");
      $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_STR);
      $stmt->execute();
      echo '削除完了しました';
    }catch (PDOException $e){
      //トランザクション取り消し（ロールバック）
      $dbh->rollBack();
      print('Error:'.$e->getMessage());
      echo "削除に失敗しました。再度お試しください。";
      die();
    }
  }
  function reply_delete(){
    global $dbh;
    $rep_flg = $_GET['reply_delete'];
    $stmt = $dbh->prepare("UPDATE comment SET delete_flg = 1 WHERE rep_flg = :rep_flg");
    $stmt->bindValue(':rep_flg', $rep_flg, PDO::PARAM_STR);
    $stmt->execute();
    echo '削除完了しました';
  }
?>