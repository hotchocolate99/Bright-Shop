<?php
//----ログイン状態-----------------
session_start();

  if ($_SESSION['login']= true) {
    $users = $_SESSION['user'];
  }
  //var_dump($users);
  foreach($users as $user){
    //var_dump($user['id']);
  }
  $user_id = $user['id'];
  
//--------------------------------
//ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../private/database.php';
require_once './../private/functions.php';


if($_SESSION['shopping_cart']){
   $total_in_cart = 0;
   foreach($_SESSION['shopping_cart'] as $detail){
      if(!empty($_SESSION['shopping_cart'])){
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
        <title>contacted</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./css/form.css">
        <link rel="stylesheet" href="./css/header.css">
    </head>

    <body>

        <?php include './header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                　  <div class="typein">
                        <h2>Your message has been sent successfuly.<br>We will response within 2 business days.<br>Thank you.</h2>
                    </div>
                </div>
            </div>
        </label>
    </body>
</html>