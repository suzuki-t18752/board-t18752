<?php
  function mail_get(){
    global $mail;
    //POSTされたデータを変数に入れる
    $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
  }

  function mail_insert(){
    global $dbh,$message,$errors,$mail,$url;
    $urltoken = hash('sha256',uniqid(rand(),1));
    $url = "http://localhost/board/view/entry/registration_form.php"."?urltoken=".$urltoken;
    
    //ここでデータベースに登録する
    // 現在最新のidを取得しpre_user_idにする
    $stmt = $dbh->prepare("SELECT pre_user_id FROM pre_user WHERE pre_user_id = (select max(pre_user_id) from pre_user)");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // DBが空の場合(1番最初の作成の場合)のみ1を入れる
    if($data == null){
      $pre_user_id = 1;
    }else{
      $pre_user_id = $data[0]["pre_user_id"] + 1;
    }
    // メールアドレスの暗号化
    require_once("../../commons/code.php");
    $encrypted = openssl_encrypt($mail, $method, $password, $options, $iv);
    $iv = base64_encode($iv);
    $statement = $dbh->prepare("INSERT INTO pre_user (pre_user_id,urltoken,mail,date,iv) VALUES (:pre_user_id,:urltoken,:mail,now(),:iv )");
    
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':pre_user_id', $pre_user_id, PDO::PARAM_INT);
    $statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
    $statement->bindValue(':mail', $encrypted, PDO::PARAM_STR);
    $statement->bindValue(':iv', $iv, PDO::PARAM_STR);
    $statement->execute();
    //データベース接続切断
    $dbh = null;
    //メールの宛先
    $sendgrid = new SendGrid(getenv('SENDGRID_USERNAME'), getenv('SENDGRID_PASSWORD'));
    $email = new SendGrid\Email();
    $email->addTo($mail)->
        setFrom('from@example.com')->
        setSubject("【board】会員登録用URLのお知らせ")->
        setText("24時間以内に下記のURLからご登録下さい。" . $url);

    // $mailTo = $mail;
    // $returnMail = 'web@sample.com';
    // $name = "board";
    // $mail = 'web@sample.com';
    // $subject = "【board】会員登録用URLのお知らせ";
    // $body = <<< EOM
    // 24時間以内に下記のURLからご登録下さい。
    // {$url}
    // EOM;
    // mb_language('ja');
    // mb_internal_encoding('UTF-8');
    //Fromヘッダーを作成
    // $header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';
    // if (mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)) {
    if ($sendgrid->send($email)) {
      //セッション変数を全て解除
      $_SESSION = array();
      //クッキーの削除
      if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
      }
      //セッションを破棄する
      session_destroy();
      $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
    } else {
      $errors['mail_error'] = "メールの送信に失敗しました。";
    }	
  }

  function data_get(){
    global $dbh,$errors,$decrypted,$data, $method, $password, $options, $iv;
    //GETデータを変数に入れる
    $urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;
    //メール入力判定
    if ($urltoken == ''){
      $errors['urltoken'] = "もう一度登録をやりなおして下さい。";
    }else{
      //flagが0の未登録者・仮登録日から24時間以内
      $statement = $dbh->prepare("SELECT mail FROM pre_user WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
      $statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $statement->execute();
      
      //レコード件数取得
      $row_count = $statement->rowCount();
      
      //24時間以内に仮登録され、本登録されていないトークンの場合
      if($row_count ==1){
        $mail_array = $statement->fetch();
        $mail = $mail_array['mail'];
        $_SESSION['mail'] = $mail;
        $_SESSION['iv'] = $iv;
      }else{
        $errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
      }
      //データベース接続切断
      $dbh = null;
    }
  }

  function data_insert(){
    global $dbh,$errors,$user_id;
    //POSTされたデータを各変数に入れる
    $account = isset($_POST['account']) ? $_POST['account'] : NULL;
    $password = isset($_POST['password']) ? $_POST['password'] : NULL;
    $mail = $_SESSION['mail'];
    $iv = base64_encode($_SESSION['iv']);

    // 11桁までのログイン用ユーザーidを自動生成
    do {
      $user_id = mt_rand(100000, 999999999);
      $check_user_id = $dbh->prepare('SELECT * FROM main_user WHERE user_id = :user_id limit 1');
      $check_user_id->execute(array(':user_id' => $user_id));
      $result = $check_user_id->fetch();
    }while ($result > 0);
    //パスワードのハッシュ化
    $password_hash =  password_hash($password, PASSWORD_DEFAULT);

    //テーブルに本登録する
    $statement = $dbh->prepare("INSERT INTO main_user (user_id,account,mail,password,iv,created_at) VALUES (:user_id,:account,:mail,:password_hash,:iv,now())");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':account', $account, PDO::PARAM_STR);
    $statement->bindValue(':mail', $mail, PDO::PARAM_STR);
    $statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
    $statement->bindValue(':iv', $iv, PDO::PARAM_STR);
    $statement->execute();
    //pre_userのflagを1にする
    $statement = $dbh->prepare("UPDATE pre_user SET flag=1 WHERE mail=(:mail)");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':mail', $mail, PDO::PARAM_STR);
    $statement->execute();
    //データベース接続切断
    $dbh = null;
    //セッション変数を全て解除
    $_SESSION = array();
    //セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
    if (isset($_COOKIE["PHPSESSID"])) {
      setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    //セッションを破棄する
    session_destroy();
  }
?>