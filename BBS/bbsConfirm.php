<?php
/* BBS送信確認画面 */

session_start();

/* CSRF対策 */
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

/* POSTに値があればSESSIONに格納する
   titleとnameが空欄であれば代用の値を入力する */

if(!empty($_POST['b_title'])){
  $title = $_POST['b_title'];
  $_SESSION['b_title'] = $title;
  }else{
  $title = 'No Title';
  $_SESSION['b_title'] = $title;
  }
  
if(!empty($_POST['b_name'])){
  $name = $_POST['b_name'];
  $_SESSION['b_name'] = $name;
  }else{
  $name = 'No Name';
  $_SESSION['b_name'] = $name;
  }
  
if(!empty($_POST['b_pass'])){
  $password = $_POST['b_pass'];
  $_SESSION['b_pass'] = $password;
  }
  
if(!empty($_POST['b_message'])){
  $message = $_POST['b_message'];
  $_SESSION['b_message'] = $message;
  }
  
/* 画像添付についての設定 */
$num = 0;

if($_FILES['img']['type']=='image/jpg' || $_FILES['img']['type']=='image/jpeg' && $_FILES['img']['size'] <= 200000){
  
  /* 画像をサーバーに保存する */
  $i_name = $_FILES['img']['name'];
  $img_name = $_FILES['img']['tmp_name'];
  $type = substr($_FILES['img']['type'],6);
  $number = rand(10000000,99999999);
  move_uploaded_file($img_name,'img/'.$number.'.'.$type);
  
  $num = 38784025;
  $msg = $i_name.'<br>画像が添付されています。';

  }else if($_FILES['img']['type']=='image/jpg' || $_FILES['img']['type']=='image/jpeg'){
  $msg = '※画像のサイズは200KB以下に設定してください。';
  }else if(!empty($_FILES['img']['name']) && $_FILES['img']['size'] <= 200000){
  $msg = '※画像の形式はJPGまたはJPEGにしてください。';
  }else if(!empty($_FILES['img']['name'])){
  $msg = '※画像の形式はJPGまたはJPEGにしてください。<br>また、画像のサイズは200KB以下に設定してください。';
  }else{
  $msg = '';
  }

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
  <p>本文は必須項目です。</p>
  <hr>
  <form action="bbsSubmit.php" method="post" autocomplete="off">
  <p>題名：</p><br>
  <?php echo $title; ?>
  <br>
  <p>名前：</p><br>
  <?php echo $name; ?>
  <br>
  <p>削除用パスワード：</p><br>
  <?php if(!empty($password)){echo '****';}else{;} ?><br>
  <p>本文：</p><br>
  <?php if(!empty($message)){
  echo nl2br($message);
  }else{echo '本文を入力してください。';} ?><br>
  <p>画像(JPG/JPEG 200KBまで)：</p><br>
  <?php echo $msg; ?>
  <br><br>
  <input type="hidden" name="img_name" value="<?php if($num==38784025){echo 'img/'.$number.'.'.$type;}else{echo 0;} ?>">
  <input type="button" value="修正" onClick="location.href='bbs.php'">
  <input type="hidden" name="token" value="<?php echo $token; ?>">
  <input class="submit" type="submit" value="送信">
  </form>
  </div>
  
</div>
</section>

<!-- footer -->
<?php include(__DIR__ . '/footer.html'); ?>

</body>
</html>