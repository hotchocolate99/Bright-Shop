<?php
require_once 'database.php';

//management_side------------------------------------------------------------------------
//create account of manager  ok
function createManager($mngData){

    $result = false;

    $sql = "INSERT INTO managers (mgr_name, mgr_pass) VALUE(:mgr_name, :mgr_pass)";
  
  //パスワードはここでハッシュ化すること！！　DBに入れる時！「password_hash(パスワード,PASSWORD_DEFAULT);」 とする。
  //第二引数は決まり文句。意味：デフォルトでハッシュ化する。

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':mgr_name', $mngData['mgr_name'],PDO::PARAM_STR);
    $stmt->bindValue(':mgr_pass', password_hash($mngData['mgr_pass'],PASSWORD_DEFAULT),PDO::PARAM_STR);
    $result = $stmt->execute();

    return $result;

}


// to log in as a mananger   ok
function findManagerByName($dbh, $mgr_name){

    $sql = "SELECT * FROM managers WHERE mgr_name = :mgr_name";

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':mgr_name', $mgr_name, PDO::PARAM_STR);
    $stmt->execute();
    $mgr = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    return $mgr;
  
}


//最新の商品登録方法  ok
function registerProduct($product_name, $category, $description, $filename, $save_path){

        $sql = "INSERT INTO products(product_name, category, description, filename, save_path)
                                    VALUES(:product_name, :category, :description, :filename, :save_path)";
 
        $dbh = dbConnect();
 
         $stmt = $dbh->prepare($sql);
         $stmt->bindValue(':product_name', $product_name,PDO::PARAM_STR);
         $stmt->bindValue(':category', $category,PDO::PARAM_STR);
         $stmt->bindValue(':description', $description,PDO::PARAM_STR);
         $stmt->bindValue(':filename', $filename,PDO::PARAM_STR);
         $stmt->bindValue(':save_path', $save_path,PDO::PARAM_STR);
         $stmt->execute();
 
         //$common_id = $dbh->lastInsertId();
 
         $sql = "SELECT id as product_id FROM products WHERE product_name = :product_name";
 
         $stmt = $dbh->prepare($sql);
         $stmt->bindValue(':product_name', $product_name,PDO::PARAM_STR);
         $stmt->execute();
         $product_id = $stmt->fetch();
 
         return $product_id;
}

//商品の詳細(色、サイズ、在庫)の登録  ok
function registerProductDetail($product_id, $price, $gender, $weight, $color, $size, $stock){

        $sql = "INSERT INTO product_details(product_id, price, gender, weight, color, size, stock)VALUES(:product_id, :price, :gender, :weight, :color, :size, :stock)";

            $dbh = dbConnect();
        try{
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':product_id',$product_id,PDO::PARAM_INT);
            $stmt->bindValue(':price', $price,PDO::PARAM_INT);
            $stmt->bindValue(':gender', $gender,PDO::PARAM_INT);
            $stmt->bindValue(':weight', $weight,PDO::PARAM_INT);
            $stmt->bindValue(':color',$color,PDO::PARAM_STR);
            $stmt->bindValue(':size',$size,PDO::PARAM_INT);
            $stmt->bindValue(':stock',$stock,PDO::PARAM_INT);
            $stmt->execute();

        }catch(PDOException $e){
        //exit($e);
        }
        
}

//商品データ(product table)の取得
function getProductData($product_id){
         $sql = "SELECT * FROM products WHERE id = :id";
         //$sql = "SELECT * FROM products JOIN product_details ON product_details.product_id = products.id WHERE products.id = :id";

         $dbh = dbConnect();

         $stmt = $dbh->prepare($sql);
         $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
         $stmt->execute();
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
         return $results;

}

//商品詳細データ(product　details table)の取得
function getProductDetails($product_id){
    $sql = "SELECT * FROM product_details WHERE product_id = :product_id";

             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;
    
    }

    //商品の詳細データを表示順に取得したい
  //まず色でグループ分けして、サイズが小さい順に取得

