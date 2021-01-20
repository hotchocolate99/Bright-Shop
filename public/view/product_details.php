<?php
//----ログイン状態-----------------
session_start();

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  $users_id = $user[0]['usr_id'];
//--------------------------------

ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';



//var_dump($_GET['id']);
if($_GET['id']){
    $product_id = $_GET['id'];
}

$commons = getProductData($product_id);
//var_dump($commons);
foreach($commons as $common){
    //echo $common['product_name'];
}
//$count_details = getCountProductDetails($product_id);
//var_dump($count_details);

//$details = getProductDetails($product_id);
//var_dump($details[0]['size']);//60

//$colors = getProductColorsCount($product_id);
//var_dump($colors);//2
//var_dump($colors[0]['count_colors']);
//var_dump($colors[1]['color']);//light gray





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

//値段を出すために使っている
$details = getProductDetails($product_id);
//print_r($details[3]['id']);

//失敗
/*foreach($details as $detail){
      echo $details;
        $detail['id'];

        $details3[]= ['id'=>$detail['id'],'color'=>$detail['color'],'size'=>$detail['size']];
        //print_r($details3[0]['id']);
    }*/
//}
//---------------------------------------------------------------------------------




//$details3 = getProductDetailsByColor($product_id);
//var_dump($details3);


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
 
     //真ん中はcount_colorsではなくて、サイズの数だけど、もうどうしたら良いのかわからない。。。$count('size')みたいな？？でも$detailCSs[]の中からどうやって取り出すの？
     //for($j=0;j<$count_colors;j++){
     //echo $detailCSs[$i][$j];
    //}

        //if($detailCSs[$i][$j]['color'] = $colors[$i]['color']){
          
         //   $color[$i][]= 
        //}else{}

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

$total_in_cart = 0;
foreach($_SESSION['shopping_cart'] as $detail){
  if(!empty($details)){
    $total_in_cart += $detail['detail_count'];
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
        <link rel="stylesheet" href="./../../public/css/detail.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
    
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
                                  <img src="./../manage/<?php echo "{$common['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                  <a class="link_aa favorite" href="./?favolite_product_id=<?php echo h($common['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                </div>

                                <div class="text_part">
                                  <h2 class="product_name"><?php echo $common['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($details[0]['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $common['description'];?></h3>
                                </div>
                            </div>

                            <div class="right_side">
                            <form action="./../shopping/shopping_cart.php" method="post">
                              <input type="hidden" name="detail_count" value="1" >
                              <input type="hidden" name="count_updated_method" value="add">
                              
                                <?php if($count_colors>1):?>
                                      <h3><?php echo 'Choose color and size from below.';?></h3>

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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][0]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][1]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][2]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][3]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][4]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][5]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][6]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
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
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[$a][7]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
                                            </td>
                                            <?php else:?>
                                               <?php echo '';?>
                                            <?php endif;?>

                                        <?php endfor ;?>
                                    </tr>

                                    
                                </table>




                            <!--the case of one color------------------------------------------------>
                                <?php else:?>
                                    <h3><?php echo 'Choose size from below.';?></h3>

                                    <table border=1>
                                    　　<tr><th><?php echo $detailCSs[0][0]['color'] ;?></th></tr>
                                        <?php// if(isset($productData['product_id'])){ echo h($productData['product_id']);}?>
                                        <tr><td class="first">Size:<?php echo $detailCSs[0][0]['size'];?><br>
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[0][0]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
                                            </td>
                                    　　 </tr>

                                 <?php for($b=0;$b<8;$b++):?>
                                    <?php if(!empty($detailCSs[0][$b+1])):?>
                                        <tr><td class="second">Size:<?php echo $detailCSs[0][$b+1]['size'];?><br>
                                                <input type="checkbox" name="detail_id[]" value="<?php echo h($detailCSs[0][$b+1]['id'])?>" >
                                                <!--<input type="hidden" name="detail_count" value="1" >
                                                <input type="hidden" name="count_updated_method" value="add">-->
                                            </td>
                                    　　 </tr>
                                    <?php else:?>
                                        <?php echo '';?>
                                    <?php endif;?>
                                <?php endfor;?>

                                    </table>

                               <?php endif;?>

                                    <input class="btn bg_green" type="submit" value="Add To Cart">
                                      <!--問題点　ラジオボックスではなく、チェックボックスなのに何故か複数の商品のデータは送れない。(複数を選択は出来る。けど、データは商品一つ分だけ。)
                                    また、チェックがから出なかったらという条件をつけないと、チェックがなくてもカートのページへ行ける。それが原因でdetail_idがnullになる。-->
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
