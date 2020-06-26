<?php
  require_once("code.php");
  $stmt = $dbh->prepare("SELECT * FROM pre_user WHERE pre_user_id = (select max(pre_user_id) from pre_user)");
  $stmt->execute();
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $iv = base64_decode($data[0]["iv"]);
  $data = $data[0]["mail"];
  $decrypted = openssl_decrypt($data, $method, $password, $options, $iv);
?>