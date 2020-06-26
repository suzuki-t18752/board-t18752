<?php
  session_start();
  header("Content-type: text/html; charset=utf-8");
  // ログイン確認
  if(!isset($_SESSION["user_id"])) {
    $no_login_url = "../index.php";
    header("Location: {$no_login_url}");
    exit;
  }
  require_once("../commons/dbconnect.php");
  dbconnection();
?>