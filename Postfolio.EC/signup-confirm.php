<?php
/* 会員登録確認画面 */

$name = $mail = $password = '';
session_start();

/* CSRF対策用トークンの発行 */
session_regenerate_id(true);
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

/* ログインしていたらエラーを表示する */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  $errMsg[] = '※既にログインしています。';
}

/* POSTに値があればSESSIONに格納する */

if(!empty($_POST['name'])){
  $name = $_POST['name'];
  $_SESSION['signup_name'] = $name;
}else{$errMsg[] = '※名前を入力してください。';}

if(!empty($_POST['mail'])){
  $mail = $_POST['mail'];
  $_SESSION['signup_mail'] = $mail;
  $mailMsg = $mail;
}else{$errMsg[] = '※メールアドレスを入力してください。';
  $mail = '#';}

if(!empty($_POST['pass1']) && !empty($_POST['pass2']) && $_POST['pass1']==$_POST['pass2']){
  if(mb_strlen($_POST['pass1'])==8 && preg_match('/[a-zA-Z0-9]+$/',$_POST['pass1'])){
    $password = $_POST['pass1'];
    $_SESSION['signup_pass1'] = $password;
    $password = '********';
    }else{
    $errMsg[] = '※パスワードが不正です。半角英数8文字で入力してください。';}
  }else{
  $errMsg[] = '※パスワードが入力されていないか、一致しません。';}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得
   DB:memberのmailカラムに$mailと一致するものがあれば取り出す */

  $sql = "SELECT mail FROM member WHERE mail ='".$mail."'";
  $stmt = $dbh->prepare($sql);
  try{
    $stmt -> execute();
  }catch(PDOException $e){;}

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

if($mail==$data['mail']){
  $errMsg[] = '※登録済メールアドレスです。';
  $token = 'no';
  }

  /* ログインをしていない場合MySQLから離脱 */
  if(empty($_SESSION['portEcUserId'])){unset($dbh);}
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
              <h4 class="alert-heading mb-2"><?php echo '入力内容に不備があります！' ?></h4>
              <?php foreach($errMsg as $value): ?>
                <p class="my-1"><?php echo $value."\n" ?></p>
              <?php endforeach ?>
            </div>
          <?php endif ?>
          <form action="signup-submit.php" method="post" autocomplete="off">
            <div class="form-group">
              <div class="my-2">
                <label>ユーザーネーム：</label>
                <?php echo $name; ?>
              </div>
              <div class="my-2">
                <label>メールアドレス：</label>
                <?php echo $mailMsg; ?>
              </div>
              <div class="my-2">
                <label>パスワード：</label>
                <?php echo $password; ?>
              </div>
            </div>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="button" class="btn btn-pink" value="修正" onClick="location.href='signup.php'">
            <?php if(empty($errMsg)){echo '<input type="submit" class="btn btn-pink" value="送信">';} ?>
          </form>
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