//色の種類の数を取得ーーー！SQL文を書く時の注意　⇨　順番はWHEREが先（処理される順番が先だから）。GROUP BYは後。それから、HAVING、SELECTの順に処理される。
function getProductColors($product_id){
    $sql = "SELECT color FROM product_details WHERE product_id = :product_id GROUP BY color";

             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

             return $results;
    
    }

//上で取得した色とproduct_idで、サイズが小さい順にdetails.idなどの全てのデータを取得。（これらは全て同じテーブルに表示する。）
function getProductDetailsByColor($product_id, $color){
   
    $sql = "SELECT * FROM product_details WHERE product_id = :product_id AND color = :color ORDER BY size ASC";

             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
             $stmt->bindValue(':color', $color,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;
    
}


function getProductByColor($product_id){
        $sql = "SELECT id, color, size FROM product_details WHERE product_id = :product_id";
    
                 $dbh = dbConnect();
         
                 $stmt = $dbh->prepare($sql);
                 $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
                 $stmt->execute();
                 $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
                 return $results;
        
}


        


//扱いにくいので、やっぱり、product table　と details table　のデータは別々に取得することにした。　全商品詳細データ(product　details table)の取得
function getNewestProductsDatas($gender){
    
    $sql = "SELECT products.*, product_details.price FROM products JOIN product_details ON products.id = product_details.product_id WHERE gender = :gender ORDER BY products.id DESC LIMIT 1";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':gender', $gender,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;
    
}

function getSecondNewestProductsDatas($gender, $newest_id){
        
        $sql = "SELECT products.*, price FROM products JOIN product_details ON products.id = product_details.product_id WHERE gender = :gender AND products.id < :id ORDER BY products.id DESC LIMIT 1";
    
        $dbh = dbConnect();

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':gender', $gender,PDO::PARAM_INT);
        $stmt->bindValue(':id', $newest_id,PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;

}

//products table　の全データを取得
function getAllProductsDatas(){
        $sql = "SELECT * FROM products";
    
                 $dbh = dbConnect();
         
                 $stmt = $dbh->prepare($sql);
                 $stmt->execute();
                 $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
                 return $results;
        
}

function getCountProducts(){
        $sql = "SELECT count(*) AS count_products FROM products";
        
                $dbh = dbConnect();
             
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch();
             
                return $result;
            
 }


//detailsの数を取得 no need?????
function getCountProductDetails($product_id){
        $sql = "SELECT count(*) FROM product_details WHERE product_id = :product_id";
            
                $dbh = dbConnect();
                 
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch();
                 
                return $result;
                
}


//shopping-------------------------------------------------------------------------

//上のが使えないので、まず、detail_idからproduct_idを取得し、それからproducts table　のデータを取得する。

//これでproduct_id,color,sizeを取得
function getProductByDetail($detail_id){
    
    $sql = "SELECT * FROM product_details WHERE id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;

}

//detail_idからproduct tableのproduct_name、画像を取得する ok
function getProductDataByDetail($detail_id){

    $sql = "SELECT product_id FROM product_details WHERE id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
             $stmt->execute();
             $product_id = $stmt->fetch();
     
    $sql = "SELECT product_name, save_path FROM products WHERE id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;
}




//次にproduct_name, save_pathを取得
function getProductNameFile($product_id){
$sql = "SELECT product_name, save_path FROM products WHERE id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;

}
//--------------------------------------------------------------------------------------

function getColorSize($detail_id){

    $sql = "SELECT color, size FROM product_details WHERE id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;
             
}

//details_idからstock数を取得
function getStockByDetailId($detail_id){

    $sql = "SELECT stock FROM product_details WHERE id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;

}

//details_idからproductst tableのデータも全て取得
function getAllByDetailId($detail_id){

    $sql = "SELECT * FROM product_details JOIN products ON product_details.product_id = products.id  WHERE product_details.id = :id";
    
             $dbh = dbConnect();
     
             $stmt = $dbh->prepare($sql);
             $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
             $stmt->execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
             return $results;

}

//商品の在庫数を変更する。
function updateStock($detail_id, $detail_count){

    $sql = "UPDATE product_details SET
                    stock = stock - :detail_count WHERE id = :id;";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
            $stmt->bindValue(':detail_count', $detail_count,PDO::PARAM_INT);
            $stmt->execute();

    
}

//注文内容を登録(order table　が先)をしたら、orders tableのidとordered_atを戻り値にする。　OK 問題はreturnの後にcommitしていたことだった。。。ドジさに悲しくなる。。。
function putOrderDatas($user_id, $shipping_fee, $sub_total, $tax, $total_charge, $pay_ways, $ordered_at){

        $sql = "INSERT INTO orders (user_id, shipping_fee, sub_total, tax, total_charge, pay_ways, ordered_at)
                            VALUES(:user_id, :shipping_fee, :sub_total, :tax, :total_charge, :pay_ways, :ordered_at)";

            $dbh = dbConnect();
            $dbh->beginTransaction();

        try{
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
            $stmt->bindValue(':shipping_fee', $shipping_fee,PDO::PARAM_INT);
            $stmt->bindValue(':sub_total', $sub_total,PDO::PARAM_INT);
            $stmt->bindValue(':tax', $tax,PDO::PARAM_INT);
            $stmt->bindValue(':total_charge',$total_charge,PDO::PARAM_INT);
            $stmt->bindValue(':pay_ways',$pay_ways,PDO::PARAM_INT);
            $stmt->bindValue(':ordered_at',$ordered_at,PDO::PARAM_STR);
            $stmt->execute();

            $id = $dbh->lastInsertId();
    
            $sql = "SELECT id, ordered_at FROM orders WHERE id = :id";
            
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $dbh->commit();
            return $results;
            

            }catch(PDOException $e){
                $dbh->rollBack();
            exit($e);
            }

}



//上で取得したidをorder_idとしてorder_details tableに詳細を登録する。 ok
function putOrderDetails($order_id, $detail_id, $price, $qty){

    $sql = "INSERT INTO order_details (order_id, detail_id, price, qty)VALUES(:order_id, :detail_id, :price, :qty)";

    $dbh = dbConnect();

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':order_id',$order_id,PDO::PARAM_INT);
    $stmt->bindValue(':detail_id', $detail_id,PDO::PARAM_INT);
    $stmt->bindValue(':price', $price,PDO::PARAM_INT);
    $stmt->bindValue(':qty', $qty,PDO::PARAM_INT);
    
    $result = $stmt->execute();

} 

