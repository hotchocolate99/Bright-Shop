<?php 
//----ログイン状態-----------------
session_start();

  if (!$_SESSION['login']) {
    header('Location: /public/account/login.php');
    exit();
  }

  if ($_SESSION['login']= true) {
    $users = $_SESSION['user'];
  }
//var_dump($users);
  foreach($users as $user){
    //var_dump($user['id']);
  }
  $user_id = $user['id'];
  
//--------------------------------

//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors',true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

if($_SESSION['shopping_cart']){
    $total_in_cart = 0;
    foreach($_SESSION['shopping_cart'] as $detail){
      if(!empty($details)){
         $total_in_cart += $detail['detail_count'];
      }
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Updated Account</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>

        <?php include './../header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                　  <div class="typein">

                    <h2>Your account has successfully updated .</h2>
                    <a href="/index.php" class="link_b line_color_orange"">Go to home</a>

                    </div>
                </div>
            </div>
        </label>
    </body>
</html>