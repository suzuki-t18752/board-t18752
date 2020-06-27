<?php
	require_once("../../controller/registration_controller.php");
  require_once("../../model/registration_model.php");
  $errors = array();
	if(empty($_POST)) {
		header("Location: registration_mail_form.php");
		exit();
	}
	data_insert();
?>
 
<!DOCTYPE html>
<html>
  <head>
    <title>会員登録完了画面</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="../css/entry.css">
  </head>
  <body>
		<header class="in_icon">BOARD</header>
		<div class="insert_view">
			<?php if (count($errors) === 0): ?>
			<h2>会員登録完了画面</h2>
			<p>登録完了いたしました。<br>下記ログインIDはログイン時に必要なのでパスワードと一緒に大切に保管してください。</p>
			<p><strong>ログインID：<?php echo $user_id?></strong></p>
			<p><a href="../../index.php">ログイン画面</a></p>
			<?php elseif(count($errors) > 0): ?>
			<?php
				foreach($errors as $value){
					echo "<p>".$value."</p>";
				}
			?>
			<?php endif; ?>
		</div>
  </body>
</html>