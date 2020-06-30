<?php
  function dbconnection() {
    global $dbh, $e;
    try {
      // データベースに接続
      $dbh = new PDO(
          'mysql:dbname=heroku_acb4936fe6af6ed;host=us-cdbr-east-02.cleardb.com;charset=utf8mb4',//dbname=で参照DB名を切り替えられます。
          'bee79624990d4b',//初期設定ではパスワードは「root」になっています。
          'bf1c53a4',//初期設定ではパスワードは「root」になっています。
          // テスト用
          // 'mysql:dbname=board;host=localhost;charset=utf8mb4','root','',
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          ]
      );
    } catch (PDOException $e) {
      //エラー発生時
      echo $e->getMessage();
      exit;
    }
  }
?>