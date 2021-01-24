<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>inquiry</p>
</body>
</html>

<?php $order_details = getOrderDetails($order_id);?>
                            <?php for($j=0;$j<count($order_details);$j++):?>
                                <?php $order_detail = $order_details[$j];?>

                                  <?php $product_details = getProductDetailsByDetailId($order_detail['detail_id']);?>
                                    <?php foreach($product_details as $product_detail):?>

                                              <?php $product_ids = getProductIdByDetailId($order_detail['detail_id']);?>
                                              <?php foreach($product_ids as $product_id):?>
                                                  <?php $product_datas = getProductDatasById($product_id);?>
                                                      <?php foreach($product_datas as $product_data):?>
                                                        <?php// var_dump($product_data['product_name']);?>
                                                     
                            
                                          <p class="straight"><?php echo $j+1;?>.&nbsp;<strong><?php echo "{$product_data['product_name']}"?>(detail_id:<?php echo "{$product_detail['id']}";?>)</strong></p>
                                          <div class="row_box1"> 
                                          <div class="item">
                                                <div class="img_box">
                                                    <img src="/public/manage/<?php echo "{$product_data['save_path']}";?>" alt="product_image" >
                                                </div>
                                          </div>


                                          <p class="item">Price:&nbsp;￥<?php echo n("{$product_detail['price']}");?></p>
                                          <p class="item">Color:&nbsp;<?php echo "{$product_detail['color']}";?></p>
                                          <p class="item">Size:&nbsp;<?php echo "{$product_detail['size']}";?></p>
                                          <p class="item"><?php echo 'Qty:'.' '."{$order_detail['qty']}";?></p>
                                          <!--<p class="item">Total:&nbsp;¥<?php// echo n("{$s_total}");?></p>-->

                                          <div class=item>
                                            
                                            <a class="btn_b bg_orange" href="./../review.php?product_id=<?php echo $product_data['id'] ?>">Review</a>
                                          </div>
                                          <?php endforeach;?>
                                    <?php endforeach;?>     
                                        
                                <?php endforeach;?>




                                <p class="straight"><?php echo $j+1;?>.&nbsp;<strong><?php echo "{$product_data['product_name']}"?>(detail_id:<?php echo "{$product_detail['id']}";?>)</strong></p>
                                                                    <!---->
                                <?php $order_details = getOrderDetails($order_id);?>
                            <?php for($j=0;$j<count($order_details);$j++):?>
                                 
                              <?php $order_detail = $order_details[$j];?>
                                 <?php $product_details = getProductDetailsByDetailId($order_detail['detail_id']);?>
                                    <?php foreach($product_details as $product_detail):?>

                                <?php $product_ids = getProductIdByDetailId($product_detail['id']);?> 
                                               <?php foreach($product_ids as $product_id):?>
                                               <?php// var_dump($product_id);?>
                                                  
                                               
                                               
                                                  <?php $product_datas = getProductDatasById($product_id);?>
                                                    <?php foreach($product_datas as $product_data):?>
                                                        <?php// var_dump($product_data['product_name']);?>





<!--------------------------------------------------------------------------------------------------------------->

                <div class="order_box">

                <!--ユーザーidでorders tableからこのユーザーのz全注文データを取得-->
                <?php $all_orders = getAllOrders($user_id);?>
                <?php //var_dump($all_orders);?>
                <?php foreach($all_orders as $all_order):?>
                <?php //var_dump($all_order);?>
                <?php//$order_id = $all_order['id'];?>

              <?php for($i=0;$i<count($all_orders);$i++):?>
                    <?php $all_order = $all_orders[$i];?>
                    <p><?php echo $i+1;?>.&nbsp;<strong>Order no:<?php echo $all_order['id'];?>(Purchase date:<?php echo "{$all_order['ordered_at']}";?>)</strong></p>
                    
                    <div class="group_box">
                       
                    <!--order idの時のproduct detail_idを取得する-->
                    <?php $allproductDetailIds = getAllproductDetailIdsByorderId($all_order['id']);?>

                       <!--product detail_idの数を使ってfor文で回す-->
                        <?php var_dump(count($allproductDetailIds));?>
                        <?php for($j=0;$j<count($allproductDetailIds);$j++):?>
                        <?php  $allproductDetailId = $allproductDetailIds[$j];?>
                             <?php// var_dump($allproductDetailId);?><!--$allproductDetailId['detail_id']でdetail_id。これはorders table に入っている。商品の色サイズ値段等のデータ。-->
                             <?php $detail_id = $allproductDetailId['detail_id'];?>
                             <?php// var_dump($detail_id);?>
                             <!--$detail_id　を使ってprodcut_details table からid($detail_idと同じ)とpriceとproduct_idの３つを取得する。-->
                                 <?php $id_productId_Prices = getId_ProductId_PriceBydetailId($detail_id);?>
                                      <?php// var_dump($id_productId_Prices);?><!--product_details table のid, product_id,priceが入っている。-->
                                      <?php foreach($id_productId_Prices as $id_productId_Price):?>
                                          <?php $detail_id = $id_productId_Price['id'];?>
                                          <?php $price = $id_productId_Price['price'];?>
                                          <?php $product_id = $id_productId_Price['product_id'];?>
 
                                               <!--次に$product_idを使って、products table からproduct_name,save_pathを取得する。-->



                        </div><!--row_box-->

                                            <?php// endforeach;?>
                                              <?php// endforeach;?>        
                                       <?php endforeach;?>
                      <?php endfor;?><!--j-->
                    </div><!--group_box->



                            <div class="row_box1">
                              
                                <!--<p class="item"><strong>Total Qty:&nbsp;<?php// echo $sub_datas['total_qty'];?></strong></p>-->
                                <div>
                                <p class="item"><strong>Sub total:&nbsp;￥<?php echo n($all_order['sub_total']);?></strong></p>
                                <p class="item"><strong>Tax:&nbsp;￥<?php echo n($all_order['tax']);?></strong></p>
                                <p class="item"><strong>Shipping fee:&nbsp;￥<?php echo n($all_order['shipping_fee']);?></strong></p>
                                <p class="item"><strong>Total charge:&nbsp;￥<?php echo n($all_order['total_charge']);?></strong></p>
                            </div><!--row_box-->

              <?php endfor;?><!--i-->
        </div><!--order_box-->
