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

//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors',true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../private/database.php';
require_once './../private/functions.php';
//var_dump($_SESSION['user']);

//$getall = getAllFromAordersTable(1);
//var_dump($getall);

//var_dump($_SESSION['shopping_cart']);
if($_SESSION['shopping_cart']){
  $total_in_cart = 0;
  foreach($_SESSION['shopping_cart'] as $detail){
    if(!empty($_SESSION['shopping_cart'])){
      $total_in_cart += $detail['detail_count'];
    }
  }
}
//session_destroy();
//get 2 each of new products for boys and girls.--------------------------------------------
//1 = Boys
$newestBoysProductsDatas = getNewestProductsDatas(1);
$newest_id = $newestBoysProductsDatas[0]['id'];
$newest_boys = $newestBoysProductsDatas[0];

$secondNewestBoysProductDatas = getSecondNewestProductsDatas(1, $newest_id);
$second_newest_boys = $secondNewestBoysProductDatas[0];


//2 = Girls
$newestGirlsProductsDatas = getNewestProductsDatas(2);
$newest_id = $newestGirlsProductsDatas[0]['id'];
$newest_girls = $newestGirlsProductsDatas[0];

$secondNewestGirlsProductDatas = getSecondNewestProductsDatas(2, $newest_id);
$second_newest_girls = $secondNewestGirlsProductDatas[0];
//-----------------------------------------------------------------------------------------

?>


<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Bright-Shop</title>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
      <link rel="stylesheet" href="./css/index.css">
      <link rel="stylesheet" href="./css/header.css">
  </head>
  <body>
  <?php include './header.php';?>

    <!--image-->
      <div class="image">
            <div class="message"><p class="anime">Light Up Your Life<br>With Bright-Shop.</p></div>
      </div>

      <div class="menu">
         <ul>
            <li><a class="link_aa" href="./view/boys.php"><h2 class="form_title green">Boys</h2></a></li>

            <li><a class="link_aa" href="./view/girls.php"><h2 class="form_title pink">Girls</h2></a></li>
         </ul>
      </div>

      <div class="wrapper">

          <div class="container">

              <div class="typein">
                  <h1 class="form_title orange">New Arrivals</h1>
                  <br>
                  <div class="product_info">

                      <div class="product_box">
                          <div class="img_box">
                              <img src="./manage/<?php echo "{$newest_boys['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                          </div>

                          <div class="text_part">
                              <h2 class="product_name"><?php echo $newest_boys['product_name'];?></h2>
                              <h2>¥&nbsp;<?php echo n($newest_boys['price']);?>&nbsp;(Tax not included)</h2>
                              <h3><?php echo $newest_boys['description'];?></h3>
                              <a class="button" href="/view/product_details.php?id=<?php echo h($newest_boys['id'])?>">Go To Details</a>
                          </div>
                      </div><!--product_box-->


                      <div class="product_box">
                          <div class="img_box">
                              <img src="./manage/<?php echo "{$second_newest_boys['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                          </div>

                           <div class="text_part">
                                <h2 class="product_name"><?php echo $second_newest_boys['product_name'];?></h2>
                                <h2>¥&nbsp;<?php echo n($second_newest_boys['price']);?>&nbsp;(Tax not included)</h2>
                                <h3><?php echo $second_newest_boys['description'];?></h3>
                                <a class="button" href="/view/product_details.php?id=<?php echo h($second_newest_boys['id'])?>">Go To Details</a>
                            </div>
                      </div><!--product_box-->

                  </div><!--product_info-->
                  <br>
                  <br>
                  <div class="product_info">

                      <div class="product_box">
                          <div class="img_box">
                              <img src="./manage/<?php echo "{$newest_girls['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                          </div>

                          <div class="text_part">
                              <h2 class="product_name"><?php echo $newest_girls['product_name'];?></h2>
                              <h2>¥&nbsp;<?php echo n($newest_girls['price']);?>&nbsp;(Tax not included)</h2>
                              <h3><?php echo $newest_girls['description'];?></h3>
                              <a class="button" href="/view/product_details.php?id=<?php echo h($newest_girls['id'])?>">Go To Details</a>
                          </div>
                      </div><!--product_box-->

                      <div class="product_box">
                          <div class="img_box">
                              <img src="./manage/<?php echo "{$second_newest_girls['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                          </div>

                          <div class="text_part">
                              <h2 class="product_name"><?php echo $second_newest_girls['product_name'];?></h2>
                              <h2>¥&nbsp;<?php echo n($second_newest_girls['price']);?>&nbsp;(Tax not included)</h2>
                              <h3><?php echo $second_newest_girls['description'];?></h3>
                              <a class="button" href="/view/product_details.php?id=<?php echo h($second_newest_girls['id'])?>">Go To Details</a>
                          </div>
                      </div><!--product_box-->

                   </div><!--product_info-->

              </div><!--typein-->
          </div><!--container-->
      </div><!--wrappr-->


  </body>
</html>