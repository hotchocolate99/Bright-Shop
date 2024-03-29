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
  
//ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';


//var_dump($_POST);
//var_dump($_POST['color'][0]);

var_dump($_FILES);
//var_dump($_GET);

$errors = [];
if(!empty($_POST['product_name'])){


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
                        $msg[] = $filename .'has been saved in '.$upload_dir;
                    }else{

                    $errors[] = 'File failed to be saved.';
                    }

                }else{
                    $errors[] = 'File is not chosen.';
                }
            }

    
    }elseif(empty($_FILES['img']['name'])){
         $errors[] = 'Please choose image file.';
    }

    //if(count($errors) == 0 && $msg ){

        //$product_id = registerProduct($product_name, $category, $description, $filename, $save_path);

        //details tableの方のバリデーション
        $errorsD = [];
        for($i=0;$i<8;$i++){

            //3つとも空だったらバリデーションは掛けない。
              //if ($_POST['color'][$i] == '' && $_POST['size'][$i] == '' && $_POST['stock'][$i] == '') {
             if(empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])){
                continue;
              }
    
             //ここからバリデーション 
             
              if($_POST['color'][$i] == ''){
           
                 $errorsD[] = 'Please type color for detail'.' '.($i+1).'.';
    
              }
            
              if($_POST['size'][$i] == ''){
            
                  $errorsD[] = 'Please type size for detail'.' '.($i+1).'.';
    
              }
            
              if($_POST['stock'][$i] == ''){
           
                $errorsD[] = 'Please type stock for detail'.' '.($i+1).'.';
    
              }
        }
            //product detail part------------------------------------------------

        //if(empty($errorsD)){
        if(empty($errors) && empty($rrorsD) && !empty($msg)){

            $product_ids = registerProduct($product_name, $category, $description, $filename, $save_path);
            $product_id = $product_ids['product_id'];

var_dump($product_id['product_id']);
            for($i=0;$i<8;$i++){ 
                          
                   // if($product_id['product_id']){
                       // $product_id = $product_id['product_id'];

                        if(empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])){
                            continue;
                          }

                          $color = $_POST['color'][$i];
                          $size = $_POST['size'][$i];
                          $stock = $_POST['stock'][$i];
                    
                          $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);


                    //}else{
                        //$product_ids = getNewestProductId();
                        //$product_id = $product_ids['id'];

                        //if(empty($_POST['color'][$i]) && empty($_POST['size'][$i]) && empty($_POST['stock'][$i])){
                            
                          //continue;
                          //}

                       // $color = $_POST['color'][$i];
                       // $size = $_POST['size'][$i];
                      //  $stock = $_POST['stock'][$i];

                       // $completedRegisteringProduct = registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock);

                   //}

            }//for
            
            
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
                        <?php var_dump($_FILES);?>

                        <h2>Product Common Part</h2>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="add_product.php" method="post" enctype="multipart/form-data"> <!--novalidate-->
                            
                            <div class="form_item">
                                <label>Product Name<br>
                                　　&nbsp;&nbsp;&nbsp;&nbsp;<input class="wide" type="text" name="product_name" value="<?php if(isset($_POST['product_name'])){ echo h($_POST['product_name']);}?>" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Price<br>
                                　　&nbsp;¥&nbsp;<input class="wide" type="text" name="price" value="<?php if(isset($_POST['price'])){ echo h($_POST['price']);}?>" required>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Gender<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="radio" type="radio" name="gender" value="1" checked>Boys
                                    <input class="radio" type="radio" name="gender" value="2">Girls
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Category<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="wide" name="category">
                                        <option value=Dress>Dress</option>
                                        <option value=Jaket>Jaket</option>
                                        <option value=Pants>Pants</option>
                                        <option value=Shirt>Shirt</option>
                                        <option value=Skirt>Skirt</option>
                                        <option value=Shoes>Shoes</option>
                                        <option value=Sleeper>Sleeper</option>
                                        <option value=Sweater>Sweater</option>
                                        <option value=Other>Other</option>
                                    </select>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Description(in 400 words or less)<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea class="description" name="description" cols="80" rows="6"><?php if(isset($_POST['description'])){echo nl2br(h($_POST['description']));}?></textarea>
                            　　
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Weight<br>
                                　　&nbsp;&nbsp;&nbsp;&nbsp;<input class="wide" type="text" name="weight" value="<?php if(isset($_POST['weight'])){ echo h($_POST['weight']);}?>" required>&nbsp;g
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Image<br>
                                <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="img" type="file" accept="image/*"/><br>
                                    　<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                </label>-->

                                <?php if(!empty($_FILE['img'])):?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_FILES['tmp_name'];?>
                                <?php else:?>
                                    　<input name="img" type="file" accept="image/*"/><br>
                                    　<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                <?php endif ;?>
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
                        <strong>1.</strong>&nbsp;&nbsp;
                        <div class="form_item_box">
                                <div class="form-item">
                                
                                    <label>Color<br>
                                    　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][0])){ echo h($_POST['color'][0]);}?>">
                                    </label>
                                </div>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <div class="form_item">
                                    <label>Size<br>
                                    　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][0])){ echo h($_POST['size'][0]);}?>"> &nbsp;cm
                                    </label>
                            　　</div>

                                <div class="form-item ">
                                    <label>Stock<br>
                                    　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][0])){ echo h($_POST['stock'][0]);}?>">
                                    </label>
                                </div>
                        </div><!--form_item_box-->
                        <br>
                        <br>

                    <!-- 2nd ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                       <strong>2.</strong>&nbsp;&nbsp;
                       <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][1])){ echo h($_POST['color'][1]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][1])){ echo h($_POST['size'][1]);}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][1])){ echo h($_POST['stock'][1]);}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!--3rd  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <strong>3.</strong>&nbsp;&nbsp;
                            <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　  <input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][2])){ echo h($_POST['color'][2]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                          <input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][2])){ echo h($_POST['size'][2]);}?>">&nbsp;cm
                                        </label>
                                </div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                           <input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][2])){ echo h($_POST['stock'][2]);}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 4th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                             <strong>4.</strong>&nbsp;&nbsp;
                             <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][3])){ echo h($_POST['color'][3]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][3])){ echo h($_POST['size'][3]);}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][3])){ echo h($_POST['stock'][3]);}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 5th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <strong>5.</strong>&nbsp;&nbsp;
                            <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][4])){ echo h($_POST['color'][4]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][4])){ echo h($_POST['size'][4]);}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][4])){ echo h($_POST['stock'][4]);}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 6th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <strong>6.</strong>&nbsp;&nbsp;
                            <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][5])){ echo h($_POST['color'][5]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][5])){ echo h($_POST['size'][5]);}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][5])){ echo h($_POST['stock'][5]);}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 7th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <strong>7.</strong>&nbsp;&nbsp;
                            <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][6])){ echo h($_POST['color'][6]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][6])){ echo h($_POST['size'][6]);}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][6])){ echo h($_POST['stock'][6]);}?>">
                                        </label>
                                    </div>
                            </div><!--form_item_box-->
                            <br>
                            <br>

                        <!-- 8th  ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                             <strong>8.</strong>&nbsp;&nbsp;
                             <div class="form_item_box">
                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($_POST['color'][7])){ echo h($_POST['color'][7]);}?>">
                                        </label>
                                    </div>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <div class="form_item">
                                        <label>Size<br>
                                        　　<input calss="narrow inline" type="text" name="size[]" value="<?php if(isset($_POST['size'][7])){ echo h($_POST['size'][7]);}?>">&nbsp;cm
                                        </label>
                                　　</div>

                                    <div class="form-item ">
                                        <label>Stock<br>
                                        　　<input calss="narrow inline" type="text" name="stock[]" value="<?php if(isset($_POST['stock'][7])){ echo h($_POST['stock'][7]);}?>">
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