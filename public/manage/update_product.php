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

if($_GET['product_id']){
    $product_id = $_GET['product_id'];
}

$productDatas = getProductData($product_id);
$productDetails = getProductDetails($product_id);

var_dump($productDatas[0]['product_name']);
foreach($productDatas as $productData){
    echo $productData;
}


echo' ディテール';
var_dump($productDtails);

$errors =[];

ini_set('display_errors', true);

var_dump($_POST);

//var_dump($_FILES);
//var_dump($_GET);

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

//---------------------------------------------
/*$array = array('apple', 'lemon', 'banana');
 
// 任意の文字列が配列に含まれるか検索
$index = array_search('lemon', $array);
print_r($index);

// インデックスを指定して要素を削除
array_splice($array, $index);
print_r($array);*/
//-----------------------------------------------

    $product_id = $product_id['product_id'];
    var_dump($product_id);
    var_dump($price);
    var_dump($gender);
    var_dump($weight);

    for($i=0;$i<8;$i++){

        $errorsD = [];

      /*if($_POST['color'][$i] !=='opt' && $_POST['size'][$i] =='opt' || $_POST['stock'][$i] =='opt'){

                $errorsD[] = 'サイズ、在庫数のいづれかが未入力です。全て入力してください。';

        }else if($_POST['size'][$i] !=='opt' && $_POST['color'][$i] =='opt' || $_POST['stock'][$i] =='opt'){

                    $errorsD[] = '色、在庫数のいづれかが未入力です。全て入力してください。';

        }else if($_POST['stock'][$i] !=='opt' && $_POST['color'][$i] =='opt' || $_POST['size'][$i] =='opt){
            
                    $errorsD[] = '色、サイズのいづれかが未入力です。全て入力してください。';

        }*/


        if($_POST['color'][$i] !=='opt' && $_POST['size'][$i] !=='opt' && $_POST['stock'][$i] !=='opt'){
            //$_POST['color'][0] && $_POST['size'][0] && $_POST['stock'][0]){
            $color = $_POST['color'][$i];
            $size = $_POST['size'][$i];
            $stock = $_POST['stock'][$i];

            $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);
            //header('Location: ./products_list.php');

            if(!$completedRegisteringProduct){
                $errorsD[] = '登録に失敗しました';
            }
            /*else if($_POST['color'][$i] =='option' && $_POST['size'][$i] =='option' && $_POST['stock'][$i] =='option'){

            $color = $_POST['color'][$i];
            $color = null;

            $size = $_POST['size'][$i];
            $size = null;

            $stock = $_POST['stock'][$i];
            $stock = null;*/

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
        <link rel="stylesheet" href="./../../public/css/header.css">
    
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

                        <form action="add_product.php" method="post" enctype="multipart/form-data"> //novalidate
                            
                            <div class="form_item">
                                <label>Product Name<br>
                                　　<input class="wide" type="text" name="product_name" value="<?php if(isset($productData['product_name'])){ echo h($productData['product_name']);}?>" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Price<br>
                                　　¥&nbsp;<input class="wide" type="text" name="price" value="<?php if(isset($productDetails[0]['price'])){ echo h($productDetails[0]['price']);}?>" required>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Gender<br>
                                    <input class="radio" type="radio" name="gender" value="1" <?php echo $productDetails[0]['gender'] == '1' ? 'checked' : '' ?>>Boys
                                    <input class="radio" type="radio" name="gender" value="2" <?php echo $productDetails[0]['gender'] == '2' ? 'checked' : '' ?>>Girls
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Category<br>
                                    <select class="wide" name="category">
                                        <option value="Dress" <?php echo $productData['category'] == 'Dress' ? 'selected' : '' ?>>Dress</option>
                                        <option value="Jaket" <?php echo $productData['category'] == 'Jaket' ? 'selected' : '' ?>>Jaket</option>
                                        <option value="Pants" <?php echo $productData['category'] == 'Pants' ? 'selected' : '' ?>>Pants</option>
                                        <option value="Shirt" <?php echo $productData['category'] == 'Shirt' ? 'selected' : '' ?>>Shirt</option>
                                        <option value="Skirt" <?php echo $productData['category'] == 'Skirt' ? 'selected' : '' ?>>Skirt</option>
                                        <option value="Shoes" <?php echo $productData['category'] == 'Shoes' ? 'selected' : '' ?>>Shoes</option>
                                        <option value="Sleeper" <?php echo $productData['category'] == 'Sleeper' ? 'selected' : '' ?>>Sleeper</option>
                                        <option value="Sweater" <?php echo $productData['category'] == 'Sweater' ? 'selected' : '' ?>>Sweater</option>
                                        <option value="Other" <?php echo $productData['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Description(in 400 words or less)<br>
                            　　　　　<textarea class="description" name="description" cols="80" rows="6">
                                       <?php if(isset($productData['description'])){echo h($productData['description']);}?>
                                    </textarea>
                            　　<!--descriptionにnl2brは不要。-->
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Weight<br>
                                　　<input class="wide" type="text" name="weight" value="<?php if(isset($productDetails[0]['weight'])){ echo h($productDetails[0]['weight']);}?>" required>&nbsp;g
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Image<br>
                                <?php if(!empty($productData['save_path'])):?>
                                    <img src="/public/manage/<?php echo "{$productData['save_path']}";?>"　width="120px" height="200px" alt="product_image" >
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
                                    <?php if(isset($productDetails[0]['color'])):?>
                                        <p>Detail ID:<?php echo $productDetails[0]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <?php endif;?>
                                </div>


                                <div class="form-item">
                                    <label>Color<br>
                                    　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[0]['color'])){ echo h($productDetails[0]['color']);}else{echo 'opt';}?>">
                                    </label>
                                </div>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <div class="form_item">
                                    <label>Size<br>
                                    　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[0]['size'])){ echo h($productDetails[0]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                    </label>
                            　　</div>

                                <div class="form-item ">
                                    <label>Stock<br>
                                    　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[0]['stock'])){ echo h($productDetails[0]['stock']);}else{echo 'opt';}?>">
                                    </label>
                                </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                    <!-- 2nd ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                       <div class="form_item_box">

                                    <div class="form-item">
                                        <?php if(isset($productDetails[1]['color'])):?>
                                            <p>Detail ID:<?php echo $productDetails[1]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif;?>
                                    </div>

                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[1]['color'])){ echo h($productDetails[1]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[1]['size'])){ echo h($productDetails[1]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[1]['stock'])){ echo h($productDetails[1]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!--3rd----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    
                           <div class="form_item_box">

                                    <div class="form-item">
                                        <?php if(isset($productDetails[2]['color'])):?>
                                             <p>Detail ID:<?php echo $productDetails[2]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                         <?php endif;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[2]['color'])){ echo h($productDetails[2]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[2]['size'])){ echo h($productDetails[2]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[2]['stock'])){ echo h($productDetails[2]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 4th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                             <div class="form_item_box">


                                    <div class="form-item">
                                        <?php if(isset($productDetails[3]['color'])):?>
                                             <p>Detail ID:<?php echo $productDetails[3]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                         <?php endif;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[3]['color'])){ echo h($productDetails[3]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[3]['size'])){ echo h($productDetails[3]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[3]['stock'])){ echo h($productDetails[3]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 5th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                            <div class="form_item_box">

                                    <div class="form-item">
                                        <?php if(isset($productDetails[4]['color'])):?>
                                             <p>Detail ID:<?php echo $productDetails[4]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                         <?php endif;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[4]['color'])){ echo h($productDetails[4]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[4]['size'])){ echo h($productDetails[4]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[4]['stock'])){ echo h($productDetails[4]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 6th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                            <div class="form_item_box">

                                    <div class="form-item">
                                        <?php if(isset($productDetails[5]['color'])):?>
                                             <p>Detail ID:<?php echo $productDetails[5]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                         <?php endif;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[5]['color'])){ echo h($productDetails[5]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[5]['size'])){ echo h($productDetails[5]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[5]['stock'])){ echo h($productDetails[5]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 7th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <div class="form_item_box">

                                    <div class="form-item">
                                        <?php if(isset($productDetails[6]['color'])):?>
                                             <p>Detail ID:<?php echo $productDetails[6]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                         <?php endif;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[6]['color'])){ echo h($productDetails[6]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[6]['size'])){ echo h($productDetails[6]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[6]['stock'])){ echo h($productDetails[6]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 8th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                             <div class="form_item_box">

                                    <div class="form-item">
                                        <?php if(isset($productDetails[7]['color'])):?>
                                             <p>Detail ID:<?php echo $productDetails[7]['id'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                         <?php endif;?>
                                    </div>

                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[7]['color'])){ echo h($productDetails[7]['color']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($productDetails[7]['size'])){ echo h($productDetails[7]['size']);}else{echo 'opt';}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($productDetails[7]['stock'])){ echo h($productDetails[7]['stock']);}else{echo 'opt';}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                    <!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->


                        <input class="btn bg_blue" type="submit"  value="register">
                    </form>

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>

   　</body>
</html>

