<?php
/* カート */

session_start();

/* ログインしていない場合indexに飛ばす */
if(!empty($_SESSION['portEcUserId']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserMail'])){
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

/* 数量の変更 */
if(!empty($_POST['change'])){
  /* カートidの取り出し */
  try{
    $stmt = $dbh->prepare("SELECT id FROM cart WHERE user_id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch(PDOException $e){;}

  foreach($stmt as $data){
    $changeId = $data['id'];
    $changeQuantity = $_POST[$changeId];
    $sql = 'UPDATE cart SET quantity = :quantity WHERE id = :change';
    $stmt = $dbh->prepare($sql);
    try{
      $stmt ->bindValue(":quantity", $changeQuantity, PDO::PARAM_STR);
      $stmt ->bindValue(":change", $changeId, PDO::PARAM_INT);
      $stmt -> execute();
    }catch(PDOException $e){
      $errTitle = '変更できませんでした！';
      $errMsg = 'もう一度お試しください。';}
  }
  $msg = '変更が完了しました。';
}

/* CSRF対策用トークンが合致すれば購入動作を行う */
if($token === $_SESSION['token'] && !empty($_POST['buy'])){
  /* 所持CUTE>cart内の総額の場合のみ実行する */
  $stmt = $dbh -> prepare(
    'SELECT cute, SUM(quantity) FROM member_cute
    JOIN cart ON member_cute.user_id = cart.user_id WHERE member_cute.user_id = :id'
  );
  try{
    $stmt ->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt -> execute();
    foreach($stmt as $data){break;}
    $memberCute = $data['cute'];
    $sumQuantity = $data['SUM(quantity)'];
  }catch(PDOException $e){;}

  if($memberCute > $sumQuantity){
    $afterCute = $memberCute-$sumQuantity;
    /* cartからidが一致するものを確認する */
    $stmt = $dbh->prepare("SELECT id FROM cart WHERE user_id = :id");
    try{
      $stmt->bindValue(":id", $id, PDO::PARAM_STR);
      $stmt -> execute();
      /* トランザクションの開始 */
      $dbh->exec("SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;");
      $dbh->beginTransaction();
      /* cartのデータを取得する */
      foreach($stmt as $data){
        $stmt = $dbh -> query('SELECT quantity, productCode FROM cart WHERE id = "'.$data['id'].'"');
          foreach($stmt as $cart){break;}
          $quantity = $cart['quantity'];
          $productCode = $cart['productCode'];
          /* cartデータをorder_historyに格納する */
          $sql = 'INSERT INTO order_history(user_id, productCode, quantity) VALUES(:user_id, :productCode, :quantity)';
          $stmt = $dbh->prepare($sql);
          $stmt ->bindValue(":user_id", $id, PDO::PARAM_INT);
          $stmt ->bindValue(":productCode", $productCode, PDO::PARAM_INT);
          $stmt ->bindValue(":quantity", $quantity, PDO::PARAM_INT);
          $stmt -> execute();
        }
        /* 移動が終わったcartのデータを削除し、ユーザーのcuteをアップデートする */
        $stmt = $dbh->query('DELETE FROM cart WHERE user_id ="'.$id.'"');
        $stmt = $dbh->query('UPDATE member_cute SET cute = "'.$afterCute.'" WHERE user_id ="'.$id.'"');
        $dbh->commit();
        $msg = '購入が完了しました。';
      }catch(PDOException $e){
        $dbh->rollback();
    }
  }else{
    $errTitle = '購入できませんでした！';
    $errMsg = '※CUTEを確認してください。';}
}

/* カート内の確認 */
try{
  $stmt = $dbh->prepare("SELECT id, user_id, quantity, productName, cart.productCode FROM cart LEFT JOIN product ON cart.productCode = product.productCode WHERE user_id = :id");
  $stmt->bindValue(":id", $id, PDO::PARAM_STR);
  $stmt -> execute();
}catch(PDOException $e){;}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="猫の可愛さ販売サイトです。">
    <title>にゃーん！</title>
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
        <table class="table bg-white table-bordered my-2">
          <tr><th colspan="3"><h3>カート</h3>
            <?php if(!empty($msg)): ?>
              <div class="alert alert-success" role="alert">
                <p class="alert-heading mb-2"><?php echo $msg; ?></p>
              </div>
            <?php endif ?></th></tr>
            <?php if(!empty($errTitle) && !empty($errMsg)): ?>
              <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading mb-2"><?php echo $errTitle ?></h4>
                  <p class="my-1"><?php echo $errMsg ?></p>
              </div>
            <?php endif ?>
          <tr><th>商品名</th><th>値段</th></tr>
          <form action="cart.php" method="post" class="form-inline">
          <?php foreach($stmt as $data): ?>
            <tr><td><a class="text-dark" href="https://ec.nya-n.xyz/product.php?code=<?php echo $data['productCode'] ?>"><?php echo $data['productName'] ?></a></td>
              <td><div class="input-group">
                <input name="<?php echo $data['id'] ?>" type="number" class="form-control" value="<?php echo $data['quantity'] ?>" autocomplete="off">
                <div class="input-group-append">
                <span class="input-group-text">CUTE</span>
              </div></td>
            <td class="text-center"></td></tr>
          <?php endforeach ?>
          <tr><td></td><td colspan="2"><div class="text-center">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <button type="submit" class="btn btn-outline-pink" name="change" value="change">値段変更</button>
            <button type="submit" class="btn btn-pink mx-2" name="buy" value="buy">購入</button>
          </form></div></td></tr>
        </table>
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
