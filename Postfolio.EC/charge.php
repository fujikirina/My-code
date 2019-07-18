<?php
/* CUTEチャージ */

session_start();

/* POSTの取得 */
if(!empty($_POST['chargeCute'])){
  $charge = $_POST['chargeCute'];
}

/* ログインしていない場合indexに飛ばす */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  $id = $_SESSION['portEcUserId'];
}else{
  $_SESSION['noLogin'] = 'noLogin';
  header('Location: index.php');
  exit;
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* 所持CUTEの確認 */
try{
  $stmt = $dbh->prepare("SELECT cute FROM member_cute WHERE user_id = :id");
  $stmt->bindValue(":id", $id, PDO::PARAM_STR);
  $stmt -> execute();
}catch(PDOException $e){;}

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}
$cute = $data['cute'];

/* CUTEのチャージ */
$charge += $cute;
if(!isset($cute)){
  $sql = "INSERT INTO member_cute(user_id, cute) VALUES(:id, 0)";
  $stmt = $dbh->prepare($sql);
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch(PDOException $e){$errMsg = '※お手数ですがもう一度お試しください。';}
}else{
  $sql = 'UPDATE member_cute SET cute = :charge WHERE user_id = :id';
  $stmt = $dbh->prepare($sql);
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    $stmt->bindValue(":charge", $charge, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){$errMsg = '※お手数ですがもう一度お試しください。';}
}

$cute = $charge;

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
              <a href="charge.php" class="list-group-item list-group-item-action disabled">CUTEチャージ</a>
              <a href="logout.php" class="list-group-item list-group-item-action">ログアウト</a>
              <a href="user-delete.php" class="list-group-item list-group-item-action text-danger">退会</a>
            </div>
          </div>
          <div class="col mt-2 bg-white m-2 rounded">
            <h3 class="my-2">CUTEチャージ</h3>
            <p class="lead">現在のCUTE:<?php echo $cute ?></p>
             <form action="charge.php" method="post">
              <div class="input-group">
                <input type="number" name="chargeCute" class="form-control">
                <div class="input-group-append"><input type="submit" class="btn btn-pink" value="チャージする"></div>
              </div>
              <span class="form-text text-muted">
                購入するCUTEの量を選んでください。実際のお金は必要ありません。
              </span>
          </div>
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