//orders.idでorders tableの全データを取得
function getAllFromAordersTable($id){

    $dbh = dbConnect();

    $sql = "SELECT * FROM orders WHERE id = :id";
            
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
}


//ユーザーidでそのユーザーの注文全てをorders tableから取得する
function getAllOrders(){

    $sql = "SELECT * FROM orders";
            
            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
}

//orders table のidを使って、紐づけられた　order_details tableのデータを取得
function getOrderDetails($order_id){

    $sql = "SELECT * FROM order_details WHERE order_id = :order_id ORDER BY id";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':order_id', $order_id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
}

//product_details table のid を使ってproduct_details tableからそのidの商品のデータを取得
function getProductDetailsByDetailId($detail_id){

    $sql = "SELECT * FROM product_details WHERE id = :id ORDER BY id";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;

}


//第３案は２回に分けて処理をする。
function getProductIdByDetailId($detail_id){

    $sql = "SELECT product_id FROM product_details WHERE id = :id";

    $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
            $stmt->execute();
            $products_id = $stmt->fetch();

            return $products_id;
}

function getProductDatasById($product_id){
    $sql = "SELECT * FROM products WHERE id = :id";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
}


//--------------------------------------------------------------------------------------------
//register to become a member  ok
function createUser($userData, $addr_pref, $ship_area){

    $result = false;

    $sql = "INSERT INTO users (title, usr_name, usr_email, usr_pass, tel, postal, addr_pref, addr_city, addr_last, ship_area, ad_request) 
                         VALUE(:title, :usr_name, :usr_email, :usr_pass, :tel, :postal, :addr_pref, :addr_city, :addr_last, :ship_area, :ad_request)";
  
  //パスワードはここでハッシュ化すること！！　DBに入れる時！「password_hash(パスワード,PASSWORD_DEFAULT);」 とする。
  //第二引数は決まり文句。意味：デフォルトでハッシュ化する。

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':title', $userData['title'],PDO::PARAM_STR);
    $stmt->bindValue(':usr_name', $userData['usr_name'],PDO::PARAM_STR);
    $stmt->bindValue(':usr_email', $userData['usr_email'],PDO::PARAM_STR);
    $stmt->bindValue(':usr_pass', password_hash($userData['usr_pass'],PASSWORD_DEFAULT),PDO::PARAM_STR);
    $stmt->bindValue(':tel', $userData['tel'],PDO::PARAM_STR);
    $stmt->bindValue(':postal', $userData['postal'],PDO::PARAM_STR);
    $stmt->bindValue(':addr_pref', $addr_pref,PDO::PARAM_STR);
    $stmt->bindValue(':addr_city', $userData['addr_city'],PDO::PARAM_STR);
    $stmt->bindValue(':addr_last', $userData['addr_last'],PDO::PARAM_STR);
    $stmt->bindValue(':ship_area', $ship_area,PDO::PARAM_INT);
    $stmt->bindValue(':ad_request', $userData['ad_request'],PDO::PARAM_INT);

    $result = $stmt->execute();

    return $result;

}


