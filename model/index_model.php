<?php
  // 新規投稿された時
  function new_article() {
    global $dbh;
    //POSTされたデータを各変数に入れる
    $title = isset($_POST['title']) ? $_POST['title'] : NULL;
    $article = isset($_POST['article']) ? $_POST['article'] : NULL;
    $image = $_FILES['image']['name'];
    $article_id = uniqid();
    $user_id = $_SESSION['user_id'];
    //テーブルに本登録する
    $statement = $dbh->prepare("INSERT INTO article (article_id,title,article,user_id,created_at,image) VALUES (:article_id,:title,:article,:user_id,now(),:image)");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':article_id', $article_id, PDO::PARAM_STR);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':title', $title, PDO::PARAM_STR);
    $statement->bindValue(':article', $article, PDO::PARAM_STR);
    $statement->bindValue(':image', $image);
    $statement->execute();

    // 画像ファイルの移動
    move_uploaded_file($_FILES['image']['tmp_name'],'C:/xampp/htdocs/board/img/' . $image);
  }
  function article_delete(){
    global $dbh, $article_id;
    $article_id = $_GET['article_delete'];
    try{
      //トランザクション開始
      $dbh->beginTransaction();
      $stmt = $dbh->prepare("UPDATE article SET delete_flg = 1 WHERE article_id = :article_id");
      $stmt->bindValue(':article_id', $article_id, PDO::PARAM_STR);
      $stmt->execute();
      $stmt = $dbh->prepare("UPDATE comment SET delete_flg = 1 WHERE article_id = :article_id");
      $stmt->bindValue(':article_id', $article_id, PDO::PARAM_STR);
      $stmt->execute();
      // トランザクション完了（コミット）
      $dbh->commit();
      echo '削除完了しました';
    }catch (PDOException $e){
      //トランザクション取り消し（ロールバック）
      $dbh->rollBack();
      print('Error:'.$e->getMessage());
      echo "削除に失敗しました。再度お試しください。";
      die();
    }
    // header("Location: " . $_SERVER['PHP_SELF']);
  }
  function data_count(){
    // 月別登録者数と投稿数
    global $dbh, $data, $data2;
    $stmh = $dbh->prepare("SELECT count(*) FROM main_user WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY created_at DESC;");
    $stmh->execute();
    $data = $stmh->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $dbh->prepare("SELECT count(*) FROM article WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY created_at DESC;");
    $stmt->execute();
    $data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  function article_list(){
    global $dbh;
    if(!empty($_POST['search_article'])){
      if(empty($_POST['title']) || empty($_POST['date'])){
        $stmn = $dbh->prepare("SELECT count(*) FROM article WHERE delete_flg = 0 AND (title LIKE :title OR (created_at BETWEEN :created_at1 AND :created_at2))");
      }else{
        $stmn = $dbh->prepare("SELECT count(*) FROM article WHERE delete_flg = 0 AND (title LIKE :title AND (created_at BETWEEN :created_at1 AND :created_at2))");
      }
      $title = $_POST['title'];
      $date1 = $_POST['date'] . ' 00:00:00';
      $date2 = $_POST['date'] . ' 23:59:59';
      $stmn->bindValue(':title', $title, PDO::PARAM_STR);
      $stmn->bindValue(':created_at1', $date1, PDO::PARAM_STR);
      $stmn->bindValue(':created_at2', $date2, PDO::PARAM_STR);
    }else{
      $stmn = $dbh->prepare("SELECT count(*) FROM article WHERE delete_flg = 0");
    }
    $stmn->execute();
    $row = $stmn->fetchAll(PDO::FETCH_ASSOC);
    $cnt = $row[0]["count(*)"];
    $max_page = ceil($cnt / 12);
    if(!isset($_GET['page_id'])){
        $now = 1;
    }else{
        $now = $_GET['page_id'];
    }
    $start_no = ($now - 1) * 12;
    if(!empty($_POST['search_article'])){
      if(empty($_POST['title']) || empty($_POST['date'])){
        $stmh = $dbh->prepare("SELECT * FROM article WHERE delete_flg = 0 AND (title LIKE :title OR (created_at BETWEEN :created_at1 AND :created_at2))");
      }else{
        $stmh = $dbh->prepare("SELECT * FROM article WHERE delete_flg = 0 AND (title LIKE :title AND (created_at BETWEEN :created_at1 AND :created_at2))");
      }
      $stmh->bindValue(':title', $title, PDO::PARAM_STR);
      $stmh->bindValue(':created_at1', $date1, PDO::PARAM_STR);
      $stmh->bindValue(':created_at2', $date2, PDO::PARAM_STR);
    }else{
      $stmh = $dbh->prepare("SELECT * FROM article WHERE delete_flg = 0");
    }
    $stmh->execute();
    $data = $stmh->fetchAll(PDO::FETCH_ASSOC);
    $data = array_slice($data, $start_no, 12, true);
    echo "<div class='article_list'>";
    foreach($data as $row){
      echo "<div>" . "<p><strong>" . $row['title'] . "</strong></p>";
      echo "<p>" . "投稿日時：" . $row['created_at'] . "</p>";
      $user_id = $row['user_id'];
      $stmt = $dbh->prepare("SELECT * FROM main_user WHERE user_id = :user_id;");
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();
      while ($row2 = $stmt->fetch()) {
        echo "<p>" . "投稿者：" . $row2['account'] . "</p>";
      }
      echo "<a href='show_article.php?id=" . $row['article_id'] . "'>" . "記事詳細" . "</a>" . "</div>";
    }
    echo "</div>";
    echo '<p>全件数'. $cnt. '件'. '　'; // 全データ数の表示です。
    if($now > 1){ // リンクをつけるかの判定
      echo '<a href=index.php?page_id='. ($now - 1) .'>前へ</a>'. '　';
    }
    for($i = 1; $i <= $max_page; $i++){
      if ($i == $now) {
        // echo $now. '　';
      } else {
        echo '<a href=index.php?page_id=&page_id='. $i. '>'. $i. '</a>'. '　';
      }
    }
    if($now < $max_page){ // リンクをつけるかの判定
      echo '<a href=index.php?page_id='.($now + 1).'>次へ</a></p>';
    }
  }
?>