<?php
/* BBS送信完了画面 */

/* POSTの取得 */
if($_POST['img_name']==='0'){$imgPath = '';
  }else if(!empty($_POST['img_name'])){
  $imgPath = $_POST['img_name'];
  }

session_start();

/* SESSIONの取得 */

if(!empty($_SESSION['b_title'])){
  $title = $_SESSION['b_title'];
  }
if(!empty($_SESSION['b_name'])){
  $name = $_SESSION['b_name'];
  }
if(!empty($_SESSION['b_pass'])){
  $password = $_SESSION['b_pass'];
  }else{$password = 936415892;}
if(!empty($_SESSION['b_message'])){
  $message = $_SESSION['b_message'];
  }

/* MySQLに接続 */
require_once('db/db.php');

/* $messageに数値が入っているとき
   CSRF用トークンが一致するときのみデータを格納する */
if(!empty($message) && $_POST['token']==$_SESSION['token']){
  $token='hogehoge';
  $stmt = $dbh->prepare("INSERT INTO bbs(title, name, pass , message ,imgPath) VALUES(:title, :name, :password, :message, :imgPath)");
  $stmt->bindValue(":title", $title, PDO::PARAM_STR);
  $stmt->bindValue(":name", $name, PDO::PARAM_STR);
  $stmt->bindValue(":password", $password, PDO::PARAM_INT);
  $stmt->bindValue(":message", $message, PDO::PARAM_STR);
  $stmt->bindValue(":imgPath", $imgPath, PDO::PARAM_STR);
  $stmt -> execute();
}

/* MySQLから離脱 */
unset($dbh);

/* セッションの削除 */
unset($_SESSION['b_title'],$_SESSION['b_name'],$_SESSION['b_pass'],$_SESSION['b_massage']);
setcookie( session_name(), '', time()-10000);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="reset.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Noto+Sans+JP:400,700" rel="stylesheet">
  <meta name="description" content="ポートフォリオ用サイトです。">
  <meta name="viewport" content="width=device-width">
  <link rel="icon" href="/img/favicon.ico">
  <title>Portfolio.BBS</title>
</head>
 
<body>

<!-- header -->
<?php include(__DIR__ . '/header.html'); ?>
  
<section>
<div class="main">

  <div class="hoge">
  <p class="title">Portfolio.BBS</p><br>
  <p>書き込みがページに反映されるシンプルな掲示板です。</p><br>
  <hr>
  <p><?php if($token=='hogehoge'){echo '送信ありがとうございました。';}else{echo '※送信エラーです。<br>お手数ですが最初からもう一度お試しください。';} ?></p><br><br>
  <input type="submit" value="掲示板へ戻る" onClick="location.href='bbs.php'">
  <input type="submit" value="HOMEへ戻る" onClick="location.href='index.php'">
  </div>
  
</div>
</section>

<!-- footer -->
<?php include(__DIR__ . '/footer.html'); ?>

</body>
</html>