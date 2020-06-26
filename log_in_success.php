<?php
  require_once("header.php");
?>
<main>
  <div class="count_view">
    <?php data_count();?>
    <span>今月の登録者数:<strong><?php echo $data[0]["count(*)"]; ?></strong>人/</span>
    <span>今月の投稿数:<strong><?php echo $data2[0]["count(*)"]; ?></strong>投稿</span>
  </div>
  <div>
    <form action="index.php" method="post" class="search_form">
      <input type="text" name="title" placeholder="タイトル">
      <input type="date" name="date">
      <input type="submit" value="検索する" name="search_article">
    </form>
  </div>
  <div>
    <?php
      article_list();
    ?>
  </div>
</main>