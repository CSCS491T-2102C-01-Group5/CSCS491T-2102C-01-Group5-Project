<?php
// (A) INIT
require "lib/core.php";
switch ($_POST['req']) {
  // (B) INVALID REQUEST
  default:
    echo "INVALID REQUEST";
    break;

  // (C) ADD ITEM TO CART
  case "add":
    $pass = $_CC->cartItem($_POST['product_id'], 1);
    echo json_encode([
      "status" => $pass,
      "message" => $pass ? "Item added to cart" : $_CC->error
    ]);
    break;

  // (D) CHANGE QTY
  case "change":
    $pass = $_CC->cartItem($_POST['product_id'], $_POST['qty'], false);
    echo json_encode([
      "status" => $pass,
      "message" => $pass ? "Quantity updated" : $_CC->error
    ]);
    break;

  // (E) COUNT TOTAL NUMBER OF ITEMS
  case "count":
    echo $_CC->cartCount();
    break;

  // (F) SHOW CART ITEMS
  case "show":
    $products = $_CC->cartGetAll();
    $sub = 0; $total = 0; ?>
    <h1>MY CART</h1>
    <table id="scTable">
      <tr>
        <th>Remove</th> <th>Qty</th> <th>Item</th> <th>Price</th>
      </tr>
      <?php
      if (count($_SESSION['cart'])>0) { foreach ($_SESSION['cart'] as $id => $qty) {
      $sub = $qty * $products[$id]['product_price'];
      $total += $sub; ?>
      <tr>
        <td>
          <input class="scDel bRed" type="button" value="X" onclick="cart.remove(<?= $id ?>);"/>
        </td>
        <td><input id='qty_<?= $id ?>' onchange='cart.change(<?= $id ?>);' type='number' value='<?= $qty ?>'/></td>
        <td><?= $products[$id]['product_name'] ?></td>
        <td><?= sprintf("$%0.2f", $sub) ?></td>
      </tr>
      <?php }} else { ?>
      <tr><td colspan="3">Cart is empty</td></tr>
      <?php } ?>
      <tr>
        <td colspan="2"></td>
        <td><strong>Grand Total</strong></td>
        <td><strong><?= sprintf("$%0.2f", $total) ?></strong></td>
      </tr>
    </table>
    <?php if (count($_SESSION['cart']) > 0) { ?>
    <form id="scCheckout" onsubmit="return cart.checkout();">
      <h1>CHECKOUT</h1>
      <label>Name</label>
      <input type="text" id="co_name" required value="John Doe"/>
      <label>Email</label>
      <input type="email" id="co_email" required value="john@doe.com"/>
      <input type="submit" class="bRed" value="Go"/>
    </form>
    <?php }
    break;

  // (G) CHECKOUT - DO YOUR SECURITY CHECKS + PAYMENT HERE!
  case "checkout":
    $pass = $_CC->cartCheckout($_POST['name'], $_POST['email']);
    echo json_encode([
      "status" => $pass,
      "message" => $pass ? "Order confirmed" : $_CC->error
    ]);
    break;
}