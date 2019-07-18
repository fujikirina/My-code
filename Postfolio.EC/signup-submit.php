<?php
/* 会員登録完了画面 */

$msg = $errMsg = '';
session_start();

/* ログインしている場合indexに飛ばす */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  header('Location: index.php');
  exit;
}

/* CSRF対策用トークンが一致したらSESSIONを取得する */
if(!empty($_SESSION['signup_name']) && !empty($_SESSION['signup_mail']) && !empty($_SESSION['signup_pass1']) && $_POST['token'] === $_SESSION['token']){
  $signup_name = $_SESSION['signup_name'];
  $signup_mail = $_SESSION['signup_mail'];
  $signup_pass1 = $_SESSION['signup_pass1'];
  $token2='nyanko';
  $msg = '※24時間以内にクリックして有効化してください。';
}else{$errMsg = '※お手数ですが最初からもう一度お試しください。';}

/* パスワードをハッシュ化する */
$signup_pass = password_hash($signup_pass1,PASSWORD_DEFAULT);

$urltoken = md5(uniqid(rand(), true));
$url = "https://ec.nya-n.xyz/registration.php"."?urltoken=".$urltoken;

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');
require_once('data/function.php');

/* DB:pre_memberへデータを格納する */
if($token2 === 'nyanko'){
  $stmt = $dbh -> prepare("INSERT INTO pre_member(name, mail , password ,urltoken) VALUES(:signup_name, :signup_mail, :signup_pass, :urltoken)");
  $stmt->bindValue(":signup_name", $signup_name, PDO::PARAM_STR);
  $stmt->bindValue(":signup_mail", $signup_mail, PDO::PARAM_STR);
  $stmt->bindValue(":signup_pass", $signup_pass, PDO::PARAM_STR);
  $stmt->bindValue(":urltoken", $urltoken, PDO::PARAM_STR);
  $stmt -> execute();
  $token3 = 'nya-n';
  }

/* MySQLから離脱 */
unset($dbh);

/* セッションの削除 */
unset($_SESSION['token'],$_SESSION['signup_name'],$_SESSION['signup_mail'],$_SESSION['signup_pass1']);
setcookie( session_name(), '', time()-10000);

/* メールを送信する */

mb_language("Japanese");
mb_internal_encoding("UTF-8");

$subject = '【Portfolio.EC】会員登録用URLのお知らせ';
$message = $signup_name." 様\nPortfolio.ECへの仮会員登録を受け付けました。\n\n24時間以内に以下のURLをクリックしてメールアドレスを有効化してください。\n".$url;

$header = "Content-Type: text/plain\nReturn-Path: portfolio.ec@ec.nya-n.xyz\nFrom: Portfolio.EC<portfolio.ec@ec.nya-n.xyz>\nSender: Portfolio.EC<portfolio.ec@ec.nya-n.xyz>\nReply-To: portfolio.ec@ec.nya-n.xyz\nOrganization: Portfolio.EC\n";

if($token3 === 'nya-n'){$send = mb_send_mail($signup_mail, $subject, $message, $header);}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="猫の可愛さ販売サイトです。">
    <title>にゃーん！</title>
    <link rel="icon" href="./img/favicon.ico">
    <link href="./read/bootstrap4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./read/style.css">
  </head>

  <body>
    <div class="container">

    <header>
      <?php
      require_once('./page/header.php');
      ?>
    </header>

    <main>
      <div class="container backlight py-1">
        <div class="my-3"><h2><img class="pb-2 mr-1" src="./img/icon.png">会員登録</h2>
          <?php if(!empty($errMsg)): ?>
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading mb-2"><?php echo '登録エラーです！' ?></h4>
                <p class="my-1"><?php echo $errMsg ?></p>
            </div>
          <?php endif ?>
          <?php if(!empty($msg)): ?>
            <div class="alert alert-alert-success" role="alert">
              <h4 class="alert-heading mb-2"><?php echo '確認メールを送信致しました。' ?></h4>
                <p class="my-1"><?php echo $msg ?></p>
            </div>
          <?php endif ?>
          <hr>
          <div>
            <button type="button" class="btn btn-pink" onclick="location.href='index.php'">TOPへ戻る</button>
          </div>
        </div>
      </div><!-- container /div -->
    </main>

    <footer>
      <?php
      require_once('./page/footer.php');
      ?>
    </footer>

    </div>

    <script type="text/javascript" src="./read/jquery-3.4.1.min.js"></script>
    <script src="./read/bootstrap4/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
