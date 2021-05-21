// NOTE: HTML INTERFACE IS INCOMPLETE
// BOOTSTRAP, JQUERY, VUE, REACT - USE THE LIBRARY OF YOUR OWN CHOICE
var cart = {
  // (A) HELPER FUNCTION - AJAX CALL
  // opt.data : data to be sent, an object with key-value pairs
  // opt.target : (optional) ID of HTML element, put server response in here if provided
  // opt.done : (optional) function to call when AJAX load is complete
  ajax : function (opt) {
    // (A1) DATA
    var data = null;
    if (opt.data) {
      data = new FormData();
      for (var d in opt.data) { data.append(d, opt.data[d]); }
    }

    // (A2) AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', "ajax-cart.php");
    xhr.onload = function(){
      if (xhr.status!=200) {
        alert("Server responded with error code " + xhr.status + " " + xhr.statusText);
      } else {
        if (opt.target) { document.getElementById(opt.target).innerHTML = this.response; }
        if (typeof opt.done == "function") { 
          try { var res = JSON.parse(this.response); }
          catch (err) { var res = null; }
          if (res===null) { opt.done(); }
          else { opt.done((res)); }
        }
      }
    };
    xhr.send(data);
  },

  // (B) ADD ITEM TO CART
  add : function (id) {
    cart.ajax({
      data : {
        req : "add",
        product_id : id
      },
      done : function (res) {
        if (res.status == true) { 
          cart.count(); 
          alert("Item added");
        } else { alert(res.message); }
       }
    });
  },

  // (C) UPDATE ITEMS COUNT
  count : function () {
    cart.ajax({
      data : { req : "count" },
      target : "scCartCount"
    });
  },

  // (D) SHOW/HIDE CART
  toggle : function (reload) {
    // (D1) GET HTML ELEMENTS
    var pgIco = document.getElementById("scCartIcon"),
        pgPdt = document.getElementById("scProduct"),
        pgCart = document.getElementById("scCart");

    // (D2) LOAD CART
    if (reload || pgCart.classList.contains("ninja")) {
      cart.ajax({
        data : { req : "show", },
        target : "scCart",
        done : function () {
          pgIco.classList.add("active");
          pgPdt.classList.add("ninja");
          pgCart.classList.remove("ninja");
        }
      });
    } else {
      pgIco.classList.remove("active");
      pgPdt.classList.remove("ninja");
      pgCart.classList.add("ninja");
    }
  },
  
  // (E) CHANGE ITEM QUANTITY
  change : function (id) {
    var qty = document.getElementById("qty_"+id).value;
    cart.ajax({
      data : {
        req : "change",
        product_id : id,
        qty : qty
      },
      done : function (res) {
        cart.count();
        cart.toggle(1);
        alert(res.message);
      }
    });
  },
  
  // (F) REMOVE ITEM FROM CART
  remove : function (id) {
    document.getElementById("qty_"+id).value = 0;
    cart.change(id);
  },
  
  // (G) CHECKOUT
  checkout : function () {
    cart.ajax({
      data : {
        req : "checkout",
        name : document.getElementById("co_name").value,
        email : document.getElementById("co_email").value
      },
      done : function (res) {
        cart.count();
        cart.toggle(1);
        alert(res.message);
        // if (res.status) { location.href = "THANK-YOU-PAGE"; }
      }
    });
    return false;
  }
};
window.addEventListener("DOMContentLoaded", cart.count);