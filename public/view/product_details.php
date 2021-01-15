<?php
session_start();

ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

var_dump($_GET['id']);
if($_GET['id']){
    $product_id = $_GET['id'];
}

$commons = getProductData($product_id);
//var_dump($commons);
foreach($commons as $common){
    //echo $common['product_name'];
}

$details = getProductDetails($product_id);
//var_dump($details);

$colors = getProductColorsCount($product_id);
var_dump($colors);//2




$productsByColor = getProductByColor($product_id);
//print_r($productsByColor);

//$details as $detail){
    //echo $detail['price'];
//}

//$productCount = getProdcutsCount(13);
//var_dump($productCount);

//no need
//$colors = getProdcutsColors($product_id);
//var_dump($colors);

//$details = getProductDetails(13);
//var_dump($products);
//foreach($details as $detail){
    //foreach($detail as $item){
       // print_r($item);
    //}
//}


?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../../css/detail.css">
        <link rel="stylesheet" href="./../../css/header.css">
    
    </head>

    <body>

     　<?php include './../../header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                <h1 class="form_title orange"><?php echo $common['product_name'];?></h1>
                    <div class="typein">

                            <div class="left_side">
                              <!--<div class="product_box">-->

                                <div class="img_box">
                                  <img src="/manage/<?php echo "{$common['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                  <a class="link_aa favorite" href="./?favolite_product_id=<?php echo h($common['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                </div>

                                <div class="text_part">
                                  <h2 class="product_name"><?php echo $common['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($details[0]['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $common['description'];?></h3>
                                </div>
                            </div>

                            <div class="right_side">
                                <h3>Choose color and size from below.</h3>

                                <form action="./cart.php" method="post">
                                <?php //for($i;$i<$colors;$i++):?>
                                    <table>
                                        <tr><td><?php $colors;?></td></tr>
                                    </table>
                                <?php// endfor ;?>
                                　　<p>商品A</p>
                                    <input type="hidden" name="item_id" value="1" >
                                    <input type="hidden" name="item_name" value="商品A" >
                                    <input type="hidden" name="item_count" value="1" >
                                    <input type="hidden" name="count_updated_method" value="add">
                                    <input class="btn bg_green" type="submit" value="Add Cart">
                                </form>
                            </div>
                    </div><!--typein-->
                            
                    <div class="">
                        <div class="review">review</div>
                    </div>

                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>