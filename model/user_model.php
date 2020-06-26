<?php 
  function user_data(){
    global $dbh, $user_id, $account_data, $mail_data, $iv, $method, $password, $password_hash, $options;
    $user = $dbh->prepare('SELECT * FROM main_user WHERE user_id = :user_id;');
    $user->execute(array(':user_id' => $user_id));
    $user_data = $user->fetch();
    $account_data = $user_data["account"];
    $iv = base64_decode($user_data["iv"]);
    $data = $user_data["mail"];
    $mail_data = openssl_decrypt($data, $method, $password, $options, $iv);
    $password_hash = $user_data["password"];
  }

  function user_update(){
    global $dbh, $user_id, $iv, $method, $password, $password_hash, $options;
    $account = isset($_POST['account']) ? $_POST['account'] : NULL;
    $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
    $mail = openssl_encrypt($mail, $method, $password, $options, $iv);
    if(!empty($_POST['password'])){
      $password = isset($_POST['password']) ? $_POST['password'] : NULL;
      $password_hash =  password_hash($password, PASSWORD_DEFAULT);
    }
    //テーブルに本登録する
    $statement = $dbh->prepare("UPDATE main_user SET account = :account, mail = :mail, password = :password_hash WHERE user_id = :user_id");
    //プレースホルダへ実際の値を設定する
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':account', $account, PDO::PARAM_STR);
    $statement->bindValue(':mail', $mail, PDO::PARAM_STR);
    $statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
    $statement->execute();
    //データベース接続切断
    $dbh = null;
    header("Location: " . $_SERVER['PHP_SELF']);
    echo "ユーザーデータの更新が完了しました。";
  }
?>