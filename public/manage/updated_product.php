<?php
session_start();

if ($_SESSION['login']= true) {
    $mgr = $_SESSION['mgr'];
  }
  $managers_id = $manager[0]['mgr_id'];
//------------------------------------------------

ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

//update and Add new product details------------------------------------
$errors =[];

ini_set('display_errors', true);

var_dump($_POST);

//var_dump($_FILES);
//var_dump($_GET);

if(!empty($_POST['product_id'])){

    $product_id = $_POST['product_id'];

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

           $update_product = $updateProduct($product_id, $product_name, $category, $description, $filename, $save_path);
}
      //$product_id = registerProduct($product_name, $category, $description, $filename, $save_path);
      //header('Location: ./products_list.php');

      if(!$product_id){
         $errors[] = '登録に失敗しました';
      }
   }
   


//product detail part------------------------------------------------

//---------------------------------------------
/*$array = array('apple', 'lemon', 'banana');
 
// 任意の文字列が配列に含まれるか検索
$index = array_search('lemon', $array);
print_r($index);

// インデックスを指定して要素を削除
array_splice($array, $index);
print_r($array);*/
//-----------------------------------------------
$errorsD = [];
for($i=0;$i<8;$i++){

    /*if($_POST['color'][$i] !=='opt' && $_POST['size'][$i] =='opt' || $_POST['stock'][$i] =='opt'){

        $errorsD[] = 'サイズ、在庫数のいづれかが未入力です。全て入力してください。';

    }else if($_POST['size'][$i] !=='opt' && $_POST['color'][$i] =='opt' || $_POST['stock'][$i] =='opt'){

            $errorsD[] = '色、在庫数のいづれかが未入力です。全て入力してください。';

    }else if($_POST['stock'][$i] !=='opt' && $_POST['color'][$i] =='opt' || $_POST['size'][$i] =='opt'){
    
            $errorsD[] = '色、サイズのいづれかが未入力です。全て入力してください。';

    }*/

     if($_POST['detail_id']){
            $color = $_POST['color'][$i];
            $size = $_POST['size'][$i];
            $stock = $_POST['stock'][$i];

            $updateProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);
     }
}

    /*var_dump($product_id);
    var_dump($price);
    var_dump($gender);
    var_dump($weight);*/


        /*if($_POST['color'][$i] !=='opt' && $_POST['size'][$i] !=='opt' && $_POST['stock'][$i] !=='opt'){
            //$_POST['color'][0] && $_POST['size'][0] && $_POST['stock'][0]){
            $color = $_POST['color'][$i];
            $size = $_POST['size'][$i];
            $stock = $_POST['stock'][$i];

            $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);
            //header('Location: ./products_list.php');

            if(!$completedRegisteringProduct){
                $errorsD[] = '登録に失敗しました';
            }*/
            /*else if($_POST['color'][$i] =='option' && $_POST['size'][$i] =='option' && $_POST['stock'][$i] =='option'){

            $color = $_POST['color'][$i];
            $color = null;

            $size = $_POST['size'][$i];
            $size = null;

            $stock = $_POST['stock'][$i];
            $stock = null;*/

        //}
    //}

//updateボタンが押されたら、POSTでproduct_idとdetail_idが送られるので、それを使って、idが存在するならupdateされた商品上表をtableに入れる。idが送られてこない。。。。
if($_POST['product_id']){
    $updateProduct($product_name, $category, $description, $filename, $save_path);
}

if($_POST['product_id'] && $_POST['detail_id']){
    $updateProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);
}





//updateの関数れい
    blogUpdate($blogs, $posts_id);
if(!empty($_FILES['img']['name'])){
   fileUpdate($blogs, $file, $save_path, $posts_id);
}else if(isset($file_path_to_delete)){
    deleteFile($posts_id, $file_path_to_delete);
}

if(!empty($_FILES['pic'])){
    addNewFile($blogs, $file, $save_path, $posts_id);
}

if(!empty($caption)){
    addCaption($caption, $posts_id);
}



?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Product</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../../../public/css/header.css">
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