//update account info
function updateUser($user_id, $userData, $addr_pref, $ship_area){

    $result = false;

    $sql = "UPDATE users SET title = :title, usr_name = :usr_name, usr_email = :usr_email, usr_pass = :usr_pass, tel = :tel, postal = :postal, addr_pref = :addr_pref, addr_city = :addr_city, addr_last = :addr_last, ship_area = :ship_area, ad_request = :ad_request
                         WHERE id = :id";
  
  //パスワードはここでハッシュ化すること！！　DBに入れる時！「password_hash(パスワード,PASSWORD_DEFAULT);」 とする。
  //第二引数は決まり文句。意味：デフォルトでハッシュ化する。

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $user_id,PDO::PARAM_INT);
    $stmt->bindValue(':title', $userData['title'],PDO::PARAM_STR);
    $stmt->bindValue(':usr_name', $userData['usr_name'],PDO::PARAM_STR);
    $stmt->bindValue(':usr_email', $userData['usr_email'],PDO::PARAM_STR);
    $stmt->bindValue(':usr_pass', password_hash($userData['usr_pass'],PASSWORD_DEFAULT),PDO::PARAM_STR);
    $stmt->bindValue(':tel', $userData['tel'],PDO::PARAM_STR);
    $stmt->bindValue(':postal', $userData['postal'],PDO::PARAM_STR);
    $stmt->bindValue(':addr_pref', $addr_pref,PDO::PARAM_STR);
    $stmt->bindValue(':addr_city', $userData['addr_city'],PDO::PARAM_STR);
    $stmt->bindValue(':addr_last', $userData['addr_last'],PDO::PARAM_STR);
    $stmt->bindValue(':ship_area', $ship_area,PDO::PARAM_INT);
    $stmt->bindValue(':ad_request', $userData['ad_request'],PDO::PARAM_INT);

    $result = $stmt->execute();

    return $result;

}

// to log in  ok
function findUserByEmail($dbh, $usr_email){

    $sql = "SELECT * FROM users WHERE usr_email = :usr_email";

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':usr_email', $usr_email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    return $user;
  
}


// get user data to get orders done.  and update user data.   ok
function findUserByUserId($usr_id){

    $sql = "SELECT * FROM users WHERE id = :id";

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $usr_id, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    return $user;
  
}


//users_list ok
function getAllusersDatas(){

    $sql = "SELECT * FROM users";

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
  
}

//users_list ok
function getCountUsers(){

    $sql = "SELECT count(*) as count_usrs FROM users";

    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();

    return $result;
  
}


