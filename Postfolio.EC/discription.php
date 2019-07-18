<?php
session_start();
require_once('./data/data.php');
require_once('./data/function.php');
if(!empty($_SESSION['portEcUserId'])){require_once('../ec.nya-n.xyz-require/db/db.php');}

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
        <div class="backImg position-relative p-3 p-md-5 m-md-3 text-center border border-blue rounded">
          <div class="col">
            <h2 class="display-4 font-weight-normal text-pink text-shadow-lp">「かわいさ」を、<br class="d-xl-none d-block">"CUTE"に！</h2>
            <a class="btn btn-lg btn-outline-pink backlight" href="signup.php">登録してみる！</a>
          </div>
        </div>
        <div class="row my-3">
          <div class="col-lg-4">
            <div class="my-1 p-2 border border-blue rounded">
              <h3><img class="pb-2 mr-1" src="./img/icon.png">"CUTE"って？</h3>
              <p>猫たちが人間の心を射止めた数値です。この通貨で猫たちの「かわいさ」を購入することが出来ます。<br>現在は会員ページで無料チャージすることが出来ます。</p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="my-1 p-2 border border-blue rounded">
              <h3><img class="pb-2 mr-1" src="./img/icon.png">購入したら？</h3>
              <p>お客様には「かわいい」を適正に得た満足感が残ります。頂いたCUTEはボランティア団体に寄付され、猫たちの生活がより良くなり、桶屋が儲かります。</p>
            </div>
          </div>
          <div class="col-lg-4">
          <div class="my-1 p-2 border border-blue rounded">
            <h3><img class="pb-2 mr-1" src="./img/icon.png">注意事項</h3>
            <p>このサイトはジョークサイトです。CUTEを購入するためのお金は必要になりませんし、残念ながら実際の猫たちや団体、個人にも一切関係ありません。</p>
          </div>
        </div>
      </div><!-- /.row -->

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
