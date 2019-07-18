<?php
/* ユーザー情報変更 */

require_once('./data/function.php');
session_start();

$id = $_SESSION['portEcUserId'];
$name = $_SESSION['portEcUserName'];
$mail = $_SESSION['portEcUserMail'];

/* ログインしていない場合indexに飛ばす */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  $id = $_SESSION['portEcUserId'];
  }else{
  $_SESSION['noLogin'] = 'noLogin';
  header('Location: index.php');
  exit;
}

/* CSRF対策用トークン */
if(!empty($_POST['token'])){
  $token = $_POST['token'];
}else{
  session_regenerate_id(true);
  $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
  $token = $_SESSION['token'];
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得
   DB:memberのidと$idが一致するデータを取り出す */
  $stmt = $dbh->prepare("SELECT password FROM member WHERE id = :id");
  $stmt->bindValue(":id", $id, PDO::PARAM_STR);
  $stmt -> execute();

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

/* POSTの取得 */
if(!empty($_POST['name'])){
  $name = $_POST['name'];
}elseif(!empty($_POST['edit'])){$errMsg[] = '※名前を入力してください。';}

if(!empty($_POST['mail'])){
  $mail = $_POST['mail'];
}elseif(!empty($_POST['edit'])){$errMsg[] = '※メールアドレスを入力してください。';}

if(!empty($_POST['new_password1']) && $_POST['new_password1'] == $_POST['new_password2']){
  $password = password_hash($_POST['new_password1'], PASSWORD_DEFAULT);
}else{
  $password = password_hash($_POST['old_password'], PASSWORD_DEFAULT);
}

/* CSRF対策用トークンとパスワードが合致すれば更新する */
if($token === $_SESSION['token'] && password_verify($_POST['old_password'], $data['password'])){
  $stmt = $dbh->prepare("UPDATE member SET name = :name, mail = :mail, password = :password WHERE id = :id");
  try{
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
    $stmt->bindValue(":password", $password, PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    $stmt -> execute();

    $_SESSION['portEcUserMail'] = h($mail);
    $_SESSION['portEcUserName'] = h($name);
  }catch(PDOException $e){$errMsg[] = '※更新エラーです。お手数ですがもう一度お試しください。';}
}elseif(!empty($_POST['edit'])){$errMsg[] = '※パスワードが入力されていないか、一致しません。';}

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
        <div class="my-3"><h2><img class="pb-2 mr-1" src="./img/icon.png">マイページ</h2></div>
        <div class="row">
          <div class="w-30">
            <div class="list-group col mt-2 ml-3">
              <a href="mypage.php" class="list-group-item list-group-item-action">ユーザー情報</a>
              <a href="order-history.php" class="list-group-item list-group-item-action">購入履歴</a>
              <a href="charge.php" class="list-group-item list-group-item-action">CUTEチャージ</a>
              <a href="logout.php" class="list-group-item list-group-item-action">ログアウト</a>
              <a href="user-delete.php" class="list-group-item list-group-item-action text-danger">退会</a>
            </div>
          </div>
          <div class="col mt-2">
            <form action="user-edit.php" method="post" class="form-inline">
              <table class="table bg-white table-bordered">
                <tr><th colspan="2"><h3>ユーザー情報変更</h3>
                  <?php if(!empty($errMsg)): ?>
                    <div class="alert alert-danger" role="alert">
                      <h4 class="alert-heading mb-2"><?php echo '変更できませんでした！' ?></h4>
                      <?php foreach($errMsg as $value): ?>
                        <p class="my-1"><?php echo $value."\n" ?></p>
                      <?php endforeach ?>
                    </div>
                  <?php endif ?></th></tr>
                <tr><th>名前</th><td><input type="name" name="name" class="form-control" value="<?php echo $name ?>"></td></tr>
                <tr><th>メール</th><td><input type="mail" name="mail" class="form-control" value="<?php echo $mail ?>"></td></tr>
                <tr><th>旧パスワード</th><td><input type="password" class="form-control" name="old_password"></td></tr>
                <tr><th>新パスワード</th><td><input type="password" class="form-control" name="new_password1"></td></tr>
                <tr><th>新パスワード(再入力)</th><td><input type="password" class="form-control" name="new_password2"></td></tr>
              </table>
          </div>
          <div class="w-100">
          <div class="row justify-content-end mr-4 mb-2">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="edit" class="btn btn-pink" role="button" value="変更する">
          </div>
            </form>
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
