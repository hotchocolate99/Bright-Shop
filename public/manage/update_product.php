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

//update--------------------------------------------

//products list　ページからGETでproducts tableのidが飛んでくる。
if($_GET['product_id']){
    $product_id = $_GET['product_id'];
}
var_dump($product_id);
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
        echo $val;
        /*$detail_id = $val['id'];
        $color = $val['color'];
        $size = $val['size'];
        $stock = $val['stock'];*/
    }
}

echo' ディテール';
/*var_dump($productDetail[0]['id']);//81
var_dump($productDetail[0]['size']);//81

var_dump($productDetail[1]['id']);//82
var_dump($productDetail[1]['size']);//81
/*

var_dump($productDetails[0]['id']);//81
var_dump($productDetails[0]['size']);//81

var_dump($productDetails[1]['id']);//82
var_dump($productDetails[1]['size']);//81*/


?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Product</title>
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

                        <form action="updated_product.php" method="post" enctype="multipart/form-data"> //novalidate
                        <input type="hidden" name="product_id" value="<?php echo h($product_id) ?>">
                        <input type="hidden" name="detail_id" value="<?php echo h($productDetails[0]['id']) ?>">
                        <input type="hidden" name="detail_id" value="<?php echo h($productDetails[1]['id']) ?>">


                            <div class="form_item">
                                <label>Product Name<br>
                                　　<input class="wide" type="text" name="product_name" value="<?php if(isset($product_name)){ echo h($product_name);}?>" required>
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
                            　　　　　<textarea class="description" name="description" cols="80" rows="6">
                                       <?php if(isset($description)){echo h($description);}?>
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
                                    <img src="/public/manage/<?php echo "{$save_path}";?>"　width="120px" height="200px" alt="product_image" >
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
                                    <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <?php endif ;?>
                                </div>


                                <div class="form-item">
                                    <label>Color<br>
                                    　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[0]['color'])){ echo h($productDetails[0]['color']);}else{echo 'opt';}?>">
                                       <input type="hidden" name="detail_id" value="<?php echo $productDetails[0]['id'];?>">
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
                                        <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>
                                    </div>

                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[1]['color'])){ echo h($productDetails[1]['color']);}else{echo 'opt';}?>">
                                           <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[1]['id'])){ echo h($productDetails[1]['id']);}?>">
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
                                        <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[2]['color'])){ echo h($productDetails[2]['color']);}else{echo 'opt';}?>">
                                           <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[2]['id'])){ echo h($productDetails[2]['id']);}?>">
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
                                        <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[3]['color'])){ echo h($productDetails[3]['color']);}else{echo 'opt';}?>">
                                            <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[3]['id'])){ echo h($productDetails[3]['id']);}?>">
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
                                        <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[4]['color'])){ echo h($productDetails[4]['color']);}else{echo 'opt';}?>">
                                           <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[4]['id'])){ echo h($productDetails[4]['id']);}?>">
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
                                        <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>

                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[5]['color'])){ echo h($productDetails[5]['color']);}else{echo 'opt';}?>">
                                           <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[5]['id'])){ echo h($productDetails[5]['id']);}?>">
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
                                             <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>
                                    </div>


                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[6]['color'])){ echo h($productDetails[6]['color']);}else{echo 'opt';}?>">
                                           <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[6]['id'])){ echo h($productDetails[6]['id']);}?>">
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
                                             <?php else:?>
                                            <p><?php echo 'Add here';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <?php endif ;?>
                                    </div>

                                    <div class="form-item">
                                        <label>Color<br>
                                        　　<input calss="narrow inline" type="text" name="color[]" value="<?php if(isset($productDetails[7]['color'])){ echo h($productDetails[7]['color']);}else{echo 'opt';}?>">
                                           <input type="hidden" name="detail_id" value="<?php if(isset($productDetails[7]['id'])){ echo h($productDetails[7]['id']);}?>">
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

