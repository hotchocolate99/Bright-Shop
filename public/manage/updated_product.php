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

//ini_set('display_errors', true);
ini_set('display_errors', 0);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

//update and Add new product details------------------------------------


//var_dump($_POST);

var_dump($_FILES);
//var_dump($_GET);

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

        

                if($_FILES['img']['name'] !==''){
                    $file = $_FILES['img'];
                    var_dump($file['name']);

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
                                    $msg[] = $filename .'has been saved in'.$upload_dir.;
                            }else{
                                    $errors[] = 'File failed to be saved.';
                            }

                        }else{
                                $errors[] = 'File is not chosen.';
                        }
                    }
                    var_dump($msg);
                    var_dump($errors);


                    if(count($errors) == 0 ){

                        $update_product = updateProduct($product_id, $product_name, $category, $description, $filename, $save_path);
                
                    }
                   

                }else if($_FILES['img']['name'] == '' && count($errors) == 0 ){

                     $update_product_without_img = updateProductWithoutImg($product_id, $product_name, $category, $description, $filename, $save_path);
                
                }


       //product detail part------------------------------------------------

        $errorsD = [];
        for($i=0;$i<8;$i++){

                //4つとも空だったらバリデーションは掛けない。
                if (empty($_POST['detail_id'][$i]) && empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])) {
                    continue;
                }

                //ここからバリデーション 
                //detail_idがない場合（新規登録）
                if(empty($_POST['detail_id'][$i]) ){

                    
                    if(empty($_POST['color'][$i])){
                        $errorsD[] = 'Please type color.';
                    }

                    if(empty($_POST['size'][$i])){
                    $errorsD[] = 'Please type size.';
                    }

                    if(empty($_POST['stock'][$i])){
                        $errorsD[] = 'Please type stock.';
                    }
            
                    if(count($errorsD) == 0){

                        $color = $_POST['color'][$i];
                        $size = $_POST['size'][$i];
                        $stock = $_POST['stock'][$i];

                        
                        $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);
                        
                    }
                            
                }



                //detail_idがある場合
                if(!empty($_POST['detail_id'][$i])){

                    

                    if(empty($_POST['color'][$i])){
                        $errorsD[] = 'Please type color.';
                    }

                    if(empty($_POST['size'][$i])){
                    $errorsD[] = 'Please type size.';
                    }

                    if( empty($_POST['stock'][$i])){
                        $errorsD[] = 'Please type stock.';
                    }
                
                    if(count($errorsD) == 0){

                        //detail_idがある場合は更新
                        $detail_id = $_POST['detail_id'][$i];
                        $color = $_POST['color'][$i];
                        $size = $_POST['size'][$i];
                        $stock = $_POST['stock'][$i];
        
                        $updateProductDetail =  updateProductDetail($detail_id, $product_id, $price, $gender, $weight, $color, $size, $stock);
        
                    }

                }
            
                

           
        
        }//for

}//if(isset($_POST['update']))

header('Location: ./products_list.php');
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

                    <h2 class="form_title">Updated successfuly.</h2>

                    </div>
                </div>
            </div>
        </label>
    </body>
</html>