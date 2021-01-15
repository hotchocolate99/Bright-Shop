<?php
session_start();
ini_set('display_errors', true);

require_once './../private/database.php';
require_once './../private/functions.php';

$errors =[];

ini_set('display_errors', true);

var_dump($_POST);

//var_dump($_FILES);
//var_dump($_GET);
$_SESSION['form_count'] = [];
$_SESSION['form_count'][] = $_POST;
$form_count = $_SESSION['form_count'];
var_dump($form_count);

//product detail form(color, size, stock)の数
//if($_POST['form_count']){
 //  $_SESSION['form_count'] = $_POST['form_count'];
 //  $form_count = $_SESSION['form_count'];
//}else{$form_count = $_SESSION['form_count'];}
//var_dump($form_count);

//$form_count = preg_replace('/[^0-9]/', '', $form_count);
//echo $form_count."\n"; // 4200

if(!$_POST['form_count']){
    $_SESSION['form_count']= 1;
    $form_count = $_SESSION['form_count'];
}else{
    $_SESSION['form_count'] = $_POST['form_count'];
    $form_count = $_SESSION['form_count'];
}
var_dump($_SESSION['form_count']);

if(!empty($_POST['product_name'])){

    $product_name = $_POST['product_name'];
    if(!$product_name || 20 < strlen($product_name)){
        $errors[] = '商品名を入力して下さい。';
    }

    $price = $_POST['price'];
    if(!$price){
        $errors[] = '単価を入力して下さい。';
    }

    $gender = $_POST['gender'];
    if(!$gender){
        $errors[] = '性別を選択して下さい。';
    }

    $category = $_POST['category'];
    if(!$category){
        $errors[] = 'カテゴリーを選択して下さい。';
    }

    $description = $_POST['description'];
    if(!$description){
        $errors[] = '商品説明を入力して下さい。';
    }

    $weight = $_POST['weight'];
    if(!$weight){
        $errors[] = '重量を入力して下さい。';
    }

//画像どうやってバリデーションかける？？
$file = $_FILES['img'];
//var_dump($file);

//↓basename()関数で、ディレクトリトラバーサル対策。ファイルのパスを排除し、最後のファイル名の部分だけを返してくれるようにする。これでパスから情報を盗まれることはない。
$filename = basename($file['name']);
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
$upload_dir = 'images/';
$save_filename = date('YmdHis'). $filename;
//↑fileに日付をつけることで、同じ画像も何度でも保存出来るようになる。

if($filesize){
    if($filesize > 1048576 || $file_err == 2){
        $errors[] = 'ファイルサイズは１MB未満にして下さい。';
    }
  }

  //ファイルの拡張子のバリデーション
  //許容するファイルの拡張子↓
  $allow_ext = array('jpg','jpeg','png');
  //実際のファイルの拡張子を確認 ↓　pathinfo関数で。＄file_extには実際のファイルの拡張子が入る。
  $file_ext = pathinfo($filename,PATHINFO_EXTENSION);
  $save_path = $upload_dir.$save_filename;

//in_array関数で＄file_ext が $allow_ext　のどれかに当てはまるかのチェック。strtolowerは実際のファイルの拡張子が大文字だったら小文字に直してくれる。
  if($file_ext && $allow_ext){
      if(!in_array(strtolower($file_ext),$allow_ext)){
        $errors[] = '画像を選択して下さい。';
        
      }
  }
//ファイルがアップロードされているかのバリデーション。　アップロード＝一時保存　is_uploaded_file($tmp_path)関数で、$tmp_pathにアップロードされているかをみる。trueならアップロード成功。
//次にmove_uploaded_file($tmp_path, $save_path）関数で、第一引数から第二引数に場所を移す。（一時保存場所から本当の保存先へ）
$msg = [];
if($tmp_path && $save_path && $upload_dir){
 if(is_uploaded_file($tmp_path)){
      if(move_uploaded_file($tmp_path, $save_path)){
          $msg[] = $filename .'を'.$upload_dir .'に保存しました。';
      }else{
          $errors[] = 'ファイルが保存できませんでした。';
      }

  }else{
      $errors[] = 'ファイルが選択されていません。';
  }
}
//var_dump($product_name);


   if(count($errors) === 0 && $msg){

      $product_id = registerProduct($product_name, $category, $description, $filename, $save_path);
      //header('Location: ./products_list.php');

      if(!$product_id){
         $errors[] = '登録に失敗しました';
      }
   }
   


//product detail part------------------------------------------------

//product detail form(color, size, stock)の数
//if(!$_GET['form_count']){
   // $form_count = 1;
//}
 //if($_POST['color-0'] || $_POST['size-0'] || $_POST['stok-0']){
    
 //}
   
    //$form_count = 0;
   if(!$_POST['form_count']){

    $form_count = preg_replace('/[^0-9]/', '', $_POST['form_count']);
    echo $form_count;

       //$form_count = 1;

   }else if($_POST['form_count']){

      $form_count = $_POST['form_count'];

      for($i=0; $i<$form_count; $i++){

       $color = explode('-',$_POST['color-'.$i]);
       $size = explode('-',$_POST['size-'.$i]);
       $stock = explode('-',$_POST['stok-'.$i]);

$errorsD = [];
if(!empty($color || $size || $stock)){

    if(!$color){
        $errorsD[] = '色を入力して下さい。';
    }

    if(!$size){
        $errorsD[] = 'サイズをして下さい。';
    }

    if(!$stock){
        $errorsD[] = '在庫数を入力して下さい。';
    }


    if(count($errorsD) === 0){

        $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);
        //header('Location: ./products_list.php');

        if(!$completedRegisteringProduct){
            $errorsD[] = '登録に失敗しました';
        }
    }
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
        <title>Add New Product</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../css/header.css">
    
    </head>

    <body>

     　<?php include './mng_header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title blue">Add New Product</h1>
                        <br>
                        

                        <h2>Product Common Part</h2>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        
                        
                            <div class="form_item">
                                <label>Product Name<br>
                                　　<input class="wide" type="text" name="product_name" id="child_common" form="parent_common" value="<?php if(isset($_POST['product_name'])){ echo h($_POST['product_name']);}?>" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Price<br>
                                　　¥&nbsp;<input class="wide" type="text" name="price" id="child_common" form="parent_common" value="<?php if(isset($_POST['price'])){ echo h($_POST['price']);}?>" required>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Gender<br>
                                    <input class="radio" type="radio" name="gender" id="child_common" form="parent_common" value="1" checked>Boys
                                    <input class="radio" type="radio" name="gender" id="child_common" form="parent_common" value="2">Girls
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Category<br>
                                    <select class="wide" name="category" id="child_common" form="parent_common">
                                        <option value=Dress>Dress</option>
                                        <option value=Jaket>Jaket</option>
                                        <option value=Pants>Pants</option>
                                        <option value=Shirt>Shirt</option>
                                        <option value=Skirt>Skirt</option>
                                        <option value=Shoes>Shoes</option>
                                        <option value=Sleeper>Sleeper</option>
                                        <option value=Sweater>Sweater</option>
                                    </select>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Description(in 400 words or less)<br>
                            　　　　　<textarea class="description" name="description" id="child_common" form="parent_common" cols="80" rows="6"></textarea>
                            　　
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Weight<br>
                                　　<input class="wide" type="text" name="weight" id="child_common" form="parent_common" value="<?php if(isset($_POST['weight'])){ echo h($_POST['weight']);}?>" required>&nbsp;g
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Photo<br>
                                    　<input name="img" form="parent_common" type="file" accept="image/*"/><br>
                                    　<input type="hidden" name="MAX_FILE_SIZE" id="child_common" form="parent_common" value="1048576" />
                                </label>
                            </div>
                            <br>

                            

                        <h2 class="border_top">Product detail Part</h2>
                        <br>
                        <form action="add_product.php" method="post">
                            <div class="form_count">
                                <label class="child">How many forms would you like?
                                <input type="hidden" name="form_count" value="<?php echo $form_count?>">
                                    <select class="change_form_count" name="form_count">

                    <!--三項演算子　(条件式) ? (真式) : (偽式);-->
                    <?php// echo $item['item_name'].$item['item_count']; ?>
                        <option value="1" <?php echo $form_count == '1' ? 'selected' : '' ?>>1</option>
                        <option value="2" <?php echo $form_count == '2' ? 'selected' : '' ?>>2</option>
                        <option value="3" <?php echo $form_count == '3' ? 'selected' : '' ?>>3</option>
                        <option value="4" <?php echo $form_count == '4' ? 'selected' : '' ?>>4</option>
                        <option value="5" <?php echo $form_count == '5' ? 'selected' : '' ?>>5</option>
                    </select>



                                        <!--<option value=1>1</option>
                                        <option value=2>2</option>
                                        <option value=3>3</option>
                                        <option value=4>4</option>
                                        <option value=5>5</option>
                                        <option value=6>6</option>
                                        <option value=7>7</option>
                                        <option value=8>8</option>
                                    </select>-->
                                </label>
                             
                                <!--<input class="btn_b bg_orange child" type="submit" value="confirm">-->
                            </div>
                        </form>
                        <?php if(isset($errorsD)): ?> 
                            <ul class="error-box">
                            <?php foreach($errorsD as $errorD): ?> 
                                <li><?php echo $errorD; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                    <!-- 1st  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    
                        <div class="form_item_box">
                                <div class="form-item">
                                    <label>Color<br>
                                    　　<input calss="narrow inline" type="text" name="color" id="child_common" form="parent_common" value="<?php if(isset($_POST['color'])){ echo h($_POST['color']);}?>" required>
                                    </label>
                                </div>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <div class="form_item">
                                    <label>Size<br>
                                    　　<input calss="narrow inline" type="text" name="size" id="child_common" form="parent_common" value="<?php if(isset($_POST['size'])){ echo h($_POST['size']);}?>" required>&nbsp;cm
                                    </label>
                            　　</div>

                                <div class="form-item ">
                                    <label>Stock<br>
                                    　　<input calss="narrow inline" type="text" name="stock" id="child_common" form="parent_common" value="<?php if(isset($_POST['stock'])){ echo h($_POST['stock']);}?>" required>
                                    </label>
                                </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                    <!-- after the 2nd ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    
                    <?php if(isset($form_count)):?>
                    <?php for($i=1; $i<=$form_count-1;$i++):?>
                        <?php $color = 'color';
                              $size = 'size';
                              $stock = 'stock';?>

                          <?php// $color = '"color-".$i+1';
                              //$size = '"size-".$i+1';
                              //$stock = '"stock-".$i+1';?>


                        <div class="form_item_box">
                                <div class="form-item">
                                    <label>Color<br>
                                    　　<input calss="narrow inline" type="text" name="<?php echo $color;?>" id="child_common" form="parent_common" value="<?php if(isset($_POST['$color'])){ echo h($_POST['$color']);}?>" required>
                                    </label>
                                </div>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <div class="form_item">
                                    <label>Size<br>
                                    　　<input calss="narrow inline" type="text" name="$size" id="child_common" form="parent_common" value="<?php if(isset($_POST['$size'])){ echo h($_POST['$size']);}?>" required>&nbsp;cm
                                    </label>
                            　　</div>

                                <div class="form-item ">
                                    <label>Stock<br>
                                    　　<input calss="narrow inline" type="text" name="$stock" id="child_common" form="parent_common" value="<?php if(isset($_POST['$stock'])){ echo h($_POST['$stock']);}?>" required>
                                    </label>
                                </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>
                        <?php endfor ;?>
                        <?php endif;?>

                    <!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <form id="parent_common" action="add_product.php" method="post" enctype="multipart/form-data">
                        <input class="btn bg_blue" type="submit"  value="register">
                    </form>

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   <script>
        // 数量が変更されたら更新を行う

       // 上のselectタグのclass名.change_item_countの要素を全て取得して、定数itemCountsに代入？
        const formCounts = document.querySelectorAll('.change_form_count');

        //itemCountsは配列？一つ以上の要素が入っている。なのでforeachでまわして要素を取得しているということだと思う。
        formCounts.forEach(function(elem) {

         //引数のelemのchangeというイベントで関数を実行。
        elem.addEventListener('change', function(elem) {

            //elemのターゲット属性のparentNode??? をformという定数に代入。
            const form = elem.target.parentNode;

            // input[name="item_count"]の値（value）を取得？？　エレメントのtarget属性の属性値と同じ。
            form.querySelector('input[name="form_count"]').value = elem.target.value

            //最後にformのサブミット関数？？
            form.submit();
        });
        });

    </script>
   　</body>
</html>