<?php
//----ログイン状態-----------------
session_start();

if ($_SESSION['login']= true) {
    $users = $_SESSION['user'];
  }

  foreach($users as $user){
    //var_dump($user['id']);
  }
  $user_id = $user['id'];
//--------------------------------
//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors',true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

if(is_array($_POST['detail_id'])){
    $detail_ids = $_POST['detail_id'];
        foreach($detail_ids as $detail_id){
          // echo $detail_id;
        }

        if(!empty($_POST) && $detail_id !== null){

            for($i=0;$i<count($detail_ids);$i++){
                $posted_detail = [
                    'detail_id' => $detail_ids[$i],
                    'detail_count' => $_POST['detail_count'],
                ];

               //カートが空の時、postで送られて来たデータを代入する。
               if(empty($_SESSION['shopping_cart'])){
                   $_SESSION['shopping_cart'] = [];
                   //↑初期化
                   //↓sessionにpostの内容を入れる。　　　　　
                   $_SESSION['shopping_cart'][]= $posted_detail;

                   //カートが空でない時
                }else{
                   //カートに登録するフラグ？？　tureなら登録する。　emptyでなかったら、それをショッピングカートに入れる。そして下で、カートの中身を一つひとつ照合処理。
                   $register = true;

                   // sessionに既に存在するdetail_idと今送られてきたpostのdetail_idで照合。合致したら、幾つあるかのチェック。なのでforeach使ってる。$indexはインデックス番号で０が商品Aで１が商品B。
                   foreach($_SESSION['shopping_cart'] as $index => $detail){

                        if ($detail['detail_id'] == $posted_detail['detail_id']){
                         //セッションに入っているdetail_id      postの方のdetail_id.(cart.phpで追加される商品のidとindex.phpの２ページに対応している。いづれかのページで追加されるdetail_id)

                            //index.phpのカートに入れるボタンを何度も押された場合、その回数に従ってitem_countが増える。（上書き作業）
                            if(isset($_POST['count_updated_method']) && $_POST['count_updated_method'] === 'add'){
                                $posted_detail['detail_count'] += $detail['detail_count'];
                                //postのカウント数？それともindex.php　　+　　　　　　　セッションのカウント数?それともこのページのセレクトボックスのカウント数
                                //index.phpの「カートに入れる」で増えた数に足すのは、　cart.phpのセレクトボックスで選ばれた数（cart.phpのページで操作するなら、index.phpの「カートに入れる」を押すことではもう増えない。変わらない数に足す）
                                //ここで＋を付けないで＝のみだと、index.phpの「カートに入れる」で追加した分＝（必ず一つずつ）が、cart.phpで表示される個数(select box)に反映されない。なので、＋が必要。
                            }

                            $_SESSION['shopping_cart'][$index] = $posted_detail;
                        
                            //上書き作業が終わったら、追加はもうしないので。
                            $register = false;

                        }

                    }

                    if($register){
                        $_SESSION['shopping_cart'][] = $posted_detail;
                    }
                }
            }

        }
}else{
    $detail_id = $_POST['detail_id'];

    if(!empty($_POST) && $detail_id !== null){

            $posted_detail = [
                'detail_id' => $detail_id,
                'detail_count' => $_POST['detail_count'],
            ];

            //カートが空の時、postで送られて来たデータを代入する。
            if(empty($_SESSION['shopping_cart'])){
                $_SESSION['shopping_cart'] = [];
                //↑初期化
                //↓sessionにpostの内容を入れる。　　　　　
               $_SESSION['shopping_cart'][]= $posted_detail;
        
            //カートが空でない時
            }else{
               //カートに登録するフラグ？？　tureなら登録する。　emptyでなかったら、それをショッピングカートに入れる。そして下で、カートの中身を一つひとつ照合処理。
               $register = true;

               // sessionに既に存在するdetail_idと今送られてきたpostのdetail_idで照合。合致したら、幾つあるかのチェック。なのでforeach使ってる。$indexはインデックス番号で０が商品Aで１が商品B。
               foreach($_SESSION['shopping_cart'] as $index => $detail){
          
                    if ($detail['detail_id'] == $posted_detail['detail_id']){
                    //セッションに入っているdetail_id      postの方のdetail_id.(cart.phpで追加される商品のidとindex.phpの２ページに対応している。いづれかのページで追加されるdetail_id)

                         //index.phpのカートに入れるボタンを何度も押された場合、その回数に従ってitem_countが増える。（上書き作業）
                         if(isset($_POST['count_updated_method']) && $_POST['count_updated_method'] === 'add'){
                                $posted_detail['detail_count'] += $detail['detail_count'];
                                //postのカウント数？それともindex.php　　+　　　　　　　セッションのカウント数?それともこのページのセレクトボックスのカウント数
                                //index.phpの「カートに入れる」で増えた数に足すのは、　cart.phpのセレクトボックスで選ばれた数（cart.phpのページで操作するなら、index.phpの「カートに入れる」を押すことではもう増えない。変わらない数に足す）
                                //ここで＋を付けないで＝のみだと、index.phpの「カートに入れる」で追加した分＝（必ず一つずつ）が、cart.phpで表示される個数(select box)に反映されない。なので、＋が必要。
                        }


                        $_SESSION['shopping_cart'][$index] = $posted_detail;
                   
                        //上書き作業が終わったら、追加はもうしないので。
                        $register = false;
                    }
               }

               if($register){
                   $_SESSION['shopping_cart'][] = $posted_detail;
               }
            }
    }

}


