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

ini_set('display_errors',true);
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
//$test = getTest();
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

            <li><div class="search">
                    <form action="/public/list/list_search_result.php" method="post">
                            <div class="form_item search_item">
                            <label>Gender<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="wide" name="category">
                                        <option value=Boys>Boys</option>
                                        <option value=Girls>Girls</option>
                                    </select>
                                </label>
                            </div>    
<?php// echo $test ;?>
                            <div class="form_item search_item">
                                <label>Category<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="wide" name="category">
                                        <option value=Dress>Dress</option>
                                        <option value=Jaket>Jaket</option>
                                        <option value=Pants>Pants</option>
                                        <option value=Shirt>Shirt</option>
                                        <option value=Skirt>Skirt</option>
                                        <option value=Shoes>Shoes</option>
                                        <option value=Sleeper>Sleeper</option>
                                        <option value=Sweater>Sweater</option>
                                        <option value=Other>Other</option>
                                    </select>
                                </label>
                            </div>

                            <div class="form_item search_item">
                            <label>Size<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="wide" name="category">
                                        <option value=60>60</option>
                                        <option value=70>70</option>
                                        <option value=80>80</option>
                                        <option value=90>90</option>
                                        <option value=100>100</option>
                                        <option value=110>110</option>
                                        <option value=120>120</option>
                                        <option value=12>12</option>
                                        <option value=13>13</option>
                                        <option value=14>14</option>
                                        <option value=15>15</option>
                                        <option value=Other>Other</option>
                                    </select>
                                </label>
                            </div>
                            
                            <div class="form_item search_item">
                            <label>Price<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="wide" name="category">
                                        <option value=￥500〜￥1000>￥500〜￥2000</option>
                                        <option value=￥500〜￥1000>￥2000〜￥3000</option>
                                        <option value=￥500〜￥1000>￥3000〜￥4000</option>
                                        <option value=￥500〜￥1000>￥4000〜￥5000</option>
                                        <option value=￥500〜￥1000>￥5000〜￥6000</option>
                                        <option value=￥500〜￥1000>￥6000〜</option>
                                        
                                    </select>
                                </label>
                            </div>    

                            <div class="form_item search_item">
                            <label>Free word<br>
                                <input type="text" name="search_word" class="sample2Text">
                            </label>
                            </div>

                            <input class="btn bg_blue" type="submit"  value="search">
                    </form>
            </div></li>
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
                              
                              <h1 class="product_name"><?php echo $newest_boys['product_name'];?></h1>
                              <a href="/view/product_details.php?id=<?php echo h($newest_boys['id'])."#review"?>"><h5><?php getStarRate($newest_boys['id']);?></h5></a>
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
                                <h1 class="product_name"><?php echo $second_newest_boys['product_name'];?></h1>
                                <a href="/view/product_details.php?id=<?php echo h($second_newest_boys['id'])."#review"?>"><h5><?php getStarRate($second_newest_boys['id']);?></h5></a>
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
                              <h1 class="product_name"><?php echo $newest_girls['product_name'];?></h1>
                              <a href="/view/product_details.php?id=<?php echo h($newest_girls['id'])."#review"?>"><h5><?php getStarRate($newest_girls['id']);?></h5></a>
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
                              <h1 class="product_name"><?php echo $second_newest_girls['product_name'];?></h1>
                              <a href="/view/product_details.php?id=<?php echo h($second_newest_girls['id'])."#review"?>"><h5><?php getStarRate($second_newest_girls['id']);?></h5></a>
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