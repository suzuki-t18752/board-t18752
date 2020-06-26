<?php
  // 暗号化するデータ
  // $str = "test@example.com";
    
  // 暗号化パスワード
  $password = 'secpass';

  // 暗号化方式
  $method = 'aes-256-cbc';

  // 方式に応じたIVに必要な長さを取得 ランダムな文字列
  $ivLength = openssl_cipher_iv_length($method);

  // IV を自動で生成
  $iv = openssl_random_pseudo_bytes($ivLength);

  // OPENSSL_RAW_DATA と OPENSSL_ZERO_PADDING を指定可
  $options = 0;

  // 暗号化
  // $encrypted = openssl_encrypt($mail, $method, $password, $options, $iv);
  // echo "<br>";
  // echo $encrypted;


  // 復号化
  // $decrypted = openssl_decrypt($encrypted, $method, $password, $options, $iv);
  // echo "<br>";
  // echo $decrypted;
?>