$details = $_SESSION['shopping_cart'];

if($_SESSION['shopping_cart']){
    $total_in_cart = 0;
    foreach($details as $detail){
        if(!empty($details)){
             $total_in_cart += $detail['detail_count'];
        }
    } 
}



//商品のproduct_nameと画像を取得
$productDatas = getProductDataByDetail($detail['detail_id']);

?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>shpping cart</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/cart.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>

　　　　  <?php include './../header.php';?>


        <label for="check">
            <div class="wrapper">
                <div class="container">
                　  <div class="typein">
                　　    <h1 class="form_title orange">Your Shopping Cart</h1>

                        <?php $total_s_total= [];?>

                        <?php if(empty($detail)):?>
                    　　      <h2>Your cart is empty.</h2>
                        <?php else : ?>
                             <!--details=$_SESSION['shopping_cart']ということ-->
                             <?php// foreach ($details as $detail): ?>
                             <?php for($i=0;$i<count($details);$i++):?>
                                <?php $detail = $details[$i];?>
                                <!--$itemsは商品AとBの両方のデータ。 $itemは商品ごとのデータ。ここでの$itmeを使って、下のようにhiddenでvalueを書いている？-->
                                <?php// var_dump($detail);?>
                                <?php $product_datas = getProductByDetail($detail['detail_id']);?>

                                    <?php foreach($product_datas as $product_data):?>
                                        <?php //echo $product_data;?>
                                    <?php endforeach;?>

                                    <?php $product_id = $product_data['product_id'];?>
                                    <?php $product_name_files= getProductNameFile($product_id);?>
                                    <?php foreach($product_name_files as $product_name_file):?>
                                        <?php// echo $product_name_file;?>
                                    <?php endforeach;?>

