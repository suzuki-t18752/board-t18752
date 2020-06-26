<?php
  session_start();
  header("Content-type: text/html; charset=utf-8");
  require_once("commons/dbconnect.php");
  dbconnection();
  // ログアウトボタン押下時に
  function log_out(){
    // セッションからユーザーidを削除し、ページをリロードする
    unset($_SESSION['user_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
  }

  function log_in(){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM main_user WHERE user_id = ?');
    $stmt->execute([$_POST['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($row['user_id']) && password_verify($_POST['password'], $row['password'])) {
        $_SESSION["user_id"] = $_POST["user_id"];
        $login_success_url = "index.php";
        header("Location: {$login_success_url}");
        exit;
    }
    $errors["error"] = "※ID、もしくはパスワードが間違っています。<br>　もう一度入力して下さい。";
  }
?>
