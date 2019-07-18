<?php
/* メール認証画面 */

$msg = $errMsg = '';

if(!empty($_GET['urltoken'])){
  $urltoken = $_GET['urltoken'];
}else{$urltoken = '#';}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得
   DB:pre_memberのurltokenカラムに$urltokenと一致するものがあれば取り出す */

$stmt = $dbh -> prepare("SELECT * FROM pre_member WHERE urltoken =:urltoken AND date > CURRENT_TIMESTAMP - INTERVAL 1 DAY");
$stmt->bindValue(":urltoken", $urltoken, PDO::PARAM_STR);
$stmt -> execute();

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

/* $urltokenと$data['urltoken']が一致したらmemberとmember_cuteにデータを格納し、pre_memberの情報を削除する */
if($urltoken==$data['urltoken']){
  $sql = 'INSERT INTO member(name, mail, password) VALUES("'.$data['name'].'","'.$data['mail'].'","'.$data['password'].'")';
  $dbh->exec("SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;");
  $stmt = $dbh -> prepare($sql);
  $dbh->beginTransaction();
  try{$stmt -> execute();
    $sql = "DELETE FROM pre_member WHERE urltoken ='".$urltoken."'";
    $stmt = $dbh->query($sql);
    $dbh->commit();
  }catch(PDOException $e){
    $dbh->rollback();
    $errMsg[] = '※お手数ですがもう一度お試しください。';
  }
  $msg = 'ログインページよりログインしてください！';
}else{$errMsg = '※お手数ですがもう一度お試しください。';}

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
        <div class="my-3"><h2><img class="pb-2 mr-1" src="./img/icon.png">メール確認</h2>
          <?php if(!empty($errMsg)): ?>
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading mb-2"><?php echo '登録エラーです！' ?></h4>
                  <p class="my-1"><?php echo $errMsg ?></p>
            </div>
          <?php endif ?>
          <?php if(!empty($msg)): ?>
            <div class="alert alert-alert-success" role="alert">
              <h4 class="alert-heading mb-2"><?php echo '登録が完了しました！' ?></h4>
                <p class="my-1"><?php echo $msg ?></p>
            </div>
          <?php endif ?>
          <hr>
          <div>
            <button type="button" class="btn btn-pink" onclick="location.href='index.php'">TOPへ戻る</button>
            <button type="button" class="btn btn-pink ml-2" onclick="location.href='login.php'">ログイン</button>
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
