<?php
//----ログイン状態-----------------
session_start();

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  $users_id = $user[0]['usr_id'];
//--------------------------------
//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$test = getTest();
//var_dump($_GET);
if($_GET['id']){
    $product_id = $_GET['id'];
}else if($_GET['product_id']){
    $product_id = $_GET['product_id'];
}

$commons = getProductData($product_id);
//var_dump($commons);
foreach($commons as $common){
    //echo $common['product_name'];
}

$productsByColor = getProductByColor($product_id);
//print_r($productsByColor);

//値段を出すために使っている
$details = getProductDetails($product_id);
//print_r($details[3]['id']);

//---------------------------------------------------------------------------------

//色を取得そして、色の数をcount()で出す。
$colors = getProductColors($product_id);
//var_dump($colors[1]['color']);
$count_colors = count($colors);
//var_dump($count_colors);

$detailsCS[]=[];
for($a=0;$a<$count_colors;$a++){
    $color[$a]= $colors[$a]['color'];
    //echo $color[$a];//Dark GrayLight Gray
    }


//-----------------------------------------------------------------
// for($i=0;$i<$count_colors;$i++){ 商品登録のところでdetailsのフォームを８つ用意したので、８にしてみた。
 for($i=0;$i<8;$i++){
     $detailCS[$i] = getProductDetailsByColor($product_id, $colors[$i]['color']);
     $detailCSs[] = $detailCS[$i];
}

//print_r($detailCSs[0]);
//$detailCSsには色ごとの２つの配列が入っている。それぞれの色の配列に０、１の二つの配列が入っている。
//print_r($detailCSs[0][0]['color']);//Dark Gray
//print_r($detailCSs[0][1]['color']);//Dark Gray
//print_r($detailCSs[0][0]);//[id] => 4 [product_id] => 55 [price] => 3000 [gender] => 1 [weight] => 200 [color] => Dark Gray [size] => 60 [stock] => 10 [created_at] => 2021-01-09 17:04:58 [updated_at] => 2021-01-09 17:04:58 )
//print_r($detailCSs[0][1]);//[id] => 5 [product_id] => 55 [price] => 3000 [gender] => 1 [weight] => 200 [color] => Dark Gray [size] => 70 [stock] => 10 [created_at] => 2021-01-09 17:05:13 [updated_at] => 2021-01-09 17:05:13 )

//print_r($detailCSs[1][0]['color']);//Light Gray
//print_r($detailCSs[1][1]['color']);//Light Gray 
//print_r($detailCSs[1][0]); //[id] => 2 [product_id] => 55 [price] => 3000 [gender] => 1 [weight] => 200 [color] => Light Gray [size] => 60 [stock] => 10 [created_at] => 2021-01-09 14:17:40 [updated_at] => 2021-01-09 14:17:40 ) 
//print_r($detailCSs[1][1]);//[id] => 3 [product_id] => 55 [price] => 3000 [gender] => 1 [weight] => 200 [color] => Light Gray [size] => 70 [stock] => 10 [created_at] => 2021-01-09 17:03:20 [updated_at] => 2021-01-09 17:03:20 ) 
//----------------------------------------------
$total_reviews = getTotalReviews($product_id);
foreach($total_reviews as $total_review){
    //echo $total_review["count(*)"];
}
$reviews_count = $total_review["count(*)"];

$total_stars = getTotalStars($product_id);
foreach($total_stars as $total_star){
   // echo $total_star;
}
$stars = $total_star["SUM(star)"];

$star_rating = $stars / $reviews_count;


