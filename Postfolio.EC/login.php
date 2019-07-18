<?php
/* ログイン画面 */

require_once('./data/function.php');

$errMsg='';
$login_mail='';
$login_pass='';

/* POSTの受け取り */
if(!empty($_POST['login_mail']) && !empty($_POST['login_pass'])){
  $login_mail = $_POST['login_mail'];
  $login_pass = $_POST['login_pass'];

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得
   DB:memberのmailと$mailが一致するデータを取り出す */

$stmt = $dbh->prepare("SELECT * FROM member WHERE mail = :mail");
try{
  $stmt->bindValue(":mail", $login_mail, PDO::PARAM_INT);
  $stmt -> execute();
}catch(PDOException $e){;}

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

session_start();

/* passwordが合致したらログイン情報をセッションに格納する */
if(password_verify($login_pass,$data['password'])){
  session_regenerate_id(true);
  $_SESSION['portEcUserId'] = $data['id'];
  $_SESSION['portEcUserName'] = h($data['name']);
  $_SESSION['portEcUserMail'] = h($data['mail']);

  /* MySQLから離脱 */
  unset($dbh);

  header('Location: index.php');
  exit;

}else{$errMsg = '※メールアドレスとパスワードをご確認ください。' ;}
}
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
        <div class="my-3"><h2><img class="pb-2 mr-1" src="./img/icon.png">ログイン</h2>
          <form novalidate action="login.php" method="post" autocomplete="off">
            <div class="form-group">
              <?php if(!empty($errMsg)): ?>
                <div class="alert alert-danger" role="alert">
                  <h4 class="alert-heading mb-2"><?php echo 'ログインができませんでした！' ?></h4>
                    <p class="my-1"><?php echo $errMsg ?></p>
                </div>
              <?php endif ?>
              <div class="my-2">
                <label>メールアドレス：</label>
                <input type="email" name="login_mail" class="form-control" value="<?php if(!empty($login_mail)){echo $login_mail;} ?>">
              </div>
              <div class="my-2">
                <label>パスワード：</label>
                <input type="password" name="login_pass" class="form-control" maxlength="8">
              </div>
            </div>
            <input type="submit" class="btn btn-pink" value="ログイン">
          </form>
          <hr>
          <div>
            <button type="button" class="btn btn-pink" onclick="location.href='signup.php'">新規登録はこちら</button>
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
