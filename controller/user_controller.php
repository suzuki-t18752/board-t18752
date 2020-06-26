<?php 
  session_start();
  header("Content-type: text/html; charset=utf-8");
  if(!isset($_SESSION["user_id"])) {
    $no_login_url = "../index.php";
    header("Location: {$no_login_url}");
    exit;
  }
  require_once("../commons/dbconnect.php");
  dbconnection();

  // ログインしているユーザーが自分の投稿一覧を抽出する
  function myarticle_ex(){
    global $dbh, $user_id;
    $stmt = $dbh->prepare("SELECT title, article, created_at, image FROM article WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    //CSV文字列生成
    $csvstr = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $csvstr .= $row['title'] . ",";
      $csvstr .= $row['article'] . ",";
      $csvstr .= $row['created_at'] . ",";
      $csvstr .= $row['image'] . "\r\n";
    }
    //CSV出力
    $fileNm = "myarticle.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$fileNm);
    echo mb_convert_encoding($csvstr, "SJIS", "UTF-8"); //Shift-JISに変換したい場合のみ
    exit();
  }
?>