<?php
session_start();

//ログアウトする時は、セッションの内容をdestroyで消去する。その前にセッションに空の配列を入れている。
//var_dump($_SESSION);　
$_SESSION = array();

// セッションクッキーを削除する。これはセッション削除とセットで覚える。このまま決まり文句として。
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
session_destroy();
//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log Out</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../../public/css/form.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
    </head>
    <body>

        <?php include './../../public/header.php';?>

        <label for="check">
        <div class="wrapper">
            <div class="container">
               <div class="typein">
               <h1 class="form_title orange">Log Out</h1>
               <br>
                   <h2>You have logged out successfully.<br>Thank you for visiting our shop.<br>We hope to see you again soon.<br>Have a bright day!</h2>
                   <br>
                    

               </div><!--typein-->
           </div><!--container-->
        </div> <!--wrapper-->
      </label>
    </body>
</html>