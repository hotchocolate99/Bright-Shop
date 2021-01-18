<!--header for pc-->
<div class="header_pc">
<header>

    <div class="h_container">

            <div class="logo"><span>Bright-Shop</span></div>

            <div class="navi">
            <ul>
            <?php if(!empty($_SESSION['mgr'])):?>
                　<li class="listH"><span>products</span>
                <ul>
                    <li><a href="/public/manage/products_list.php" class="link_a"><span>Products List</span></a></li>
                    <li><a href="/public/manage/add_product.php" class="link_a"><span>Add Product</span></a></li>
                </ul>
                </li>
                  <?php endif;?>
                  <li class="adj"><a href="/public/manage/orders_list.php" class="link_a"><span>Orders</span></a></li>
                  <li class="adj"><a href="/public/manage/users_list.php" class="link_a"><span>Users List</span></a></li>
                  <li class="adj"><a href="/public/manage/send_info.php" class="link_a"><span>Send Info</span></a></li>

                <?php if(empty($_SESSION['mgr'])):?>
                　<li><a href="/public/manage/mng_login.php" class="link_a"><span><i class="fas fa-lock"></i>Log In</span></a></li>
                <?php else:?>
                  <li class="listH"><strong><?php echo $_SESSION['mgr'][0]['mgr_name'];?></strong>
                  <ul>
                     <li><a href="/public/manage/mng_logout.php" class="link_a"><span><i class="fas fa-sign-out-alt"></i>Log Out</span></a></li>
                     <li><a href="/public/manage/mng_signup.php" class="link_a"><span><i class="fas fa-user"></i>Account</span></a></li>
                </ul>
                    </li>
                <?php endif;?>
              </ul>
         </div><!--navi-->
    </div><!--container-->

</header>
