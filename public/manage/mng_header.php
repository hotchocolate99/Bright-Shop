<!--header for pc-->
<div class="header_pc">
<header>

    <div class="h_container">

            <div class="logo"><span><i class="fas fa-paw"></i>Bright-Shop</span></div>

            <div class="navi">
            <ul>
            <?php if(!empty($_SESSION['mgr'])):?>
                　<li class="listH"><span><i class="fas fa-tshirt"></i>products</span>
                <ul>
                    <li><a href="/manage/products_list.php" class="link_a"><span><i class="fas fa-clipboard-list"></i>Products List</span></a></li>
                    <li><a href="/manage/add_product.php" class="link_a"><span><i class="fas fa-plus"></i>Add Product</span></a></li>
                </ul>
                </li>
                  <?php endif;?>
                  <li class="adj"><a href="/manage/orders_list.php" class="link_a"><span><i class="fas fa-stopwatch"></i>Orders List</span></a></li>
                  <li class="adj"><a href="/manage/users_list.php" class="link_a"><span><i class="fas fa-address-book"></i>Users List</span></a></li>
                  <!--<li class="adj"><a href="/manage/send_info.php" class="link_a"><span>Send Info</span></a></li>-->

                <?php if(empty($_SESSION['mgr'])):?>
                  <li><a href="/manage/mng_signup.php" class="link_a"><span><i class="fas fa-user"></i>Sign Up</span></a></li>
                  <li><a href="/manage/mng_login.php" class="link_a"><span><i class="fas fa-lock"></i>Log In</span></a></li>
                <?php else:?>
                  <li class="listH"><strong><i class="fas fa-user"></i><?php echo $_SESSION['mgr'][0]['mgr_name'];?></strong>
                  <ul>
                     <li><a href="/manage/mng_logout.php" class="link_a"><span><i class="fas fa-sign-out-alt"></i>Log Out</span></a></li>
                     
                </ul>
                    </li>
                <?php endif;?>
              </ul>
         </div><!--navi-->
    </div><!--container-->

</header>
