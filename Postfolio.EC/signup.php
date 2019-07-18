<?php
/* 会員登録画面 */

/* SESSIONの取得 */
session_start();

if(!empty($_SESSION['signup_name'])){
$signup_name = $_SESSION['signup_name'];
}
if(!empty($_SESSION['signup_mail'])){
$signup_mail = $_SESSION['signup_mail'];
}

/* セッションの削除 */
unset($_SESSION['token'],$_SESSION['signup_name'],$_SESSION['signup_mail'],$_SESSION['signup_pass1']);
setcookie( session_name(), '', time()-10000);

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
          <form action="signup-confirm.php" method="post" autocomplete="off">
            <div class="form-group">
              <div class="my-2">
                <label>ユーザーネーム：</label>
                <input type="text" name="name" class="form-control" value="<?php if(!empty($signup_name)){echo $signup_name;} ?>">
              </div>
              <div class="my-2">
                <label>メールアドレス：</label>
                <input type="email" name="mail" class="form-control" value="<?php if(!empty($signup_mail)){echo $signup_mail;} ?>">
              </div>
              <div class="my-2">
                <label>パスワード：</label>
                <input type="password" name="pass1" class="form-control" maxlength="8">
              </div>
              <div class="my-2">
                <label>パスワード（再入力）：</label>
                <input type="password" name="pass2" class="form-control" maxlength="8">
              </div>
            </div>
            <input type="submit" class="btn btn-pink" value="確認">
          </form>
          <hr>
          <div>
            <button type="button" class="btn btn-pink" onclick="location.href='login.php'">既に会員の方はこちら</button>
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
