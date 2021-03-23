<?php
session_start();

if (!$_SESSION['login']) {
    header('Location: /manage/mng_login.php');
    exit();
  }

  if ($_SESSION['login']= true) {
    $mgrs = $_SESSION['mgr'];
  }
  //var_dump($mgrs);
  foreach($mgrs as $mgr){
    //var_dump($mgr['id']);
  }
  $managers_id = $mgr['id'];
  //var_dump($managers_id);
//------------------------------------------------

ini_set('display_errors', true);
//error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';
//-----------------------------------------------------------------------

if($_POST){
    var_dump($_POST);
    $shipping_status = $_POST['shipping_status'];
    $order_id = $_POST['order_id'];

    $chage_shipping_status = changeShippingStatus($_POST['order_id'], $_POST['shipping_status']);
    //header("Location: ./orders_list.php?order_id=".$order_id);
    header("Location:" .$_SERVER['PHP_SELF']."#$order_id");
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders list</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/orders_list.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>

        <?php include './../manage/mng_header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                　   <div class="typein">
                　　　    <h1 class="form_title orange">Orders List</h1>

                         <div class="order_box">

                             <!--ユーザーidでorders tableからこのユーザーの全注文データを取得-->
                            <?php $all_orders = getAllOrdersForLists();?>
                            <?php //var_dump($all_orders);?>
                            <?php foreach($all_orders as $all_order):?>
                                <?php //var_dump($all_order);?>
                            <?php endforeach;?>
                            <?php $order_id = $all_order['id'];?>
                         
                              
                            <?php for($i=0;$i<count($all_orders);$i++):?>
                                <?php $all_order = $all_orders[$i];?>
                                
                                <div class="row_box3">
                                    <div class="row_item"><h3 class="begin"><?php echo $i+1;?>.&nbsp;<strong>Order No:&nbsp;<?php echo $all_order['id'];?>&nbsp;&nbsp;(Purchase Date:&nbsp;<?php echo "{$all_order['ordered_at']}";?>)</strong></h3><a name=<?php echo'                       ';?><?php echo h($all_order['id']);?>></a></div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        
                                    <div class="row_item">
                                    
                                        <form action="orders_list.php" method="post">

                                            <?php if($all_order['shipping_status'] ==2):?>
                                                <div class=item>
                                                   <input class="btn_b bg_orange" type="submit" value="Shipped">
                                                   <input type="hidden" name="order_id" value="<?php echo $all_order['id'];?>">
                                                   <input type="hidden" name="shipping_status" value="1">
                                                </div>

                                            <?php elseif($all_order['shipping_status'] == 1):?>
                                                <div class=item>
                                                   <input class="btn_b bg_green" type="submit" value="Yet">
                                                   <input type="hidden" name="order_id" value="<?php echo $all_order['id'];?>">
                                                   <input type="hidden" name="shipping_status" value="2">
                                                </div>
                                            <?php endif;?>
                                            
                                        </form>
                                    </div><!--row_item-->
                                </div><!--row_box3-->


                                <?php $users = findUserByUserId($all_order['user_id']);?>
                                <?php// var_dump($users);?>
                                <?php foreach($users as $user):?>
                                        <p>User ID:&nbsp;<?php echo$user['id'];?>&nbsp;&nbsp;&nbsp;User Name:&nbsp;<?php echo $user['title'].' '.$user['usr_name'];?></p>
                                        <p>Address:&nbsp;〒<?php echo$user['postal'];?>&nbsp;&nbsp;<?php echo $user['addr_pref'].$user['addr_city'].$user['addr_last'];?></p>
                                <?php endforeach;?>
                                          
                                <!--order_details table から、order_idを使って、order_idの時のdetail_id（product_details table のid）を取得する.それと購入時の値段と数量も-->
                                <?php $alls_order_details = getAllOrderDetailsByOrderId($all_order['id']);?>
                                <?php// var_dump($alls_order_details);?>

                                <?php $j =0;?>
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

                                                        <br>
                                                        <?php $j +=1;?>
                                                        <p><?php echo $j;?>.&nbsp;<strong><?php echo $product_name;?>&nbsp;&nbsp;(detail_id:<?php echo $detail_id;?>)</strong></p>
                                          
                                                        <div class="row_box2">
                                                            <div class="item">
                                                                    <div class="img_box">
                                                                        <img src="/manage/<?php echo $save_path;?>" alt="product_image" >
                                                                    </div>
                                                            </div><!--item-->

                                                            <div class="item">Price:&nbsp;￥<?php echo n($price);?></div>
                                                            <div class="item">Color:&nbsp;<?php echo $color;?></div>
                                                            <div class="item">Size:&nbsp;<?php echo $size;?></div>
                                                            <div class="item"><?php echo 'Qty:'.' '.$qty;?></div>
                                                        </div>
                                                    <?php endforeach;?><!--($productName_savePaths-->
                                            <?php endforeach;?><!--$id_productId_color_sizes-->
                                    <?php endforeach;?><!--$alls_order_details-->
                    
                                    <div class="row_box1">
                                        <div class="item1"><strong>Sub total:&nbsp;￥<?php echo n($all_order['sub_total']);?></strong></div>
                                        <div class="item1"><strong>Tax:&nbsp;￥<?php echo n($all_order['tax']);?></strong></div>
                                        <div class="item1"><strong>Shipping fee:&nbsp;￥<?php echo n($all_order['shipping_fee']);?></strong></div>
                                        <div class="item1"><strong>Total charge:&nbsp;￥<?php echo n($all_order['total_charge']);?></strong></div>
                                    </div><!--row_box-->

                            <?php endfor;?><!--i-->
                      </div><!--order_box-->

                   </div><!--typein-->
                </div><!--container-->
        　　　</div><!--wrappr-->
        </label>
    </body>
</html>











        