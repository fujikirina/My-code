<?php
/* 掲示板 */

/* SESSIONの取得 */
session_start();

if(!empty($_SESSION['b_title'])){
$title = $_SESSION['b_title'];
}
if(!empty($_SESSION['b_name'])){
$name = $_SESSION['b_name'];
}
if(!empty($_SESSION['b_pass'])){
$password = $_SESSION['b_pass'];
}
if(!empty($_SESSION['b_message'])){
$message = $_SESSION['b_message'];
}

/* セッションの削除 */
unset($_SESSION['b_title'],$_SESSION['b_name'],$_SESSION['b_pass'],$_SESSION['b_massage']);
setcookie( session_name(), '', time()-10000);

/* MySQLに接続 */
require_once('db/db.php');
require_once('data/function.php');

/* MySQL内のデータを取得 */
$sql = "SELECT * FROM bbs";
$stmt = $dbh->query($sql);

/* MySQLから離脱 */
unset($dbh);

/* 日時の分割用 */
function multiexplode ($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="reset.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Noto+Sans+JP:400,700" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <meta name="description" content="ポートフォリオ用サイトです。">
  <meta name="viewport" content="width=device-width">
  <link rel="icon" href="/img/favicon.ico">
  <title>Portfolio.BBS</title>
</head>
 
<body>

<!-- header -->
<div id="position">
  <nav id="nav">
    <ul>
      <li><a href="#top">Top</a></li>
      <li><a href="#bbstop">BBS Top</a></li>
      <li><a href="#bottom">Bottom</a></li>
    </ul>
  </nav>
</div>

<?php include(__DIR__ . '/header.html'); ?>
  
<section>
<div class="main">
  <div class="hoge">
  <p class="title">Portfolio.BBS</p><br>
  <p>書き込みがページに反映されるシンプルな掲示板です。</p><br>
  <p>本文は必須項目です。</p>
  <hr>
  <input type="button" class="bbsButton" id="bbs1" value="書き込む">
  
  <div class="bbs1">
  <form action="bbsConfirm.php" method="post" autocomplete="off" enctype="multipart/form-data">
  <p>題名：</p><br>
  <input type="text" name="b_title" maxlength="20" style="width:400px" value="<?php if(!empty($title)){echo $title;} ?>"><br>
  <p>名前：</p><br>
  <input type="text" name="b_name" maxlength="20" style="width:400px" value="<?php if(!empty($name)){echo $name;} ?>"><br>
  <p>削除用パスワード(半角数字)：</p><br>
  <input type="password" name="b_pass"  maxlength="8" style="width:400px" value="<?php if(!empty($password)){echo $password;} ?>"><br>
  <p>本文：</p><br>
  <textarea name="b_message" cols="50" rows="10" maxlength="500" style="width:400px" required><?php if(!empty($message)){echo $message;} ?></textarea><br>
  <p>画像(JPG/JPEG 200KBまで)：</p><br>
  <input type="file" name="img" id="noInput" accept="image/jpeg"><br>
  <input type="submit" value="確認">
  </form>
  </div>
  
  <hr>
  
  <input type="button" class="bbsButton" id="bbs2" value="削除する">
  
  <div class="bbs2">
  <form action="bbsDelete.php" method="post" autocomplete="off">
  <p>削除したい投稿のID：</p><br>
  <input type="number" name="id"  style="width:150px" maxlength="3"><br>
  <p>投稿時パスワード：</p><br>
  <input type="password" name="d_pass"  style="width:150px" maxlength="8"><br>
  <input class="submit" type="submit" value="削除">
  </form>
  </div>

  </div>
<!-- 書き込みの表示 -->
  <p id="bbstop">
  <?php foreach ($stmt as $data) : ?>
    <div class="hoge">
    <div class="b_top"><?php echo h($data['id'])."．".h($data['title']) ?></div>
    <?php echo nl2br(h($data['message'])) ?><br>
    <?php 
    /* もし画像があれば表示する */
    if(!empty($data['imgPath'])){  ?>
    <img class="bbsImg" src="<?php echo $data['imgPath'] ?>"><br>
    <?php } ?>
    <div class="b_bottom">by <?php 
    /* 日時を分割する */
    $delimiters = array('-',':',' ');
    $date = multiexplode($delimiters,htmlspecialchars($data['date'], ENT_QUOTES ,'UTF-8'));
     echo h($data['name'])." - ".$date[0]."年".$date[1]."月".$date[2]."日 ".$date[3].":".$date[4] ?></div></div>
  <?php endforeach; ?>

  <p id="bottom"></p>
</div>
</section>

<!-- footer -->
<?php include(__DIR__ . '/footer.html'); ?>

<script src="script.js"></script>
</body>
</html>