<?php
class CartCore {
  // (A) CORE FUNCTIONS
  // (A1) PROPERTIES
  public $pdo = null;
  public $stmt = null;
  public $error = "";

  // (A2) CONSTRUCTOR - CONNECT TO DATABASE
  function __construct() {
    try {
      $this->pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER, DB_PASSWORD, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    } catch (Exception $ex) { exit($ex->getMessage()); }
  }

  // (A3) DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct() {
    if ($this->stmt !== null) { $this->stmt = null; }
    if ($this->pdo !== null) { $this->pdo = null; }
  }

  // (A4) HELPER FUNCTION - EXECUTE SQL QUERY
  function query ($sql, $data=null) {
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      return true;
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
  }

  // (B) PRODUCT FUNCTIONS
  // (B1) GET ALL PRODUCTS
  function pdtGetAll () {
    $this->query("SELECT * FROM `products`");
    $pdts = [];
    while ($row = $this->stmt->fetch()) { $pdts[$row['product_id']] = $row; }
    return count($pdts)==0 ? null : $pdts ;
  }

  // (B2) GET PRODUCT
  function pdtGet ($id) {
    $this->query("SELECT * FROM `products` WHERE `product_id`=?", [$id]);
    return $this->stmt->fetch();
  }

  // (C) CART FUNCTIONS
  // (C1) UPDATE ITEM IN CART
  function cartItem ($id, $qty, $add=true) {
    // CHECK ID AND QTY
    if (!is_numeric($id) || !is_numeric($qty)) {
      $this->error = "Invalid product ID or quantity";
      return flase;
    }

    // REMOVE ITEM
    if ($qty==0) { unset($_SESSION['cart'][$id]); }

    // ADD ITEM TO CART
    else if ($add) {
      if (is_numeric($_SESSION['cart'][$id])) { $_SESSION['cart'][$id] += $qty; }
      else { $_SESSION['cart'][$id] = $qty;  }
    }

    // SET TO EXACT QUANTITY
    else { $_SESSION['cart'][$id] = $qty; }

    // OVER MAX
    if ($_SESSION['cart'][$id] > CART_MAX) { $_SESSION['cart'][$id] = CART_MAX; }

    return true;
  }

  // (C2) COUNT ITEMS IN CART
  function cartCount () {
    $total = 0;
    if (count($_SESSION['cart'])>0) {
      foreach ($_SESSION['cart'] as $id => $qty) { $total += $qty; }
    }
    return $total;
  }

  // (C3) GET ALL ITEMS IN CART
  function cartGetAll () {
    // CART EMPTY
    if (count($_SESSION['cart'])==0) { return false; }

    // GET PRODUCTS IN CART
    $sql = "SELECT * FROM `products` WHERE `product_id` IN (";
    $sql .= str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
    $sql .= ")";
    $this->query($sql, array_keys($_SESSION['cart']));
    $pdts = [];
    while ($row = $this->stmt->fetch()) { $pdts[$row['product_id']] = $row; }
    return count($pdts)==0 ? null : $pdts ;
  }

  // (C4) CHECKOUT
  function cartCheckout ($name, $email) {
    // AUTO COMMIT OFF
    $this->pdo->beginTransaction();

    // CREATE NEW ORDER
    $pass = $this->query(
      "INSERT INTO `orders` (`order_name`, `order_email`) VALUES (?, ?)",
      [$name, $email]
    );

    // INSERT THE ITEMS
    if ($pass) {
      $this->orderID = $this->pdo->lastInsertId();
      $sql = "INSERT INTO `orders_items` (`order_id`, `product_id`, `quantity`) VALUES ";
      $cond = [];
      foreach ($_SESSION['cart'] as $id=>$qty) {
        $sql .= "(?, ?, ?),";
        array_push($cond, $this->orderID, $id, $qty);
      }
      $sql = substr($sql, 0, -1) . ";";
      $pass = $this->query($sql, $cond);
    }

    // FINALIZE
    if ($pass) { 
      $this->pdo->commit(); 
      $_SESSION['cart'] = [];
    } else { $this->pdo->rollBack(); }
    
    // SEND EMAIL
    if ($pass) { if (!$this->emailOrder($this->orderID)) {
      $this->error = "Order saved, but error sending mail";
    }}
    
    return $pass;
  }

  // (C5) EMAIL ORDER TO CUSTOMER
  function emailOrder ($id) {
    // GET ORDER
    $this->query("SELECT * FROM `orders` WHERE `order_id`=?", [$id]);
    $order = $this->stmt->fetch();
    if (!is_array($order)) {
      $this->error = "Invalid order";
      return false;
    }

    // GET ITEMS
    $this->query("SELECT * FROM `orders_items` LEFT JOIN `products` USING (`product_id`) WHERE `orders_items`.order_id=?", [$id]);
    $order['items'] = [];
    while ($row = $this->stmt->fetch()) { $order['items'][$row['product_id']] = $row; }

    // FORMAT EMAIL - CHANGE AS YOU SEE FIT
    $mailTo = $order['order_email'];
    $mailSubject = "Order Received";
    $mailBody = "Dear " . $order['order_name'] . ",<br>";
    $mailBody .= "Thank you and we have received your order.<br>";
    foreach ($order['items'] as $pid=>$p) {
      $mailBody .= $p['product_name'] . " - " . $p['quantity'] . "<br>";
    }
    $mailHead = implode("\r\n", [
      'MIME-Version: 1.0',
      'Content-type: text/html; charset=utf-8'
    ]);

    // SEND EMAIL
    if (@mail($mailTo, $mailSubject, $mailBody, $mailHead)) {
      return true;
    } else {
      $this->error = "Error sending email";
      return false;
    }
  }
}

// (D) SETTINGS
// (D1) DATABASE - CHANGE TO YOUR OWN!
define('DB_HOST', 'localhost');
define('DB_NAME', 'maindb');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'pi');
define('DB_PASSWORD', '^LkJMb');

// (D2) ERROR REPORTING
error_reporting(E_ALL & ~E_NOTICE);

// (D3) PATH
define('PATH_LIB', __DIR__ . DIRECTORY_SEPARATOR);

// (D4) CART SETTINGS
define('CART_MAX', 99);

// (E) START!
session_start();
if (!is_array($_SESSION['cart'])) { $_SESSION['cart'] = []; }
$_CC = new CartCore();
