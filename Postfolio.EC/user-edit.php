<?php
/* ユーザー情報変更 */

require_once('./data/function.php');
session_start();

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
  $stmt = $dbh->prepare("SELECT password, mail FROM member WHERE id = :id");
  $stmt->bindValue(":id", $id, PDO::PARAM_STR);
  $stmt -> execute();

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}
$mail = $data['mail'];

/* POSTの取得 */
if(!empty($_POST['name'])){
  $name = $_POST['name'];
}elseif(!empty($_POST['edit'])){$errMsg[] = '※名前を入力してください。';}

/* メールアドレスの重複確認 */
  $sql = "SELECT mail FROM member WHERE mail = :mail";
  $stmt = $dbh->prepare($sql);
  try{
    $stmt->bindValue(":mail", $_POST['mail'], PDO::PARAM_STR);
    $stmt -> execute();
  }catch(PDOException $e){;}
foreach ($stmt as $dbMail){break;}

/* POSTの入力があり、メールが一致する場合は何もしない */
if(!empty($_POST['edit']) && $_POST['mail']==$mail){;
}elseif(!empty($_POST['edit']) && !empty($dbMail['mail'])){
  $errMsg[] = '※登録済メールアドレスです。';
}elseif(!empty($_POST['edit']) && !empty($_POST['mail'])){$pre_mail = $_POST['mail'];
}elseif(!empty($_POST['edit'])){$errMsg[] = '※メールアドレスを入力してください。';}

if(!empty($_POST['new_password1']) && $_POST['new_password1'] == $_POST['new_password2']){
  $password = password_hash($_POST['new_password1'], PASSWORD_DEFAULT);
}else{
  $password = password_hash($_POST['old_password'], PASSWORD_DEFAULT);
}

/* CSRF対策用トークンとパスワードが合致すれば名前とパスワードを更新する */
if($token === $_SESSION['token'] && password_verify($_POST['old_password'], $data['password'])){
  $stmt = $dbh->prepare("UPDATE member SET name = :name, password = :password WHERE id = :id");
  try{
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":password", $password, PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    $stmt -> execute();
    $_SESSION['portEcUserName'] = h($name);
    $token2 = 'nya-n!';
    $msg[] = 'ユーザー情報を変更しました。';
  }catch(PDOException $e){$errMsg[] = '※更新エラーです。お手数ですがもう一度お試しください。';}
}elseif(!empty($_POST['edit'])){$errMsg[] = '※パスワードが入力されていないか、一致しません。';}

/* 更にメールアドレスの変更があればpre_mailを設定する */
if($token2 === 'nya-n!' && !empty($pre_mail)){
  $stmt = $dbh->prepare("UPDATE member SET mail = NULL WHERE id = :id");
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){;}

  $urltoken = md5(uniqid(rand(), true));
  $url = "https://ec.nya-n.xyz/mail-regis.php"."?urltoken=".$urltoken;
  $stmt = $dbh->prepare("INSERT INTO pre_mail(user_id, mail, urltoken) VALUES(:id, :pre_mail, :urltoken)");
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":pre_mail", $pre_mail, PDO::PARAM_STR);
    $stmt->bindValue(":urltoken", $urltoken, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){$errMsg[] = '※更新エラーです。お手数ですがもう一度お試しください。';}
  $_SESSION['portEcUserMail'] = 'メールアドレスの確認をしてください';
  $mail = $_SESSION['portEcUserMail'];
  $msg[] = 'メールアドレスの確認をしてください。';

  /* メールアドレス確認メールを送信する */
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  $subject = '【Portfolio.EC】メールアドレス確認用URLのお知らせ';
  $message = $name." 様\n【Portfolio.EC】のご登録メールアドレスの更新を受け付けました。\n\n24時間以内に以下のURLをクリックしてメールアドレスを有効化してください。\n".$url;
  $header = "Content-Type: text/plain\nReturn-Path: portfolio.ec@ec.nya-n.xyz\nFrom: Portfolio.EC<portfolio.ec@ec.nya-n.xyz>\nSender: Portfolio.EC<portfolio.ec@ec.nya-n.xyz>\nReply-To: portfolio.ec@ec.nya-n.xyz\nOrganization: Portfolio.EC\n";
  $send = mb_send_mail($pre_mail, $subject, $message, $header);
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
                  <?php endif ?>
                  <?php if(!empty($msg)): ?>
                    <div class="alert alert-success" role="alert">
                      <h4 class="alert-heading mb-2"><?php echo '変更完了しました！' ?></h4>
                      <?php foreach($msg as $value): ?>
                        <p class="my-1"><?php echo $value."\n" ?></p>
                      <?php endforeach ?>
                    </div>
                  <?php endif ?>
                </th></tr>
                <tr><th>名前</th><td><input type="name" name="name" class="form-control" value="<?php echo $name ?>"></td></tr>
                <tr><th>メール</th><td><input type="mail" name="mail" class="form-control" value="<?php echo $mail ?>"></td></tr>
                <tr><th>旧パスワード</th><td><input type="password" class="form-control" name="old_password" maxlength="8"></td></tr>
                <tr><th>新パスワード</th><td><input type="password" class="form-control" name="new_password1" maxlength="8"></td></tr>
                <tr><th>新パスワード(再入力)</th><td><input type="password" class="form-control" name="new_password2" maxlength="8"></td></tr>
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
