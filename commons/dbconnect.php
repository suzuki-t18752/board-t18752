<?php
  function dbconnection() {
    global $dbh, $e;
    try {
      // データベースに接続
      $dbh = new PDO(
          'mysql:dbname=board;host=localhost;charset=utf8mb4',//dbname=で参照DB名を切り替えられます。
          'root',//初期設定ではパスワードは「root」になっています。
          '',//初期設定ではパスワードは「root」になっています。
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