/**XSS対策エスケープ  ok
     * ＠param str $s
     * return method htmlspecialchars($s, ENT_QUOTES, "UTF-8");
     */
    if(!function_exists('h')) {
        function h($s){
            return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
        }
    }

    //数字を値段表示にする  ok
    function n($price){
        return number_format($price);
    }


    //genderを数字から文字列へ変換  ok
    function setGender($gender){
        if($gender == 1){
            return 'Boys';
        }

        if($gender == 2){
            return 'Girls';
        }
    }


    function setShippingStatus($shipping_status){
        if($shipping_status == 1){
            return 'Shipping is being prepared.';
        }

        if($shipping_status == 2){
            return  " Your oreder is on it's way.";
        }
    }

    

//ship_areaから送料を算出 
    function setShippingFee($ship_area){

        //北海道
        switch($ship_area){
            case 1:
                return 1300;
                break;
        

        //東北
            case 2:
                return 1000;
                break;
        

        //関東
            case 3:
                return 800;
                break;
        

        //北信越、中部
            case 4 || 5:
                return 800;
                break;

        //関西
            case 6:
                return 1000;
                break;

        //中国、四国
            case 7 || 8:
                return 1200;
                break;

        //九州、沖縄
            case 9 || 10:
                return 1300;
                break;
        
        //その他
            case 11:
                return 1500;
                break;

       }
    }


   //delete product from products table  ok
    function deleteProduct($product_id){
        
        $sql = "DELETE FROM products WHERE id = :id";
        
        $dbh = dbConnect();
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', (int)$product_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    //delete detail from product details table  ok
    function deleteDetails($productDetail_id){
        
        $sql = "DELETE FROM product_details WHERE id = :id";
        
        $dbh = dbConnect();
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $productDetail_id, PDO::PARAM_INT);
        $stmt->execute();
    }


    function updateProduct($product_id, $product_name, $category, $description, $filename, $save_path){

        $sql = "UPDATE products SET
                    product_name = :product_name, category = :category, description = :description, filename = :filename, save_path = :save_path
                    WHERE id = :id;";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
            $stmt->bindValue(':product_name', $product_name,PDO::PARAM_STR);
            $stmt->bindValue(':category', $category,PDO::PARAM_STR);
            $stmt->bindValue(':description', $description,PDO::PARAM_STR);
            $stmt->bindValue(':filename', $filename,PDO::PARAM_STR);
            $stmt->bindValue(':save_path', $save_path,PDO::PARAM_STR);
           
            $stmt->execute();
    }

   function  updateProductDetail($detail_id, $product_id, $price, $gender, $weight, $color, $size, $stock){

        $sql = "UPDATE product_details SET
                    product_id = :product_id, price = :price, gender= :gender, weight = :weight, color = :color, size = :size, stock = :stock
                    WHERE id = :id;";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue('id', $detail_id,PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
            $stmt->bindValue(':price', $price,PDO::PARAM_INT);
            $stmt->bindValue(':gender', $gender,PDO::PARAM_INT);
            $stmt->bindValue(':weight', $weight,PDO::PARAM_INT);
            $stmt->bindValue(':color', $color,PDO::PARAM_STR);
            $stmt->bindValue(':size', $size,PDO::PARAM_INT);
            $stmt->bindValue(':stock', $stock,PDO::PARAM_INT);
           
            $stmt->execute();
   }

   //update product data with keeping previous image.
   function updateProductWithoutImg($product_id, $product_name, $category, $description){

        $sql = "UPDATE products SET
                    product_name = :product_name, category = :category, description = :description
                    WHERE id = :id;";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
            $stmt->bindValue(':product_name', $product_name,PDO::PARAM_STR);
            $stmt->bindValue(':category', $category,PDO::PARAM_STR);
            $stmt->bindValue(':description', $description,PDO::PARAM_STR);

            $stmt->execute();
   }




