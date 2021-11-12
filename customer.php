<?php
  #セッション開始
  session_start();
  #status確認
  if($_SESSION["status"] == 1){
    $_SESSION["status"] = 1;
    $mail =  $_SESSION["SESSION_MAIL"];
    $pass =  $_SESSION["SESSION_PASS"];
    $_SESSION["SESSION_MAIL"] = $mail;
    $_SESSION["SESSION_PASS"] = $pass;
  }else{
    #トークン確認
    if($_SESSION["token"] == $_POST["token"]){
    }else{
      $_SESSION["error"] = "セッションエラーです.";
      header("Location: ./login_start.php");
      return;
    }
    #echo "セッションIDは".$_COOKIE["PHPSESSID"]."。";
    #メール、パスワード取得
    $mail3 = $_POST["mail"];
    $pass3 = $_POST["pass"];
    #正規表現チェック
    $mail_display = "/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/";
    $pass_display = "/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}$/i";
    if(preg_match($mail_display,$mail3,$mail)){
      if(preg_match($pass_display,$pass3,$pass)){
      }else{
        $_SESSION["error"] = "パスワードに使えない文字があります";
        header("Location: ./login_start.php");
        return;
      }
    }else{
      $_SESSION["error"] = "メールアドレスに使えない文字があります";
      header("Location: ./login_start.php");
      return;
    }
    #現在時刻取得(より更新が最新のグループをより上位に表示するため)
    $dates = date("YmdHis");
    try{
      #データベース接続
      $dsn = 'mysql:dbname=xs539898_grutell;host=mysql12013.xserver.jp;charset=utf8';
      $user = 'xs539898_ceo';
      $password = 'Higashi624';
      $pdo = new PDO($dsn, $user, $password,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ]);
      #トランザクション開始
      $pdo->beginTransaction();
      $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
      $sql = "SELECT * FROM confirm WHERE mail like :mail";
      $stmh = $pdo -> prepare("$sql");
      $stmh -> bindValue(":mail",$_POST["mail"],PDO::PARAM_STR);
      $stmh -> execute();
      $count = $stmh -> rowCount();
      #検索結果を代入
      #メールアドレスとパスワードが一致する個人情報を取得
      while($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
        $mail2 = $row["mail"];
        $pass2 = $row["pass"];
        $names = $row["name1"];
      }
      try{
        #現在時刻をデータベースに格納
        #顧客情報の保存
        $sql2 = "UPDATE confirm SET datatime = :dates WHERE mail = :mail2";
        $stmh = $pdo -> prepare("$sql2");
        $params = array(':dates' => $dates , ":mail2" => $mail2);
        $stmh -> execute($params);
        $pdo -> commit();
        #echo "成功！";
      }catch(PDOException $e){
        die($e->getMessage());
        echo "miss";
      }
      #IDまたはメールアドレスと、passが一致するかどうかを判定する。
      if(password_verify($_POST["pass"],$pass2)){
      }else{
        $_SESSION["error"] = "パスワードまたはメールアドレスが違います";
        header("Location: login_start.php");
        return;
      }
      #データベース接続切断
      unset($pdo);
    }catch(PDOException $e){
      $pdo -> rollBack();
      echo "エラー".$e -> getMessage();
    }
    $_SESSION["status"] = 1;
    #セッションに入力情報を保存
    $_SESSION['SESSION_MAIL'] = $_POST["mail"];
    $_SESSION['SESSION_PASS'] = $pass2;
  }
    $mail4 = $_SESSION["SESSION_MAIL"];
    $pass4 = $_SESSION["SESSION_PASS"];
  try{
    #データベース接続
    $dsn = 'mysql:dbname=xs539898_grutell;host=mysql12013.xserver.jp;charset=utf8';
    $user = 'xs539898_ceo';
    $password = 'Higashi624';
    $pdo = new PDO($dsn, $user, $password,[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    #トランザクション開始
    $pdo->beginTransaction();
    $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $sql = "SELECT * FROM confirm WHERE mail like :mail4";
    $stmh = $pdo -> prepare("$sql");
    $stmh -> bindValue(":mail4",$mail4,PDO::PARAM_STR);
    $stmh -> execute();
    $count = $stmh -> rowCount();
    #検索結果を代入
    #メールアドレスとパスワードが一致する個人情報を取得
    while($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
      $mail2 = $row["mail"];
      $pass2 = $row["pass"];
      $names = $row["name1"];
      $ids = $row["id"];
    }
    try{
      #現在時刻をデータベースに格納
      #顧客情報の保存
      $sql2 = "UPDATE confirm SET datatime = :dates WHERE mail = :mail2";
      $stmh = $pdo -> prepare("$sql2");
      $params = array(':dates' => $dates , ":mail2" => $mail2);
      $stmh -> execute($params);
      $pdo -> commit();
      #echo "成功！";
    }catch(PDOException $e){
      die($e->getMessage());
      echo "miss";
    }
    #データベース接続切断
    unset($pdo);
  }catch(PDOException $e){
    $pdo -> rollBack();
    echo "エラー".$e -> getMessage();
  }
  $_SESSION["name"] = $names;
  $_SESSION["id"] = $ids
  /*  #セッション切断
    session_destroy();
    #セッションスタート
    session_start();
  #セッションに入力情報を保存
  $_SESSION['SESSION_MAIL'] = $mail2;
  $_SESSION['SESSION_PASS'] = $pass2;
  $_SESSION["status"] = 1;
  #トークン生成
  $token = "";
  for($i=0;$i<20;$i++){
      $token.=chr(mt_rand(65,90));
  }
  #セッション変数にトークン代入
  $_SESSION["token"] = $token;*/
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Grutell</title>
    <link rel="stylesheet" href="../style/PC/grutell.css">
    <link rel="stylesheet" href="../style/PC/company.css">
    <link rel="stylesheet" href="../style/PC/index-kata.css">
    <link rel="stylesheet" href="../style/PC/community.css">
    <link rel="stylesheet" href="../style/PC/customer.css">
    <link rel="stylesheet" href="../style/mobile/grutell-m.css">
    <link rel="stylesheet" href="../style/mobile/company-m.css">
    <link rel="stylesheet" href="../style/mobile/index-kata-m.css">
    <link rel="stylesheet" href="../style/mobile/community-m.css">
    <link rel="stylesheet" href="../style/mobile/customer-m.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
  </head>
  <body>
    <header>
      <div class="header">
        <ul class="headers">
          <li></li>
          <a><li class="header-dreamtell3"><?php echo $names; ?>様専用ページ<div class="subsubconsept"></div></li></a><div class="session-title4"><?php echo $_SESSION["error"]; $_SESSION["error"] = NULL; ?></div>
          <li></li>
          <div>
            <a href="../index.php" class="header-a header-element js-animation"><li>ホーム</li></a>
            <a href="../index.php#serch" class="header-a header-element js-animation"><li>検索</li></a>
            <a href="../index.php#service" class="header-a header-element js-animation"><li>サービス</li></a>
            <a href="../index.php#otoiawase" class="header-a header-element js-animation"><li>お問い合わせ</li></a>
            <label for="submit_logout" class="header-a header-element js-animation"><li>ログアウト</li></label>
            <form action="../index.php" method="POST">
              <input type="hidden" value="1" name="logout">
              <li>
                <input id="submit_logout" style="display:none;" type="submit" value="ログアウト">
              </li>
            </form>
          </div>
          <li></li>
        </ul>
      </div>
    </header>
    <header class="mobile">
      <div class="header" style="z-index:900;">
        <div class="header-dreamtell">Grutell</div>
        <div class="header-element menu-open js-animation" id="menu-open-btn">
            ______<br>______<br>______
        </div>
      </div>
    </header>
    <header class="mobile">
      <div class="mobile-main menu-close" style="z-index:999;">
        <div class="space">
            <div></div>
            <div></div>
            <div style="width:100%;display:flex;justify-content:flex-end;"><div class="mobile-e" id="menu-close-btn">×</div></div>
        </div>
        <div class="space">
            <div></div>
            <a class="mobile-element" href="#home"><div>Home</div></a>
            <div></div>
        </div>
        <div class="space">
            <div></div>
            <a class="mobile-element" href="#company"><div>Company</div></a>
            <div></div>
        </div>
        <div class="space">
            <div></div>
            <a class="mobile-element" href="#service"><div>Service</div></a>
            <div></div>
        </div>
        <div class="space">
          <div></div>
          <a class="mobile-element" href="#plan"><div>plan</div></a>
          <div></div>
        </div>
        <div class="space">
          <div></div>
          <a class="mobile-element" href="#sns"><div>SNS</div></a>
          <div></div>
        </div>
      </div>
    </header>
    <div class="osusume3">
      <div class="space">
        <div></div>
        <div></div>
        <div></div>
        <style>
        .plan_ele{
          width:200px;
          overflow:hidden;
        }
        </style>
        <a href="../LightningCat/secure/userform.php">
          <div class="plan_ele">ホームページ編集</div>
        </a>
        <a href="event-make.php">
          <div class="plan_ele">イベント作成</div>
        </a>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="space">
        <div></div>
        <div></div>
        <div></div>
        <a href="user.php">
          <div class="plan_ele">プロフィール編集</div>
        </a>
        <a href="../index.php#otoiawase">
          <div class="plan_ele">お問い合わせ</div>
        </a>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="space">
        <div></div>
        <div></div>
        <div></div>
        <a href="#">
          <div class="plan_ele">動画解説</div>
        </a>
        <a href="../index.php#serch">
          <div class="plan_ele">検索ページへ</div>
        </a>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="kaisetu-title3">改善要望</div>
      <div>
        <form action="contact.php" method="POST">
          <div style="width:100%;"><textarea style="resize:none;margin-left:50%;transform:translateX(-50%);" class="kaisetu-box" name="content" rows="5" placeholder="お問合せ内容を入力"></textarea></div>
          <input type ="hidden" name="token" value ="<?=$token?>">
          <br>
          <div style="width:100%;"><input style="margin-left:50%;transform:translateX(-50%);" class="contact-submit3 botun" type="submit" value="送信"></div>
        </form>
      </div>
    </div>
    <footer class="footer" id="sns">
      <div class="space">
        <div class="ft">
            <div class="footer-title">© Grutell since 2020</div>
            <div class="programer">お問い合わせ：grutell.2020@gmail.com</div>
            <div class="programer">代表　東谷 有真</div>
        </div>
        <div class="footer-e">
          <p><a href="https://www.instagram.com/higashitani_yuma/?hl=ja title" class="footere">Instagram<img src="../image/instagram.png" class="header-instagram"></a>
          <a href="https://www.youtube.com/channel/UCiQ841mhlM3aO-3eMzmLgeg?view_as=subscriber" class="footere">Youtube<img src="../image/YouTube.jpg" class="header-instagram"></a></p>
        </div>
      </div>
    </footer>
    <script src="../JS/menu.js"></script>
  </body>
</html>
