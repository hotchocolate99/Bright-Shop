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
//--------------------------------

ini_set('display_errors', true);
//error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

//update--------------------------------------------
$errors =[];
if(isset($_POST['update'])){

        $product_id = $_POST['product_id'];

        $product_name = $_POST['product_name'];
        if(!$product_name || 20 < strlen($product_name)){
            $errors[] = 'Please type product name.';
        }

        $price = $_POST['price'];
        if(!$price){
            $errors[] = 'Please type price.';
        }

        $gender = $_POST['gender'];
        if(!$gender){
            $errors[] = 'Please choose gender.';
        }

        $category = $_POST['category'];
        if(!$category){
            $errors[] = 'Please choose category.';
        }

        $description = $_POST['description'];
        if(!$description){
            $errors[] = 'Please type product description.';
        }

        $weight = $_POST['weight'];
        if(!$weight){
            $errors[] = 'Please type product weight.';
        }


                if(!empty($_FILES['img']['name'])){
                    $file = $_FILES['img'];
                    //echo 'ファイルネーム';
                    //var_dump($file['name']);

                    //↓basename()関数で、ディレクトリトラバーサル対策。ファイルのパスを排除し、最後のファイル名の部分だけを返してくれるようにする。これでパスから情報を盗まれることはない。
                    $filename = basename($file['name']);
                    $tmp_path = $file['tmp_name'];
                    $file_err = $file['error'];
                    $filesize = $file['size'];
                    $upload_dir = 'images/';
                    $save_filename = date('YmdHis'). $filename;
                    //↑fileに日付をつけることで、同じ画像も何度でも保存出来るようになる。

                    if($filesize){
                        if($filesize > 1072000 || $file_err == 2){
                            $errors[] = 'Image file size must be less than 1MB.';
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
                            $errors[] = 'Please choose image file.';

                        }
                    }

                    //ファイルがアップロードされているかのバリデーション。　アップロード＝一時保存　is_uploaded_file($tmp_path)関数で、$tmp_pathにアップロードされているかをみる。trueならアップロード成功。
                    //次にmove_uploaded_file($tmp_path, $save_path）関数で、第一引数から第二引数に場所を移す。（一時保存場所から本当の保存先へ）
                    $msg = [];
                    if($tmp_path && $save_path && $upload_dir){
                        if(is_uploaded_file($tmp_path)){
                            if(move_uploaded_file($tmp_path, $save_path)){
                                    $msg[] = $filename .'has been saved in'.$upload_dir;
                            }else{
                                    $errors[] = 'File failed to be saved.';
                            }

                        }else{
                                $errors[] = 'File is not chosen.';
                        }
                    }
                    //var_dump($msg);
                    //var_dump($errors);


                    if(empty($errors)){

                        $update_product = updateProduct($product_id, $product_name, $category, $description, $filename, $save_path);

                    }


                }else if(empty($_FILES['img']['name']) && count($errors) == 0 ){

                     //$update_product_without_img = updateProductWithoutImg($product_id, $product_name, $category, $description, $filename, $save_path);
                     $update_product_without_img = updateProductWithoutImg($product_id, $product_name, $category, $description);

                }


       //product detail part------------------------------------------------
       $errors_register = [];
       $errors_update = [];
        for($i=0;$i<8;$i++){
            //var_dump($_POST['detail_id'][$i]);
            //var_dump($_POST['color'][$i]);
            //var_dump($_POST['size'][$i]);
            //var_dump($_POST['stock'][$i]);

                   //4つとも空だったらバリデーションは掛けない。
                   //if ($_POST['color'][$i] == '' && $_POST['size'][$i] == '' && $_POST['stock'][$i] == '') {
                    if(empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])) {
                       continue;
                    }

                //ここからバリデーション
                //detail_idがある場合

                if($_POST['detail_id'][$i]){
                    //$_POST['detail_id'][$i] == $productDetails[$i]['id']
                    $detail_id = $_POST['detail_id'][$i];

                    //4つとも空だったらバリデーションは掛けない。
                    //if($_POST['color'][$i] == '' && $_POST['size'][$i] == '' && $_POST['stock'][$i] == ''){
                        if(empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])){
                        continue;
                        }

                        if($_POST['color'][$i] == ''){
                            $errors_update[] = 'Please type color for detail'.' '.($i+1).'.';

                        }

                        if($_POST['size'][$i] == ''){
                            $errors_update[] = 'Please type size for detail'.' '.($i+1).'.';

                        }

                        if($_POST['stock'][$i] == ''){
                            $errors_update[] = 'Please type stock for detail'.' '.($i+1).'.';

                        }

                }


                //detail_idがない場合（新規登録）

                if($_POST['detail_id'][$i]==''){


                    if($_POST['color'][$i] == ''){
                        $errors_register[] = 'Please type color for detail'.' '.($i+1).'.';

                    }

                    if($_POST['size'][$i] == ''){
                        $errors_register[] = 'Please type size for detail'.' '.($i+1).'.';

                    }

                    if($_POST['stock'][$i] == ''){
                        $errors_register[] = 'Please type stock for detail'.' '.($i+1).'.';

                    }

                }

        }//for

        
        for($i=0;$i<8;$i++){

            if(empty($errors_update) && empty($errors_register)){

                //$detail_id_exist = findDetailId($_POST['detail_id'][$i]);


                if(!empty($_POST['detail_id'][$i])){
                //if($_POST['detail_id'][$i] == ''){

                    $detail_id_exist = findDetailId($_POST['detail_id'][$i]);
                        if(!empty($detail_id_exist)){

                            $detail_id = $_POST['detail_id'][$i];
                            $color = $_POST['color'][$i];
                            $size = $_POST['size'][$i];
                            $stock = $_POST['stock'][$i];

                            $updateProductDetail =  updateProductDetail($detail_id, $product_id, $price, $gender, $weight, $color, $size, $stock);
                        
                        }elseif(empty($detail_id_exist)){

                            $color = $_POST['color'][$i];
                            $size = $_POST['size'][$i];
                            $stock = $_POST['stock'][$i];
                            
                            $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);

                        }

                }elseif($_POST['detail_id'][$i] == ''){

                   //if(empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])){
                       // continue;
                       //}

                            $color = $_POST['color'][$i];
                            $size = $_POST['size'][$i];
                            $stock = $_POST['stock'][$i];

                            $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);

                }

                header("Location:./updated_product.php?product_id=".$product_id);
            }

        }
        
        
}//if(isset($_POST['update']))