//token for purchase.php
    function generateCsrfToken() {
        return hash('sha256', session_id());
    } 


    //get all girls products
    //まず、details table　でgenderごとに分けてprodut_id を取得
    function getAllProductsIdByGender($gender){
    
        $sql = "SELECT product_id FROM product_details WHERE gender = :gender GROUP BY product_id";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':gender', $gender,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $results;
    }

//次にproduct id で　products tableからデータを取得する。
    function getProductsDataById($girls_product_id){

        $sql = "SELECT * FROM products WHERE id = :id ORDER BY id DESC";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $girls_product_id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $results;
    }

    //product_id でproduct_details table からpriceを取得
    function getPriceByProductId($product_id){

        $sql = "SELECT price FROM product_details WHERE product_id = :product_id";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
             
            return $result;
    }


    //レビューの投稿
    function postedReview($user_id, $product_id, $code_name, $review_comment){

        $result = false;

        $sql = "INSERT INTO reviews (user_id, product_id, code_name, review_comment) 
                              VALUE(:user_id, :product_id, :code_name, :review_comment)";

        $dbh = dbConnect();
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id,PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
        $stmt->bindValue(':code_name', $code_name,PDO::PARAM_STR);
        $stmt->bindValue(':review_comment', $review_comment,PDO::PARAM_STR);

        $result = $stmt->execute();

        return $result;

    }

    //レビューの取得
    function getAllReviewsByProductId($product_id){

        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':product_id', $product_id,PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $result;
    }



    //order_idでproduct_details_id 取得する。それと、購入時の値段、数量も。ok
    function getAllOrderDetailsByOrderId($order_id){

        $sql = "SELECT detail_id, price, qty FROM order_details WHERE order_id = :order_id";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':order_id', $order_id,PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $result;
    }


//orders table から　取得した$detail_id　を使ってprodcut_details table からprice、product_idを取得する。ok
    function get_Id_productId_color_size_BydetailId($detail_id){

        $sql = "SELECT id, product_id, color, size FROM product_details WHERE id = :id";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $detail_id,PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $result;
    }

    //$product_idを使って、product tableから商品名と画像を取得する。
    function getProductName_savePath($product_id){

        $sql = "SELECT product_name, save_path FROM products WHERE id = :id";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':id', $product_id,PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
            return $result;
    }


    function contact($title, $name, $email, $subject, $content){

        $sql = "INSERT INTO inquiries (title, name, email, subject, content) 
                              VALUE(:title, :name, :email, :subject, :content)";

        $dbh = dbConnect();
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':title', $title,PDO::PARAM_STR);
        $stmt->bindValue(':name', $name,PDO::PARAM_STR);
        $stmt->bindValue(':email', $email,PDO::PARAM_STR);
        $stmt->bindValue(':subject', $subject,PDO::PARAM_STR);
        $stmt->bindValue(':content', $content,PDO::PARAM_STR);

        $result = $stmt->execute();

        return $result;
    }

    
    function getAllOrdersForLists(){

        $sql = "SELECT * FROM orders";
            
        $dbh = dbConnect();
         
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
        return $result;
    }

    function getAllOrdersByUser($user_id){

        $sql = "SELECT * FROM orders WHERE user_id = :user_id";
            
        $dbh = dbConnect();
         
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue('user_id', $user_id,PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
        return $result;
    }



    function changeShippingStatus($order_id, $shipping_status){

        $sql = "UPDATE orders SET
                    shipping_status = :shipping_status WHERE id = :id;";

            $dbh = dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue('id', (int)$order_id,PDO::PARAM_INT);
            $stmt->bindValue('shipping_status', (int)$shipping_status,PDO::PARAM_INT);

            $stmt->execute();
    }


    function getNewestProductId(){
        
        $sql = "SELECT id FROM products ORDER BY id DESC limit 1";
            
            $dbh = dbConnect();
             
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
             
            return $result;
    }

//商品データの更新か新規登録かを判別するために、product_details tableのidを取得
    function findDetailId($detail_id){

        $result = false;

        $sql = "SELECT * FROM product_details WHERE id = :id";
    
        $dbh = dbConnect();
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $detail_id, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
      
    
    }