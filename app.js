let openShopping = document.querySelector('.shopping');
let closeShopping = document.querySelector('.closeShopping');
let list = document.querySelector('.list');
let listCard = document.querySelector('.listCard');
let body = document.querySelector('body');
let total = document.querySelector('.total');
let quantity = document.querySelector('.quantity');
let payment = document.querySelector('.payment');

let totalPriceDiv = document.getElementById('totalPrice');
let totalDiscountDiv = document.getElementById('totalDiscount');

// Cart "close" button
openShopping.addEventListener('click', ()=>{
    body.classList.add('active');
})
closeShopping.addEventListener('click', ()=>{
    body.classList.remove('active');
})

// Creates menu items
let listCarts  = [];
let menu;
let combo;
let main;
let side;
let drinks;
let discount;

fetch('get_menu.php')
    .then(response => response.json())
    .then(data => {
        menu = data; // Assign the fetched data to the menu variable
        initApp(); // Call initApp after menu data is fetched
    })
    .catch(error => {
        console.error('Error fetching menu data:', error);
    });

fetch('get_combo.php')
    .then(response => response.json())
    .then(data => {
        combo = data; // Assign the fetched data to the menu variable
        comboDiscount();
    })
    .catch(error => {
        console.error('Error fetching menu data:', error);
    });

function initApp() {
      menu.forEach((value, key) => { // Use the menu variable here
          let newDiv = document.createElement('div');
          newDiv.classList.add('item');
          newDiv.innerHTML = `
              <img src="images/${value.image}">
              <div class="title">${value.group}</div>
              <div class="title">${value.name}</div>
              <div class="price">${value.price.toLocaleString()}</div>
              <button onclick="addtoCart(${key})">Add To Cart</button>`;
          list.appendChild(newDiv);
      });
  }

  function comboDiscount() {
    combo.forEach((value, key) => { // Use the menu variable here
        main = value.main;
        side = value.side;
        drink = value.drink;
        discount = value.discount;
    });
}

function addtoCart(key) {
  if (listCarts.length == 0) {
      listCarts.push({ ...menu[key], quantity: 1 });
  } else {
      listCarts.every((item, id) => {
          if (listCarts[id]["group"] === menu[key]["group"]) {
              delete listCarts[id];
              listCarts = listCarts.filter(n => n);
              return false;
          }
          return true;
      });

      listCarts.push({ ...menu[key], quantity: 1 });
  }

  reloadCard();
}

function reloadCard() {
    listCard.innerHTML = '';
    let count = 0;
    let totalPrice = 0;
    let hasChicken = false;
    let hasPotato = false;
    let hasIcedTea = false;
    let hasSteak = false;
    let hasVegetables = false;
    let hasRootBeer = false;
  
    listCarts.forEach((value, key) => {
      totalPrice = +totalPrice + +value.price;
      count = count + value.quantity;
      if (value != null) {
        let newDiv = document.createElement('li');
        newDiv.innerHTML = `
          <div><img src="images/${value.image}" /></div>
          <div>${value.name}</div>
          <div>${value.price.toLocaleString()}</div>
          <div>
            <button onclick="changeQuantity(${key}, ${value.quantity - 1})">-</button>
            <div class="count">${value.quantity}</div>
            <button onclick="changeQuantity(${key}, ${value.quantity + 1})">+</button>
          </div>`;
        listCard.appendChild(newDiv);
  
        // Check if specific items are present
        if (value.name.includes('Chicken')) {
          hasChicken = true;
        }
        if (value.name.includes('Baked Potato') || value.name.includes('Mashed Potato')) {
          hasPotato = true;
        }
        if (value.name.includes('Iced Tea')) {
          hasIcedTea = true;
        }
        if (value.name.includes('Steak')) {
          hasSteak = true;
        }
        if (value.name.includes('Steamed Vegetables')) {
          hasVegetables = true;
        }
        if (value.name.includes('Root Beer')) {
          hasRootBeer = true;
        }
      }
    });
  
    // Apply discounts if specific item combinations are present
    if (hasChicken && hasPotato && hasIcedTea) {
      discount = totalPrice*0.1;
      totalPrice *= 0.9; // Apply 10% discount
      //window.alert("discount:" + discount);
  
      // Update the message
      let discountMessage = '10% discount is applied! Chicken-Potato-Tea Combo availed.';
  
      // Display the message
      let messageDiv = document.createElement('div');
      messageDiv.classList.add('discount-message');
      messageDiv.innerText = discountMessage;
      listCard.appendChild(messageDiv);
    } else if (hasSteak && hasVegetables && hasRootBeer) {
      discount = totalPrice*0.15;
      totalPrice *= 0.85; // Apply 15% discount
  
      // Update the message
      let discountMessage = '15% discount is applied! Steak-Veg-Beer Combo availed.';
  
      // Display the message
      let messageDiv = document.createElement('div');
      messageDiv.classList.add('discount-message');
      messageDiv.innerText = discountMessage;
      listCard.appendChild(messageDiv);
    }
  
    totalPriceDiv.setAttribute("value", totalPrice); // send value to totalPrice (hidden input) in main.php
    //window.alert("discount:" + discount);
    totalDiscountDiv.setAttribute("value", discount);
    
    total.innerText = totalPrice.toLocaleString();
    quantity.innerText = count;
}      
  
function changeQuantity(key, qty) {
  // If quantity reaches 0, delete card
  if (qty === 0) {
      delete listCarts[key];
  } else {
      listCarts[key].quantity = qty;
      listCarts[key].price = qty * menu[key].price;
  }
  reloadCard();
}

/*
function submitForm() {
var payment = $('input[payment=payment]').val();
var name = $('input[payment=payment]').val();
var formData = {payment: payment, totalPrice,name: name, date: date}
$.ajax({url: "main.php", type: 'POST', data: formData, success: function(response)
{

}
})
}*/