//products list　ページからGETでproducts tableのidが飛んでくる。
if(!empty($_GET['product_id'])){
    $product_id = $_GET['product_id'];
}
//var_dump($product_id);
//GETの値を使って、produts table と　product_details table　のデータを取得し、フォームに表示させる。
$productDatas = getProductData($product_id);
foreach($productDatas as $productData){
    $product_name = $productData['product_name'];
    $category = $productData['category'];
    $description = $productData['description'];
    $save_path = $productData['save_path'];
}


$productDetails = getProductDetails($product_id);
foreach($productDetails as $productDetail){

    $price = $productDetail['price'];
    $gender = $productDetail['gender'];

    foreach($productDetail as $val){
        //echo $val;
        /*$detail_id = $val['id'];
        $color = $val['color'];
        $size = $val['size'];
        $stock = $val['stock'];*/
    }
}

var_dump($_POST);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Product</title>
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
                        <h1 class="form_title blue">Update Product</h1>
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

                        <form action="update_product.php" method="post" enctype="multipart/form-data"> <!--novalidate-->
                        <input type="hidden" name="product_id" value="<?php echo h($product_id) ?>">



                            <div class="form_item">
                                <label>Product Name<br>
                                　　&nbsp;&nbsp;&nbsp;&nbsp;<input class="wide" type="text" name="product_name" value="<?php if(isset($product_name)){ echo h($product_name);}?>" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Price<br>
                                　　&nbsp;¥&nbsp;<input class="wide" type="text" name="price" value="<?php if(isset($productDetails[0]['price'])){ echo h($productDetails[0]['price']);}?>" required>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Gender<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="radio" type="radio" name="gender" value="1" <?php echo $productDetails[0]['gender'] == '1' ? 'checked' : '' ?>>Boys
                                    <input class="radio" type="radio" name="gender" value="2" <?php echo $productDetails[0]['gender'] == '2' ? 'checked' : '' ?>>Girls
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Category<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="wide" name="category">
                                        <option value="Dress" <?php echo $category == 'Dress' ? 'selected' : '' ?>>Dress</option>
                                        <option value="Jaket" <?php echo $category == 'Jaket' ? 'selected' : '' ?>>Jaket</option>
                                        <option value="Pants" <?php echo $category == 'Pants' ? 'selected' : '' ?>>Pants</option>
                                        <option value="Shirt" <?php echo $category == 'Shirt' ? 'selected' : '' ?>>Shirt</option>
                                        <option value="Skirt" <?php echo $category == 'Skirt' ? 'selected' : '' ?>>Skirt</option>
                                        <option value="Shoes" <?php echo $category == 'Shoes' ? 'selected' : '' ?>>Shoes</option>
                                        <option value="Sleeper" <?php echo $category == 'Sleeper' ? 'selected' : '' ?>>Sleeper</option>
                                        <option value="Sweater" <?php echo $category == 'Sweater' ? 'selected' : '' ?>>Sweater</option>
                                        <option value="Other" <?php echo $category == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Description(in 400 words or less)<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea class="description" name="description" cols="80" rows="6"><?php if(isset($description)){echo h($description);}?></textarea>
                            　　<!--descriptionにnl2brは不要? / textarea　は１行にしておかないと、変なスペースが空いてしまう。 -->
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Weight<br>
                                　　&nbsp;&nbsp;&nbsp;&nbsp;<input class="wide" type="text" name="weight" value="<?php if(isset($productDetails[0]['weight'])){ echo h($productDetails[0]['weight']);}?>" required>&nbsp;g
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Image<br>
                                <?php if(!empty($productData['save_path'])):?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="/manage/<?php echo "{$save_path}";?>"　width="120px" height="200px" alt="product_image">

                                <?php endif ;?>
                                    <br>
                                    <br>

                                    <p>If you would like, choose new image to replace the original one with.</p>
                                    　<input name="img" type="file" accept="image/*"/><br>
                                    　<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />

                                </label>
                            </div>
                            <br>


                        <h2 class="border_top">Product details</h2>

                        <?php if(isset($errors_update)): ?>
                            <ul class="error-box">
                            <?php foreach($errors_update as $val2): ?>
                                <li><?php echo $val2; ?></li>
                            <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                        <br>

                        <?php if(isset($errors_register)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors_register as $val1): ?> 
                                <li><?php echo $val1; ?></li>
                            <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                        <br>

                    <!-- 1st  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                        <div class="form_item_box">

                                <div class="form-item">
                                    <?php if(isset($productDetails[0]['color'])):?>
                                        <strong>1.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[0]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php else:?>
                                            <strong>1.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php endif ;?>
                                </div>

                                <div class="form-item">
                                    Color
                                        <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][0])){echo h($_POST['color'][0]);}elseif(empty($_POST['color'][0]) && empty($productDetails[0]['color'])){echo '';}elseif(isset($productDetails[0]['color'])){ echo h($productDetails[0]['color']);}?>">&nbsp;
                                        <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[0]['id'])){ echo h($productDetails[0]['id']);}?>">
                                </div>

                                <div class="form_item">
                                    Size
                                        <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][0])){echo h($_POST['size'][0]);}elseif(empty($_POST['size'][0]) && empty($productDetails[0]['size'])){echo '';}elseif(isset($productDetails[0]['size'])){ echo h($productDetails[0]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                                </div>

                                <div class="form-item ">
                                    Stock
                                        <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][0])){echo h($_POST['stock'][0]);}elseif(empty($_POST['stock'][0]) && empty($productDetails[0]['stock'])){echo '';}elseif(isset($productDetails[0]['stock'])){ echo h($productDetails[0]['stock']);}else{echo '';}?>">&nbsp;
                                </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                    <!-- 2nd ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <div class="form_item_box">

                        <div class="form-item">
                            <?php if(isset($productDetails[1]['color'])):?>
                                <strong>2.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[1]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php else:?>
                                    <strong>2.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php endif ;?>
                        </div>

                        <div class="form-item">
                            Color
                                <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][1])){echo h($_POST['color'][1]);}elseif(empty($_POST['color'][1]) && empty($productDetails[1]['color'])){echo '';}elseif(isset($productDetails[1]['color'])){ echo h($productDetails[1]['color']);}else{echo '';}?>">&nbsp;
                                <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[1]['id'])){ echo h($productDetails[1]['id']);}?>">
                        </div>

                        <div class="form_item">
                            Size
                                <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][1])){echo h($_POST['size'][1]);}elseif(empty($_POST['size'][1]) && empty($productDetails[1]['size'])){echo '';}elseif(isset($productDetails[1]['size'])){ echo h($productDetails[1]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                        </div>

                        <div class="form-item ">
                            Stock
                                <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][1])){echo h($_POST['stock'][1]);}elseif(empty($_POST['stock'][1]) && empty($productDetails[1]['stock'])){echo '';}elseif(isset($productDetails[1]['stock'])){ echo h($productDetails[1]['stock']);}else{echo '';}?>">&nbsp;
                        </div>
                    </div><!--form_item_box-->
                    <br>
                    <br>

                        <!--3rd----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                        <div class="form_item_box">

                            <div class="form-item">
                                <?php if(isset($productDetails[2]['color'])):?>
                                    <strong>3.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[2]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php else:?>
                                        <strong>3.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif ;?>
                            </div>

                            <div class="form-item">
                                Color
                                    <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][2])){echo h($_POST['color'][2]);}elseif(empty($_POST['color'][2]) && empty($productDetails[2]['color'])){echo '';}elseif(isset($productDetails[2]['color'])){ echo h($productDetails[2]['color']);}else{echo '';}?>">&nbsp;
                                    <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[2]['id'])){ echo h($productDetails[2]['id']);}?>">
                            </div>

                            <div class="form_item">
                                Size
                                    <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][2])){echo h($_POST['size'][2]);}elseif(empty($_POST['size'][2]) && empty($productDetails[2]['size'])){echo '';}elseif(isset($productDetails[2]['size'])){ echo h($productDetails[2]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                            </div>

                            <div class="form-item ">
                                Stock
                                    <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][2])){echo h($_POST['stock'][2]);}elseif(empty($_POST['stock'][2]) && empty($productDetails[2]['stock'])){echo '';}elseif(isset($productDetails[2]['stock'])){ echo h($productDetails[2]['stock']);}else{echo '';}?>">&nbsp;
                            </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                        <!-- 4th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                        <div class="form_item_box">

                            <div class="form-item">
                                <?php if(isset($productDetails[3]['color'])):?>
                                    <strong>4.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[3]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php else:?>
                                        <strong>4.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif ;?>
                            </div>

                            <div class="form-item">
                                Color
                                    <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][3])){echo h($_POST['color'][3]);}elseif(empty($_POST['color'][3]) && empty($productDetails[3]['color'])){echo '';}elseif(isset($productDetails[3]['color'])){ echo h($productDetails[3]['color']);}else{echo '';}?>">&nbsp;
                                    <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[3]['id'])){ echo h($productDetails[3]['id']);}?>">
                            </div>

                            <div class="form_item">
                                Size
                                    <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][3])){echo h($_POST['size'][3]);}elseif(empty($_POST['size'][3]) && empty($productDetails[3]['size'])){echo '';}elseif(isset($productDetails[3]['size'])){ echo h($productDetails[3]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                            </div>

                            <div class="form-item ">
                                Stock
                                    <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][3])){echo h($_POST['stock'][3]);}elseif(empty($_POST['stock'][3]) && empty($productDetails[3]['stock'])){echo '';}elseif(isset($productDetails[3]['stock'])){ echo h($productDetails[3]['stock']);}else{echo '';}?>">&nbsp;
                            </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                        <!-- 5th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                        <div class="form_item_box">

                            <div class="form-item">
                                <?php if(isset($productDetails[4]['color'])):?>
                                    <strong>5.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[4]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php else:?>
                                        <strong>5.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif ;?>
                            </div>

                            <div class="form-item">
                                Color
                                    <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][4])){echo h($_POST['color'][4]);}elseif(empty($_POST['color'][4]) && empty($productDetails[4]['color'])){echo '';}elseif(isset($productDetails[4]['color'])){ echo h($productDetails[4]['color']);}else{echo '';}?>">&nbsp;
                                    <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[4]['id'])){ echo h($productDetails[4]['id']);}?>">
                            </div>

                            <div class="form_item">
                                Size
                                    <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][4])){echo h($_POST['size'][4]);}elseif(empty($_POST['size'][4]) && empty($productDetails[4]['size'])){echo '';}elseif(isset($productDetails[4]['size'])){ echo h($productDetails[4]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                            </div>

                            <div class="form-item ">
                                Stock
                                    <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][4])){echo h($_POST['stock'][4]);}elseif(empty($_POST['stock'][4]) && empty($productDetails[4]['stock'])){echo '';}elseif(isset($productDetails[4]['stock'])){ echo h($productDetails[4]['stock']);}else{echo '';}?>">&nbsp;
                            </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                        <!-- 6th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                        <div class="form_item_box">

                            <div class="form-item">
                                <?php if(isset($productDetails[5]['color'])):?>
                                    <strong>6.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[5]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php else:?>
                                        <strong>6.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif ;?>
                            </div>

                            <div class="form-item">
                                Color
                                    <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][5])){echo h($_POST['color'][5]);}elseif(empty($_POST['color'][5]) && empty($productDetails[5]['color'])){echo '';}elseif(isset($productDetails[5]['color'])){ echo h($productDetails[5]['color']);}else{echo '';}?>">&nbsp;
                                    <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[5]['id'])){ echo h($productDetails[5]['id']);}?>">
                            </div>

                            <div class="form_item">
                                Size
                                    <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][5])){echo h($_POST['size'][5]);}elseif(empty($_POST['size'][5]) && empty($productDetails[5]['size'])){echo '';}elseif(isset($productDetails[5]['size'])){ echo h($productDetails[5]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                            </div>

                            <div class="form-item ">
                                Stock
                                    <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][5])){echo h($_POST['stock'][5]);}elseif(empty($_POST['stock'][5]) && empty($productDetails[5]['stock'])){echo '';}elseif(isset($productDetails[5]['stock'])){ echo h($productDetails[5]['stock']);}else{echo '';}?>">&nbsp;
                            </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                        <!-- 7th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                        <div class="form_item_box">

                            <div class="form-item">
                                <?php if(isset($productDetails[6]['color'])):?>
                                    <strong>7.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[6]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php else:?>
                                        <strong>7.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif ;?>
                            </div>

                            <div class="form-item">
                                Color
                                    <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][6])){echo h($_POST['color'][6]);}elseif(empty($_POST['color'][6]) && empty($productDetails[6]['color'])){echo '';}elseif(isset($productDetails[6]['color'])){ echo h($productDetails[6]['color']);}else{echo '';}?>">&nbsp;
                                    <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[6]['id'])){ echo h($productDetails[6]['id']);}?>">
                            </div>

                            <div class="form_item">
                                Size
                                    <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][6])){echo h($_POST['size'][6]);}elseif(empty($_POST['size'][6]) && empty($productDetails[6]['size'])){echo '';}elseif(isset($productDetails[6]['size'])){ echo h($productDetails[6]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                            </div>

                            <div class="form-item ">
                                Stock
                                    <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][6])){echo h($_POST['stock'][6]);}elseif(empty($_POST['stock'][6]) && empty($productDetails[6]['stock'])){echo '';}elseif(isset($productDetails[6]['stock'])){ echo h($productDetails[6]['stock']);}else{echo '';}?>">&nbsp;
                            </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>
                        <!-- 8th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                        <div class="form_item_box">

                            <div class="form-item">
                                <?php if(isset($productDetails[7]['color'])):?>
                                    <strong>8.</strong>&nbsp;&nbsp;Detail ID:<?php echo $productDetails[7]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php else:?>
                                        <strong>8.</strong>&nbsp;&nbsp;<?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif ;?>
                            </div>

                            <div class="form-item">
                                Color
                                    <input calss="narrow inline" type="text" name="color[]" value="<?php if(!empty($_POST['color'][7])){echo h($_POST['color'][7]);}elseif(empty($_POST['color'][7]) && empty($productDetails[7]['color'])){echo '';}elseif(isset($productDetails[7]['color'])){ echo h($productDetails[7]['color']);}else{echo '';}?>">&nbsp;
                                    <input type="hidden" name="detail_id[]" value="<?php if(isset($productDetails[7]['id'])){ echo h($productDetails[7]['id']);}?>">
                            </div>

                            <div class="form_item">
                                Size
                                    <input calss="narrow inline" type="text" name="size[]" value="<?php if(!empty($_POST['size'][7])){echo h($_POST['size'][0]);}elseif(empty($_POST['size'][7]) && empty($productDetails[7]['size'])){echo '';}elseif(isset($productDetails[7]['size'])){ echo h($productDetails[7]['size']);}else{echo '';}?>">cm&nbsp;&nbsp;
                            </div>

                            <div class="form-item ">
                                Stock
                                    <input calss="narrow inline" type="text" name="stock[]" value="<?php if(!empty($_POST['stock'][7])){echo h($_POST['stock'][7]);}elseif(empty($_POST['stock'][7]) && empty($productDetails[7]['stock'])){echo '';}elseif(isset($productDetails[7]['stock'])){ echo h($productDetails[7]['stock']);}else{echo '';}?>">&nbsp;
                            </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                    <!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    

                        <input class="btn bg_blue" type="submit"  name="update" value="update">
                    </form>

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>

   　</body>
</html>

