<?php
//----ログイン状態-----------------
session_start();

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  
  foreach($user as $user_data){
    //echo $user_data['id'];
  }

  $user_id = $user_data['id'];
  //var_dump($user_id);
//--------------------------------

//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../private/database.php';
require_once './../private/functions.php';

$errors = [];
//var_dump($_POST);
if(!empty($_POST)){

    $product_id = $_POST['product_id'];

    $code_name = $_POST['code_name'];
    if(!$code_name){
        $errors[] = '名前を入力して下さい。';
    }

    $review_comment = $_POST['review_comment'];
    if(!$review_comment || 400 < strlen($review_comment)){
        $errors[] = 'レビューは400字以下で入力して下さい。';
    }

    if(count($errors) === 0){

        $hasPosted = postedReview($user_id, $product_id, $code_name, $review_comment);
        //echo '<script type="text/javascript"> alert("Your review was posted successfully.")</script>';
        header('Location: /view/product_details.php?product_id='.$product_id);
        if(!$hasPosted){

            echo '<script type="text/javascript"> alert("Your review failed to be posted.")</script>';
            $errors[] = 'Your review failed to be posted.';
        }
    }

}
 


//var_dump($_GET);
if($_GET['product_id']){
    $product_id = $_GET['product_id'];
}else if($_POST[$product_id]){
    $product_id = $_POST[$product_id];
}

$commons = getProductData($product_id);
//var_dump($commons);
foreach($commons as $common){
    //echo $common['product_name'];
}

$productsByColor = getProductByColor($product_id);

//値段を出すために使用
$details = getProductDetails($product_id);
//print_r($details[3]['id']);

//色を取得そして、色の数をcount()で出す。
$colors = getProductColors($product_id);
//var_dump($colors[1]['color']);
$count_colors = count($colors);
//var_dump($count_colors);//2

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


//----------------------------------------------
if($_SESSION['shopping_cart']){
    $total_in_cart = 0;

    if($_SESSION['shopping_cart']){

        foreach($_SESSION['shopping_cart'] as $detail){
            if(!empty($details)){
                 $total_in_cart += $detail['detail_count'];
            }
        }
     }
}
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Review</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/review.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>

     　 <?php include './header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">

                    <h1 class="form_title orange">Review of <?php echo $common['product_name'];?></h1>

                    <div class="typein">
                        <div class="left_side">
                            <div class="img_box">
                                <img src="/manage/<?php echo "{$common['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                            </div>

                            <div class="text_part">
                                <h2 class="product_name"><?php echo $common['product_name'];?></h2>
                                <h2>¥&nbsp;<?php echo n($details[0]['price']);?>&nbsp;(Tax not included)</h2>
                                <h3><?php echo $common['description'];?></h3>
                            </div>
                        </div><!--left_side-->

                        <div class="right_side">
                              
                            <?php if($count_colors>1):?>
                                <h3>Color(s) ans Size(s)</h3>

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
                                                </td>
                                            <?php else:?>
                                               <?php echo '';?>
                                            <?php endif;?>
                                        <?php endfor ;?>
                                    </tr>
                                </table>


                                <!--the case of one color------------------------------------------------>
                                <?php else:?>
                                    <h3>Color(s) ans Size(s)</h3>

                                    <table border=1>
                                    　　 <tr><th><?php echo $detailCSs[0][0]['color'] ;?></th></tr>

                                        <tr><td class="first">Size:<?php echo $detailCSs[0][0]['size'];?><br>
                                            </td>
                                    　　 </tr>

                                        <?php for($b=0;$b<8;$b++):?>
                                             <?php if(!empty($detailCSs[0][$b+1])):?>
                                                 <tr><td class="second">Size:<?php echo $detailCSs[0][$b+1]['size'];?><br>
                                                      </td>
                                    　　         </tr>
                                             <?php else:?>
                                                <?php echo '';?>
                                             <?php endif;?>
                                        <?php endfor;?>
                                    </table>

                                <?php endif;?>

                        </div><!--right_side-->
                    </div><!--typein-->

                    <div class="typein2">

                            <h2 class="form_title2">Write a review of <?php echo $common['product_name'];?></h2>
                            
                            <?php if(isset($errors)): ?> 
                               <ul class="error-box">
                                  <?php foreach($errors as $error): ?> 
                                     <li><?php echo $error; ?></li>
                                  <?php endforeach ?> 
                               </ul>
                            <?php endif ?>
                            <br>
                            
                            <form action="./review.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo "{$product_id}";?>">

                                <p class="form_item">Code Name</p>
                                   <input type="text" class="form_text" name="code_name">
                                <br>
                                <br>

                                <div class="form_item"><p>Review Comment&nbsp;(in 400 words or less)</p></div>
                                   <textarea name="review_comment" id="review_comment" cols="100" rows="6"></textarea>
                                <br>
                                <input type="submit" value="Send" class="btn bg_green">
                            </form>
                            
                    </div><!--typein2-->

                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>
