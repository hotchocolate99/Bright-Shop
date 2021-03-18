<?php
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

                    <h2>Your have successfully signed up.</h2>
                    <a href="/account/login.php" class="link_b line_color_orange">Log in here!</a>

                    </div>
                </div>
            </div>
        </label>
    </body>
</html>