//---------------------------------------------

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
        <title>Product Details</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/detail.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>

     　<?php include './../header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <h1 class="form_title orange"><?php echo $common['product_name'];?></h1>
                    <div class="typein">

                            <div class="left_side">
                                <div class="img_box">
                                  <img src="./../manage/<?php echo "{$common['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                                </div>

                                <div class="text_part">
                                  <h1 class="product_name"><?php echo $common['product_name'];?></h1>
                                  <h2>¥&nbsp;<?php echo n($details[0]['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $common['description'];?></h3>
                                </div>
                            </div>
                            
                            <div class="right_side">
                                <form action="./../shopping/shopping_cart.php" method="post">
                                    <input type="hidden" name="detail_count" value="1" >
                                    <input type="hidden" name="count_updated_method" value="add">

                                    <?php if($count_colors>1):?>
                                        <h3><?php echo 'Choose color(s) and size(s) from below.';?></h3>

                                        <!--色が８色になる可能性ある。なので、thが８個いる。そしてそれぞれのサイズも７つずつ用意することになる--> 
                                        <table border=1>

                                            <!--colors up to 8--> 
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][0]['color'])):?>
                                                        <th>
                                                           <?php echo $detailCSs[$a][0]['color'] ;?>
                                                        </th>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>
                                            <!---------------------------------------------------------------------->

                                            <!--1st row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][0])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][0]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][0]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--2nd row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][1])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][1]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][1]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--3rd row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][2])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][2]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][2]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--4th row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][3])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][3]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][3]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--5th row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][4])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][4]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][4]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--6th row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][5])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][5]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][5]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--7th row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][6])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][6]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][6]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                            <!--8th row for size-->
                                            <tr>
                                                <?php for($a=0; $a<8; $a++):?>
                                                    <?php if(!empty($detailCSs[$a][7])):?>
                                                        <td class="top_left">Size:<?php echo $detailCSs[$a][7]['size'] ;?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][7]['id'])?>">
                                                        </td>
                                                    <?php else:?>
                                                        <?php echo '';?>
                                                    <?php endif;?>
                                                <?php endfor ;?>
                                            </tr>

                                    
                                        </table>




                                      <!--the case of one color------------------------------------------------>
                                    <?php else:?>
                                        <h3><?php echo 'Choose size(s) from below.';?></h3>

                                        <table border=1>
                                    　　     <tr><th><?php echo $detailCSs[0][0]['color'] ;?></th></tr>
                                            <tr><td class="first">Size:<?php echo $detailCSs[0][0]['size'];?><br>
                                                    <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[0][0]['id'])?>">
                                                </td>
                                    　　     </tr>

                                            <?php for($b=0;$b<8;$b++):?>
                                                <?php if(!empty($detailCSs[0][$b+1])):?>
                                                    <tr><td class="second">Size:<?php echo $detailCSs[0][$b+1]['size'];?><br>
                                                            <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[0][$b+1]['id'])?>">
                                                        </td>
                                    　　             </tr>
                                                <?php else:?>
                                                    <?php echo '';?>
                                                <?php endif;?>
                                            <?php endfor;?>

                                        </table>

                                    <?php endif;?>

                                    <input class="btn bg_green" type="submit" value="Add To Cart">

                                </form>
                            </div><!--right_side-->
                    </div><!--typein-->



                    <div class="typein2">

                            <h2 class="form_title2 line">Customer Reviews</h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php $allReviews = getAllReviewsByProductId($product_id);?>
                            <a class="atReview" name="review"></a>
                            <div class="line">
                            <?php if($star_rating <= 0.1):?>
                                <span><i class="big far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>
                                        
                            <?php if($star_rating >= 0.2 && $star_rating <= 0.6):?>
                                <span><i class="fas fa-star-half-alt checked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                            <?php endif;?>
                                          
                            <?php if($star_rating >= 0.7 && $star_rating <= 1.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                            <?php endif;?>
                                        
                            <?php if($star_rating >= 1.3 && $star_rating <= 1.7):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star-half-alt checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 1.8 && $star_rating <= 2.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 2.3 && $star_rating <= 2.7):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star-half-alt checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 2.8 && $star_rating <= 3.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 3.3 && $star_rating <= 3.7):?>
                                <span><i class="fas fa-star big checked"></i></span>
                                <span><i class="fas fa-star big checked"></i></span>
                                <span><i class="fas fa-star big checked"></i></span>
                                <span><i class="fas fa-star-half-alt big checked"></i></span>
                                <span><i class="far fa-star big unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 3.8 && $star_rating <= 4.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 4.3 && $star_rating <= 4.7):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star-half-alt checked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 4.8 && $star_rating <= 5.0):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                            <?php endif;?>
                        </div>&nbsp;

                            <?php if(!empty($allReviews)):?>
                                <?php if($reviews_count == 1):?>
                                    <h3 class="line"><?php echo $star_rating."  "."out of"."  "."5"."  "."($reviews_count"." "."review)";?></h3><br><br><br>
                                <?php endif;?>

                                <?php if($reviews_count > 1):?>
                                    <h3 class="line"><?php echo (floor($star_rating * 10) / 10)."  "."out of"."  "."5"."  "."($reviews_count"." "."reviews)";?></h3><br><br><br>
                                <?php endif;?>
                            <?php endif ;?>

                            <?php if(empty($allReviews['review_comment'])):?>
                                 <h3><?php echo 'There are currently no comments for this product.';?></h3>
                            <?php endif ;?>
                            
                            <?php for($i=0;$i<count($allReviews);$i++):?>
                                
                                <?php $allReview = $allReviews[$i];?>
                                    <?php// var_dump($allReview);?>
                               
                                    
                                        <div class="review_box">
                                        
                                                <p><?php echo $i+1;?>.
                                                &nbsp;&nbsp;&nbsp;<i class="fas fa-user"></i><strong><?php echo $allReview['user_name'];?></strong> &nbsp;&nbsp;&nbsp;
                                    
                                    
                                                <?php if($allReview['star'] == 0):?>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                <?php endif;?>    
                                    
                                                <?php if($allReview['star'] == 1):?>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                <?php endif;?>

                                                <?php if($allReview['star'] == 2):?>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                <?php endif;?>

                                                <?php if($allReview['star'] == 3):?>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                <?php endif;?>
                                    
                                                <?php if($allReview['star'] == 4):?>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="far fa-star unchecked"></i></span>
                                                <?php endif;?>

                                                <?php if($allReview['star'] == 5):?>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                    <span><i class="fas fa-star checked"></i></span>
                                                <?php endif;?>

                                                &nbsp;&nbsp;(Date:&nbsp;<?php echo $allReview['created_at'];?>)
                                            
                                            </p>

                                        
                                            <!-- <dt>&nbsp;&nbsp;&nbsp;Review Comment:</dt>-->
                                            <?php if($allReview['review_comment'] != null || $allReview['review_comment'] > 1):?>
                                                <dd><?php echo nl2br($allReview['review_comment']);?></dd>                                   
                                            <?php endif;?>

                                        </div>
                            <?php endfor;?>
                    </div><!--typein2-->

                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>
