<!DOCTYPE html>
<html>
  <head>
    <title>Group 5 Project - CTU Bookstore Website</title>
    <link rel="stylesheet" href="public/cart.css">
    <script src="public/cart.js"></script>
  </head>
  <body>
    <!-- (A) HEADER -->
    <header id="scHead">
      <div id="scTitle">CTU-Bookstore Project Website</div>
      <div id="scCartIcon" onclick="cart.toggle();">
        &#128722; <span id="scCartCount">0</span>
      </div>
    </header>

    <!-- (B) PRODUCTS -->	
    <div id="scProduct"><?php
      // (B1) GET PRODUCTS
      require "lib/core.php";
      $products = $_CC->pdtGetAll();

      // (B2) LIST PRODUCTS
      if (is_array($products)) { foreach ($products as $id => $row) { ?>
      <div class="pdt">
        <img class="pImg" src="public/<?= $row['product_image'] ?>"/>
        <h3 class="pName"><?= $row['product_name'] ?></h3>
        <div class="pPrice">$<?= $row['product_price'] ?></div>
        <div class="pDesc"><?= $row['product_description'] ?></div>
        <input class="pAdd bRed" type="button" value="Add to cart" onclick="cart.add(<?= $row['product_id'] ?>);"/>
      </div>
      <?php }} else { echo "No products found."; }
    ?></div>

    <!-- (C) CART -->
    <div id="scCart" class="ninja"></div>
  </body>
</html>
