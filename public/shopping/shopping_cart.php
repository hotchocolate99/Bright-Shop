<?php
//----ログイン状態-----------------
session_start();

/*if (!$_SESSION['login']) {
    header('Location: /public/account/login.php');
    exit();
  }*/

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  $users_id = $user[0]['usr_id'];
//--------------------------------

//against click junction ボタンがある時はこれいる？？いらない？？
header('X-FRAME-OPTION:DENY');

ini_set('display_errors',true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

echo 'ポスト';
print_r($_POST);

//↓は、index.phpから渡ってくるpostと、このページから渡ってくるpostのどちらにも対応しているの？
if(!empty($_POST) && $_POST['detail_id'] !== null){
    $posted_detail = [
        'detail_id' => $_POST['detail_id'],
        'detail_count' => $_POST['detail_count'],
    ];
//var_dump($posted_detail);
//var_dump($_SESSION['shopping_cart']);

//カートが空の時、postで送られて来たデータを代入する。
    if(empty($_SESSION['shopping_cart'])){
       $_SESSION['shopping_cart'] = [];
       //↑初期化
       //↓sessionにpostの内容を入れる。　　　　　
       $_SESSION['shopping_cart'][]= $posted_detail;

       //echo 'セッション';
       //var_dump($_SESSION['shopping_cart']);
       //session_destroy();

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
                    $posted_detail['detail_count'] = $detail['detail_count'];
                    //postのカウント数？それともindex.php　　+　　　　　　　セッションのカウント数?それともこのページのセレクトボックスのカウント数
                    //index.phpの「カートに入れる」で増えた数に足すのは、　cart.phpのセレクトボックスで選ばれた数（cart.phpのページで操作するなら、index.phpの「カートに入れる」を押すことではもう増えない。変わらない数に足す）
                    //ここで＋を付けないで＝のみだと、index.phpの「カートに入れる」で追加した分＝（必ず一つずつ）が、cart.phpで表示される個数(select box)に反映されない。なので、＋が必要。
                    }

 
                $_SESSION['shopping_cart'][$index] = $posted_detail;
                //var_dump($_SESSION['shopping_cart'][0]);
                //(商品Aはindexが０、商品Bはindexが１)。
                 //商品Aが２つの時　⇨　array(3) { ["item_id"]=> string(1) "1" ["item_name"]=> string(7) "商品A" ["item_count"]=> int(2) } 
                 //$postedItemの中身はitem_id,　item_name,　item_count。

                //上書き作業が終わったら、追加はもうしないので。
                $register = false;
            }
       }

        if($register){
            $_SESSION['shopping_cart'][] = $posted_detail;
        }
    }
}
echo 'カート';
var_dump($_SESSION['shopping_cart']);
$details = $_SESSION['shopping_cart'];

//var_dump($details[0]['detail_count']);//1  $detail['detail_count']と同じ
foreach($details as $detail){
    //echo '＄detailsの数';
    //var_dump(count($details));
    //var_dump($detail['detail_count']);//1  $details[0]['detail_count']と同じ
}


$total_in_cart = 0;
foreach($details as $detail){
  if(!empty($details)){
    $total_in_cart += $detail['detail_count'];
    echo 'カートの商品数';
    var_dump($total_in_cart);
   }
}


//$_SESSION['shopping_cart'] = [];
//session_destroy();

//商品のproduct_nameと画像を取得
$productDatas = getProductDataByDetail($detail['detail_id']);

//var_dump($productDatas);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shpping cart</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../../public/css/cart.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
</head>
<body>
    
　　　　<?php include './../header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                　<div class="typein">
                　　　<h1 class="form_title orange">Your Shopping Cart</h1>

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
                                    <?php// var_dump($product_datas);?>
                                    <?php $product_id = $product_datas[0]['product_id'];?>
                                    <?php $product_name_file= getProductNameFile($product_id);?>
                                    <?php// var_dump($product_name_file);?>


            <div class="pickup_box">
                <p><?php echo $i+1;?>.&nbsp;<strong><?php echo "{$product_name_file[0]['product_name']}"?><?php echo "{$detail['detail_id']}";?></strong></p>
                
                <div class="row_box">
                        <div class="img_box item">
                            <img src="/manage/<?php echo "{$product_name_file[0]['save_path']}";?>" alt="product_image" >
                        </div>

                
                        <p class="item">Price:&nbsp;￥<?php echo n("{$product_datas[0]['price']}");?></p>
                        <p class="item">Color:&nbsp;<?php echo "{$product_datas[0]['color']}";?></p>
                        <p class="item">Size:&nbsp;<?php echo "{$product_datas[0]['size']}";?></p>
                        

                        <form class="item" action="./shopping_cart.php" method="post">
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

　　　　　　　　　　　　　　
                        <?php $s_total = $product_datas[0]['price'] * $detail['detail_count']?>
                        <p class="item">Total:&nbsp;¥<?php echo n("{$s_total}");?></p>

                       <div class=item>
                           <a class="btn bg_gray" href="./shopping_delete.php?detail_id=<?php echo $detail['detail_id'] ?>">Delete</a>
                        </div>
                    </div><!--row_box-->

                </div><!--pickup_box-->
             <?php endfor ;?>
          <?php endif ;?>

          <?php $total_s_total=0;
foreach($details as $detail){
    if(!empty($details)){
        $s_total = $product_datas[0]['price'] * $detail['detail_count'];
        $total_s_total += $s_total;
      echo '小計の合計';
      var_dump($total_s_total);
     }
  }?>

                <div class="row_box">
                    <p class="item">Total Qty:&nbsp;<?php echo "{$total_in_cart}";?></p>
                    <!--<p class="item">Sub Total:¥&nbsp;<?php echo n("{}");?></p>-->
                    <!--<p class="item">Sipping Fee:¥&nbsp;<?php echo n("{}");?></p>-->
                    <!--<p class="item">Total Charge:¥&nbsp;<?php echo n( "{}");?></p>-->
               </div>
          <div class="link_box">
              <a class="link_a line_color_green" href="./../../index.php">Continue shopping</a>
              <a class="link_a line_color_orange" href="./purchase.php">Proceed with purchase</a>
          </div>
     

<!--↓　理解してないけど、とりあえず数量変更の更新ということだけ覚えておく
上のselectタグのclass名.change_item_countの要素を全て取得？それをエレメントとして、関数を実行。引数はelem。どんな関数か⇨changeというイベントで関数を実行。
要素.addEventListener(イベント, 関数, オプション);-->
    <script>
        // 数量が変更されたら更新を行う

       // 上のselectタグのclass名.change_item_countの要素を全て取得して、定数itemCountsに代入？
        const detailCounts = document.querySelectorAll('.change_detail_count');

        //itemCountsは配列？一つ以上の要素が入っている。なのでforeachでまわして要素を取得しているということだと思う。
        detailCounts.forEach(function(elem) {

         //引数のelemのchangeというイベントで関数を実行。
        elem.addEventListener('change', function(elem) {

            //elemのターゲット属性のparentNode??? をformという定数に代入。
            const form = elem.target.parentNode;

            //formの中から input[name="item_count"]の値（value）を取得？？　エレメントのtarget属性の属性値と同じ。
            form.querySelector('input[name="detail_count"]').value = elem.target.value

            //最後にformのサブミット関数？？
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