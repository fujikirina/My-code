<?php
/* 商品ページ */

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

/* GETの受け取り */
if(!empty($_GET['code'])){
  $code = h($_GET['code']);
}

/* POSTの受け取り */
if(!empty($_POST['quantity'])){
  $quantity = $_POST['quantity'];
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* カートに入れる */
if(!empty($quantity)){
  $sql = "INSERT INTO cart(user_id, productCode, quantity) VALUES(:id, :code, :quantity)";
  $stmt = $dbh->prepare($sql);
try{
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  $stmt->bindValue(":code", $code, PDO::PARAM_INT);
  $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
  $stmt -> execute();
}catch(PDOException $e){;}
}

/* MySQL内のデータを取得 */
try{
  $stmt = $dbh->prepare("SELECT * FROM product WHERE productCode= :code");
  $stmt->bindValue(":code", $code, PDO::PARAM_INT);
  $stmt -> execute();
}catch(PDOException $e){;}

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

/* URLが無効だった場合mainに戻す */
if(empty($data[productName])){
  header('Location: index.php');
  exit;
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
        <div class="row"><img src="<?php echo $data['path'] ?>" class="mx-auto d-block rounded img-thumbnail col-6" alt="<?php echo $data['productName'] ?>"></div>
        <div class="text-center m-2">
          <h4 class="my-2"><img class="pb-2 mr-1" src="./img/icon.png"><?php echo $data['productName'] ?></h4>
          <p class="m-1">種類：<?php echo $data['breed'] ?></p>
          <p class="m-1">パターン：<?php echo $data['pattern'] ?></p>
          <div class="m-1">カラー：
            <p class="<?php echo colorIcon($data['color1']) ?>">■</p>
            <p class="<?php echo colorIcon($data['color2']) ?>">■</p>
            <p class="<?php echo colorIcon($data['color3']) ?>">■</p>
          </div>
          <form action="product.php?code=<?php echo $data['productCode'] ?>" method="post">
            <div class="form-row justify-content-center my-2">
              <div class="input-group mb-3 col-3">
                <input name="quantity" type="number" class="form-control border-blue" placeholder="任意のCUTE">
                <div class="input-group-append">
                <span class="input-group-text border-blue">CUTE</span>
              </div>
            </div>
            <div class="w-100"></div>
            <input type="submit" class="mb-2 btn btn-pink" value="カートに入れる">
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
