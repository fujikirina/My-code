<?php
/* BBS投稿削除完了画面 */

/* POSTの取得 */
if(!empty($_POST['id'])){
  $dId = intval($_POST['id']);
  }
if(!empty($_POST['d_pass'])){
  $dPass = intval($_POST['d_pass']);
  }

/* MySQLに接続 */
require_once('db/db.php');

/* MySQL内のデータを取得 */
$stmt = $dbh->prepare("SELECT pass FROM bbs WHERE id = :id");
$stmt->bindValue(":id",$dId,PDO::PARAM_INT);
$stmt -> execute();

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

/* パスワードが合致したら投稿を削除する */
if($dPass == $data['pass']){
  $stmt = $dbh->prepare("DELETE FROM bbs WHERE id = :id");
  $stmt->bindValue(":id",$dId,PDO::PARAM_INT);
  $stmt -> execute();
  }
  
/* MySQLから離脱 */
unset($dbh);

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
  <title>Portfolio.</title>
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
  <p><?php if(!empty($dPass) && $dPass == $data['pass']){echo $dId.'．は削除されました。';}else{echo '※パスワードが違います。<br>お手数ですがもう一度お試しください。';} ?></p><br><br>
  <input type="submit" value="掲示板へ戻る" onClick="location.href='bbs.php'">
  <input type="submit" value="HOMEへ戻る" onClick="location.href='index.php'">
  </div>
  
</div>
</section>

<!-- footer -->
<?php include(__DIR__ . '/footer.html'); ?>

</body>
</html>