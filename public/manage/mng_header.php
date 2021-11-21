<?php
$unRepliedInquiry = getUnrepliedInquiry(1);
?>
<!--header for pc-->
<div class="header_pc">
<header>

    <div class="bh_container">

            <div class="blogo"><span><i class="fas fa-paw"></i>Bright-Shop</span></div>

            <div class="bnavi">
            <ul>
            <?php if(!empty($_SESSION['mgr'])):?>
                　<li class="blistH"><span><i class="fas fa-tshirt"></i>products</span>
                <ul>
                    <li><a href="/manage/products_list.php" class="blink_a"><span><i class="fas fa-clipboard-list"></i>Products List</span></a></li>
                    <li><a href="/manage/add_product.php" class="blink_a"><span><i class="fas fa-plus"></i>Add Product</span></a></li>
                </ul>
                </li>
                  <?php endif;?>
                  <li class="badj"><a href="/manage/orders_list.php" class="blink_a"><span><i class="fas fa-stopwatch"></i>Orders</span></a></li>
                  <li class="badj"><a href="/manage/users_list.php" class="blink_a"><span><i class="fas fa-address-book"></i>Users</span></a></li>
                  <li class="badj"><a href="/manage/mng_inquiry.php" class="blink_a"><span><i class="fas fa-bell"></i>Message(<?php echo $unRepliedInquiry['COUNT(*)'];?>)</span></a></li>
                  
                  <!--<li class="adj"><a href="/manage/send_info.php" class="link_a"><span>Send Info</span></a></li>-->

                <?php if(empty($_SESSION['mgr'])):?>
                  <li><a href="/manage/mng_signup.php" class="blink_a"><span><i class="fas fa-user"></i>Sign Up</span></a></li>
                  <li><a href="/manage/mng_login.php" class="blink_a"><span><i class="fas fa-lock"></i>Log In</span></a></li>
                <?php else:?>
                  <li class="blistH"><strong><i class="fas fa-user"></i><?php echo $_SESSION['mgr'][0]['mgr_name'];?></strong>
                  <ul>
                     <li><a href="/manage/mng_logout.php" class="blink_a"><span><i class="fas fa-sign-out-alt"></i>Log Out</span></a></li>
                     
                </ul>
                    </li>
                <?php endif;?>
              </ul>
         </div><!--navi-->
    </div><!--container-->

</header>