　　　　　　　　　　　　　　            <!--商品の在庫数を取得-->
                                   <?php $detail_stocks = getStockByDetailId($detail['detail_id']);?>
                        　　        <?php //var_dump($detail_stocks);?>
                        　　        <?php foreach($detail_stocks as $detail_stock):?>
                                         <?php $stock =  $detail_stock['stock'];?>
                                         <?php //echo $stock;?>
                                    <?php endforeach;?>



                                    <div class="pickup_box">
                                            <p><?php echo $i+1;?>.&nbsp;<strong><?php echo "{$product_name_file['product_name']}"?>&nbsp;&nbsp;(Stock:&nbsp;<?php echo $detail_stock['stock'];?>&nbsp;left)</strong></p>
                                            <?php if($detail_stock['stock'] > 0 && $detail['detail_count'] > $detail_stock['stock']):?>
                                                <p class="attention"><strong><?php echo"Sorry. We don't have enough stock. Please change the Qty to less than".' '.$detail_stock['stock'].".";?></strong></p>
                                            <?php elseif($detail_stock['stock'] < 1 && $detail['detail_count'] > $detail_stock['stock']):?>
                                                <p class="attention"><strong><?php echo"Sorry. This product is currently out of stock.";?></strong></p>
                                            <?php endif;?>

                                            <div class="row_box">
                                                <div class="item">
                                                    <div class="img_box item">
                                                        <img src="/manage/<?php echo "{$product_name_file['save_path']}";?>" alt="product_image" >
                                                    </div>
                                                </div>

                                                <div class="item">Price:&nbsp;￥<?php echo n("{$product_data['price']}");?></div>
                                                <div class="item">Color:&nbsp;<?php echo "{$product_data['color']}";?></div>
                                                <div class="item">Size:&nbsp;<?php echo "{$product_data['size']}";?></div>

                                                <div class="item">
                                                    <form action="./shopping_cart.php" method="post">
                                                        <!--echoの部分はhtmlエスケープなしでOK？？重要なデータではない？？ hiddenの時はエスケープいらない？ あと、フォームにはsubmitボタンが必要な気がするけど、なくてもちゃんとpostで飛んでる。。-->
                                                        <input type="hidden" name="detail_id" value="<?php echo $detail['detail_id']?>">
                                                        <input type="hidden" name="detail_count" value="<?php echo $detail['detail_count']?>">

                                                        <?php echo 'Qty:'.$detail['detail_count']; ?>
                                                        <select name="detail_count" class="change_detail_count">
                                                                <option value="1" <?php echo $detail['detail_count'] == '1' ? 'selected' : '' ?>>Qty:1</option>
                                                                <option value="2" <?php echo $detail['detail_count'] == '2' ? 'selected' : '' ?>>Qty:2</option>
                                                                <option value="3" <?php echo $detail['detail_count'] == '3' ? 'selected' : '' ?>>Qty:3</option>
                                                                <option value="4" <?php echo $detail['detail_count'] == '4' ? 'selected' : '' ?>>Qty:4</option>
                                                                <option value="5" <?php echo $detail['detail_count'] == '5' ? 'selected' : '' ?>>Qty:5</option>
                                                        </select>
                                                    </form>
                                                </div><!--item-->

                                                <?php $s_total = $product_data['price'] * $detail['detail_count']?>
                                                <div class="item">Total:&nbsp;¥<?php echo n("{$s_total}");?></div>

                                                <div class="item">
                                                    <a class="btn bg_gray" href="./shopping_delete.php?detail_id=<?php echo $detail['detail_id'] ?>">Delete</a>
                                                </div>

                                            </div><!--row_box-->

                                    </div><!--pickup_box-->

                                    <?php $total_s_total[] += $s_total;?>

                            <?php endfor ;?><!--$i-->
                        <?php endif ;?><!--if(empty($detail))-->

                        <?php $sum_total_s_total = array_sum($total_s_total);?>


                       <div class="row_box2">
                            <p class="item">Total Qty:&nbsp;<?php echo "{$total_in_cart}";?></p>
                            <p class="item">Sub Total:&nbsp;¥<?php echo n("{$sum_total_s_total}");?></p>
                            <?php $tax = $sum_total_s_total*0.1;?>
                            <p class="item">Tax:&nbsp;¥<?php echo n("{$tax}");?></p>
                            
                            <?php if(!$user && !empty($total_in_cart)):?>
                                <p class="attention"><strong><?php echo'Please log in to proceed.';?></strong></p>
                                <?php $total_charge = 0;?>
                            <?php elseif(!$user):?>
                                 <p class="attention"><strong><?php echo'You are not logged in.';?></strong></p>
                            <?php elseif(!empty($_SESSION['shopping_cart']) && $user):?>
                                <?php $ship_area = $user['ship_area'];?>
                                <?php $shipping_fee = setShippingFee($user['ship_area']);?>
                                <p class="item">Sipping Fee:&nbsp;¥<?php echo n("{$shipping_fee}");?></p>
                                <?php $sub_tax_shippingfee = [$sum_total_s_total, $tax, $shipping_fee];?>
                                <?php $total_charge = array_sum($sub_tax_shippingfee);?>
                                <p class="item"><strong>Total Charge:&nbsp;¥<?php echo n( "{$total_charge}");?><strong></p>
                            <?php endif;?>
                        </div><!--row_box2-->


                        <!--purchase.phpへ送るデータとして３つの配列がある。　$_SESSION['shopping_cart']、$_SESSION['sub_datas']、　$_SESSION['stock_count']-->
                        <?php if(!empty($_SESSION['shopping_cart']) && $user):?>
                            <?php $_SESSION['sub_datas'] = ['total_qty' => $total_in_cart, 'sum_total_s_total' => $sum_total_s_total, 'tax' => $tax, 'shipping_fee' => $shipping_fee, 'total_charge' => $total_charge,];?>
                        <?php endif;?>


                        <div class="link_box">
                            <a class="link_a line_color_orange" href="./../index.php">Continue shopping</a>

                            <?php if($user && !empty($total_in_cart) && $detail['detail_count'] <= $detail_stock['stock']):?>
                                <a class="link_a line_color_green" href="./purchase.php">Proceed with purchase</a>
                            <?php else:?>
                                <a class="link_a line_color_green" href="./shopping_cart.php">Proceed with purchase</a>
                            <?php endif;?>
                        </div>
     


                        <script>
                            // 数量が変更されたら更新を行う

                            const detailCounts = document.querySelectorAll('.change_detail_count');

                            detailCounts.forEach(function(elem) {

                               elem.addEventListener('change', function(elem) {

                                    //elemのターゲット属性のparentNode(親要素) をformという定数に代入。
                                   const form = elem.target.parentNode;

                                   //formの中から input[name="item_count"]の値（value）を取得？？　
                                   form.querySelector('input[name="detail_count"]').value = elem.target.value

                                   form.submit();

                                });
                            });

                        </script>

　　　　　　　　　　　　</div><!--typein-->
                </div><!--container-->
        　　　</div><!--wrappr-->
        </label>
    </body>
</html>