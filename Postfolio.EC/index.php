<?php
/* メインページ */
session_start();

require_once('./data/data.php');
require_once('./data/function.php');

$search=$searchBreed=$searchPattern=$searchColor=$searchWord='';

/* POSTの受け取り */
if(!empty($_POST['searchBreed'])){
  $searchBreed = $_POST['searchBreed'];
  $search .= '%'.$searchBreed.'%';
}
if(!empty($_POST['searchPattern'])){
  $searchPattern = $_POST['searchPattern'];
  $search .= '%'.$searchPattern.'%';
}
if(!empty($_POST['searchColor'])){
  $searchColor = $_POST['searchColor'];
  $search .= '%'.$searchColor.'%';
}
if(!empty($_POST['searchWord'])){
  $searchWord = $_POST['searchWord'];
  $search .= '%'.$searchWord.'%';
}

if(!empty($_POST['offset'])){
  $offset = $_POST['offset'];
}else{
  $offset = 0;
}
if(!empty($_POST['before'])){
  $offset -= 40;
}
if(!empty($_POST['after'])){
  $offset += 40;
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得 */
if(!empty($searchBreed) || !empty($searchPattern) || !empty($searchColor) || !empty($searchWord)){
  $stmt = $dbh->prepare(
    "SELECT * FROM product WHERE CONCAT(productName,'',breed,'',pattern,'',color1,'',color2,'',color3) LIKE :search
    ORDER BY productCode DESC LIMIT 40 OFFSET :value
  ");
  try{
    $stmt->bindValue(":search", $search, PDO::PARAM_STR);
    $stmt->bindValue(":value", $offset, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){;}
}else{
  try{
    $stmt = $dbh->prepare("SELECT * FROM product ORDER BY productCode DESC LIMIT 40 OFFSET :value");
    $stmt->bindValue(":value", $offset, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){;}
}

/* ログインをしていない場合MySQLから離脱 */
if(empty($_SESSION['portEcUserId'])){unset($dbh);}

/* エラー表示 */
if(!empty($_SESSION['noLogin'] === 'noLogin')){
$errMsg = '※この動作を行うには<a href="login.php" class="alert-link">ログイン</a>してください。';

/* セッションの削除 */
unset($_SESSION['noLogin']);
setcookie( session_name(), '', time()-10000);
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
        <?php if(!empty($errMsg)): ?>
          <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            <h4 class="alert-heading mb-2"><?php echo 'エラーです！' ?></h4>
            <p class="my-1"><?php echo $errMsg ?></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="閉じる">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif ?>
        <!-- カルーセル 画像設定は data/data.php -->
        <div id="carousel" class="carousel slide mt-2" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img class="d-block w-100" src="<?php echo $carouselActive['src'] ?>" alt="<?php echo $carouselActive['alt'] ?>">
            </div>
            <?php foreach($carousel as list($src,$alt)): ?>
             <div class="carousel-item">
               <img class="d-block w-100" src="<?php echo $src ?>" alt="<?php echo $alt ?>">
             </div>
            <?php endforeach ?>
          </div>
          <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">前へ</span>
          </a>
          <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">次へ</span>
          </a>
        </div>

        <!-- 検索フォーム -->
        <div class="my-3 mx-auto">
          <div class="text-center"><img class="pb-2 mx-1" src="./img/icon.png"><img class="pb-2 mx-1" src="./img/icon.png"><img class="pb-2 mx-1" src="./img/icon.png"></div>
          <form action="index.php" method="post">
            <div class="row m-2">
              <select name="searchBreed" class="custom-select border-blue col-4 m-0">
                <option selected value="">種類</option>
                <?php foreach($breed as $value): ?>
                  <option value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
              <select name="searchPattern" class="custom-select border-blue col-4 m-0">
                <option selected value="">パターン</option>
                <?php foreach($pattern as $value): ?>
                  <option value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
              <select name="searchColor" class="custom-select border-blue col-4 m-0">
                <option selected value="">カラー</option>
                <?php foreach($color as $value): ?>
                  <option value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
            </div>
          <div class="row m-2">
            <input name="searchWord" type="text" class="form-control border-blue" placeholder="フリーワード">
            <button type="submit" class="btn btn-pink d-block mx-auto my-2">理想の猫を検索する！</button>
          </div>
          </form>
        </div>

        <!-- 商品 -->
        <div class="row text-center m-1">
          <?php foreach($stmt as $data): ?>
          <div class="card card-p col-lg-3 col-mf-4 col-sm-6 col-xs-12">
          <a href="<?php echo 'https://ec.nya-n.xyz/product.php?code='.$data['productCode'] ?>" class="m-2 card-b text-dark">
            <img class="card-img-top img-m" src="<?php echo $data['path'] ?>" alt="<?php echo $data['productName'] ?>">
            <div class="card-body p-1">
              <p class="card-txt m-1"><?php echo $data['breed'] ?></p>
              <p class="card-text m-1"><?php echo $data['pattern'] ?></p>
              <p class="<?php echo colorIcon($data['color1']) ?>">■</p>
              <p class="<?php echo colorIcon($data['color2']) ?>">■</p>
              <p class="<?php echo colorIcon($data['color3']) ?>">■</p>
            </div>
          </a>
        </div>
      <?php endforeach ?>
        </div>

        <div class="w-100">
          <div class="row justify-content-end mr-3 mb-1">
            <form action="https://ec.nya-n.xyz/" method="post" class="form-inline">
              <input type="submit" class="btn btn-pink m-1" name="before" value="前の40件">
              <input type="submit" class="btn btn-pink m-1" name="after" value="次の40件">
              <input type="hidden" name="offset" value="<?php echo $offset ?>">
            </form>
          </div>
        </div>

        <p class="pagetop"><a href="#">▲</a></p>
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
    <script script type="text/javascript" src="./read/function.js"></script>
  </body>
</html>
