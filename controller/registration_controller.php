<?php
  session_start();
  header("Content-type: text/html; charset=utf-8");
  
  //クロスサイトリクエストフォージェリ（CSRF）対策
  $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
  $token = $_SESSION['token'];
  
  //クリックジャッキング対策
  header('X-FRAME-OPTIONS: SAMEORIGIN');

  //データベース接続
  require_once("../../commons/dbconnect.php");
  dbconnection();
?>