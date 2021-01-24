<?php
//----ログイン状態-----------------
session_start();

if (!$_SESSION['login']) {
    header('Location: /public/account/login.php');
    exit();
  }

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }

  foreach($user as $user_data){
    //echo $user_data['tel'];
}

$user_id = $user_data['id'];
var_dump($user_id);
//--------------------------------

//against click junction ボタンがある時はこれいる？？いらない？？
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors',true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

//var_dump($_SESSION['shopping_history']);

// 1 ユーザーidでorders tableからこのユーザーのz全注文データを取得
$all_orders = getAllOrders($user_id);
//var_dump($all_orders);
foreach($all_orders as $all_order){
 //var_dump($all_order);

 $order_id = $all_order['id'];
}

// 2 orders table のid を使って、order_details から詳細を取得する。$order_id 　下記の部分は消さないでのとしておく！！
//$order_details = getOrderDetails(22);//$order_id が入るけど、試しに２２。
//var_dump($order_details);
//var_dump(count($order_details));
//foreach($order_details as $order_detail){
  //var_dump($order_detail);

//}


//$productDetails = getProductDetailsByDetailId(8);
//var_dump($productDetails);

//2段階に分けて、detail_idからproducts tableのproduct_name とsave_pathを取得
//１　detail_idからproduct_idを取得
//$product_ids = getProductIdByDetailId(8);
//foreach($product_ids as $product_id){
  //var_dump($product_id);
//}

// 2 product_idからproduct_name とsave_pathを取得
//$product_datas = getProductDatasById($product_id);
//var_dump($product_datas);
//foreach($product_datas as $product_data){
  ////var_dump($product_data['product_name']);
//}
echo 'ううう';
//var_dump($productDatas);
//-----------------------------------------------------------------------


//最後にこれを空にする。
$_SESSION['shopping_history'] = [];

$total_in_cart = 0;
foreach($_SESSION['shopping_cart'] as $detail){
  if(!empty($_SESSION['shopping_cart'])){
    $total_in_cart += $detail['detail_count'];
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase history</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../../public/css/history.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
</head>
<body>

　　　　<?php include './../header.php';?>


        <label for="check">
            <div class="wrapper">
                <div class="container">
                　<div class="typein">
                　　　<h1 class="form_title orange">Your purchase history</h1>

                       <div class="order_box">

                          <!--ユーザーidでorders tableからこのユーザーの全注文データを取得-->
                          <?php $all_orders = getAllOrders($user_id);?>
                          <?php //var_dump($all_orders);?>
                          <?php// foreach($all_orders as $all_order):?>
                            <?php //var_dump($all_order);?>
                              <?php $order_id = $all_order['id'];?>

                                  <?php for($i=0;$i<count($all_orders);$i++):?>
                                        <?php $all_order = $all_orders[$i];?>
                                        <h3 class="begin"><?php echo $i+1;?>.&nbsp;<strong>Order no:<?php echo $all_order['id'];?>(Purchase date:<?php echo "{$all_order['ordered_at']}";?>)</strong></h3>
                                        
                                    <!--<div class="group_box">-->
                                          
                                            <!--order_details table から、order_idを使って、order_idの時のdetail_id（product_details table のid）を取得する.それと購入時の値段と数量も-->
                                            <?php $alls_order_details = getAllOrderDetailsByOrderId($all_order['id']);?>
                                            <?php// var_dump($alls_order_details);?>
                                            <?php foreach($alls_order_details as $alls_order_detail):?>
                                              <?php $price = $alls_order_detail['price'];?>
                                              <?php $qty = $alls_order_detail['qty'];?>


                                              <?php $detail_id = $alls_order_detail['detail_id'];?>
                                              <!--次に$detail_idを使って、product_details tableから、id,product_id,色とサイズを取得する。-->
                                               <?php $id_productId_color_sizes = get_Id_productId_color_size_BydetailId($detail_id);?>
                                               <?php //var_dump($id_productId_color_sizes);?>
                                               <?php foreach($id_productId_color_sizes as $id_productId_color_size):?>
                                                  <?php $color = $id_productId_color_size['color'];?>
                                                  <?php $size = $id_productId_color_size['size'];?>
                                                  <?php $product_id = $id_productId_color_size['product_id'];?>
                                                  

                                                  <!--最後に$product_idを使って、product tableから商品名と画像を取得する。-->
                                                    <?php $productName_savePaths = getProductName_savePath($product_id);?>
                                                    <?php// var_dump($productName_savePaths);?>
                                                    <?php foreach($productName_savePaths as $productName_savePath):?>
                                                      <?php $product_name = $productName_savePath['product_name'];?>
                                                      <?php $save_path = $productName_savePath['save_path'];?>


                                  
                                                      <?php// $j =0;?>
                                                      <?php// $j +=1;?>
                                                      <p><?php// echo $j++;?>.<strong><?php echo $product_name;?>(detail_id:<?php echo $detail_id;?>)</strong></p>
                                          <div class="item">
                                                <div class="img_box">
                                                    <img src="/public/manage/<?php echo $save_path;?>" alt="product_image" >
                                                </div>
                                          </div>
                                          <p class="item">Price:&nbsp;￥<?php echo n($price);?></p>
                                          <p class="item">Color:&nbsp;<?php echo $color;?></p>
                                          <p class="item">Size:&nbsp;<?php echo $size;?></p>
                                          <p class="item"><?php echo 'Qty:'.' '.$qty;?></p>
                                          
                                          <div class=item>
                                            <a class="btn_b bg_orange" href="./../review.php?product_id=<?php echo $product_id;?>">Review</a>
                                          </div>
                              
                                          <?php endforeach;?>
                                          <?php endforeach;?>
                                          <?php endforeach;?>
                                      
                    
                                          <div class="row_box1">
                                             
                                                    
                                              <p class="item"><strong>Sub total:&nbsp;￥<?php echo n($all_order['sub_total']);?></strong></p>
                                              <p class="item"><strong>Tax:&nbsp;￥<?php echo n($all_order['tax']);?></strong></p>
                                              <p class="item"><strong>Shipping fee:&nbsp;￥<?php echo n($all_order['shipping_fee']);?></strong></p>
                                              <p class="item"><strong>Total charge:&nbsp;￥<?php echo n($all_order['total_charge']);?></strong></p>
                                            </div><!--row_box-->

                                      <?php endfor;?><!--i-->
                                    
                      </div><!--order_box-->
        


                   </div><!--typein-->
                </div><!--container-->
        　　　</div><!--wrappr-->
        </label>
    </body>
</html>