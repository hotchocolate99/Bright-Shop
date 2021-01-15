<?php
//----ログイン状態-----------------
session_start();

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  $users_id = $user[0]['usr_id'];
//--------------------------------
//var_dump($user);
ini_set('display_errors',true);


require_once './private/database.php';
require_once './private/functions.php';
//var_dump($_SESSION['user']);

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
//お気に入りに追加（同じ商品が被らないようにしたい。）
$_SESSION['favorites'];
if($_GET['favorite_product_id']){

  $_SESSION['favorites'][] = $_GET['favorite_product_id'];
}

var_dump($_SESSION['favorites']);
//$_SESSION['favorites']=[];

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
        <li><a class="link_aa" href="./public/view/boys.php"><h2 class="form_title green">Boys</h2></a></li>

        <li><a class="link_aa" href="./public/view/girls.php"><h2 class="form_title pink">Girls</h2></a></li>
        <li>
          <div class="search">
              <form action="/public/view/search_result.php" method="post">
                  <input type="text" name="search_word" class="sample2Text" placeholder="Search">
              </form>
          </div>
        </li>
      </ul>

    </div>

    <div class="wrapper">
                <div class="container">
                
                    <div class="typein">
                    <h1 class="form_title orange">New Arrivals</h1><br>
                    <h2 class="form_title green">Boys</h2>
                        <div class="product_info">
                              <div class="product_box">

                                <div class="img_box">
                                  <img src="/manage/<?php echo "{$newest_boys['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                  <a class="link_aa favorite" href="./?favorite_product_id=<?php echo h($newest_boys['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                </div>

                                <div class="text_part">
                                
                                  <h2 class="product_name"><?php echo $newest_boys['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($newest_boys['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $newest_boys['description'];?></h3>
                                  <a class="button" href="./public/view/product_details.php?id=<?php echo h($newest_boys['id'])?>">Go To Details</a>
                                </div>
                                
                              </div>


                             <div class="product_box">
                                <div class="img_box">
                                  <img src="/manage/<?php echo "{$second_newest_boys['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                  <a class="link_aa favorite" href="./?favorite_product_id=<?php echo h($second_newest_boys['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                </div>

                                <div class="text_part">
                                  <h2 class="product_name"><?php echo $second_newest_boys['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($second_newest_boys['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $second_newest_boys['description'];?></h3>
                                  <a class="button" href="./public/view/product_details.php?id=<?php echo h($second_newest_boys['id'])?>">Go To Details</a>
                                </div>
                             </div>

                        </div>
                        <br>
                        <br>
                        <h2 class="form_title pink">Girls</h2>
                        <div class="product_info">

                              <div class="product_box">
                                <div class="img_box">
                                  <img src="/manage/<?php echo "{$newest_girls['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                  <a class="link_aa favorite" href="./?favorite_product_id=<?php echo h($newest_girls['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                </div>

                                <div class="text_part">
                                  <h2 class="product_name"><?php echo $newest_girls['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($newest_girls['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $newest_girls['description'];?></h3>
                                  <a class="button" href="./public/view/product_details.php?id=<?php echo h($newest_girls['id'])?>">Go To Details</a>
                                </div>
                              </div>

                             <div class="product_box">
                                
                                <div class="img_box">
                                  <img src="/manage/<?php echo "{$second_newest_girls['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                  <a class="link_aa favorite" href="./?favorite_product_id=<?php echo h($second_newest_girls['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                </div>

                                <div class="text_part">
                                  <h2 class="product_name"><?php echo $second_newest_girls['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($second_newest_girls['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $second_newest_girls['description'];?></h3>
                                  <a class="button" href="./public/view/product_details.php?id=<?php echo h($second_newest_girls['id'])?>">Go To Details</a>
                                </div>
                              </div>

                        </div>

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->


</body>
</html>