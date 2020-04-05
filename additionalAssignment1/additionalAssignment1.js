var r = require('request');
var rp = require('request-promise');
const jsdom = require("jsdom");
const { JSDOM } = jsdom;

function objToStr(product){
  console.log("\nname: " + product["name"] +
              "\n\tweight: " + product["weight"] +
              "\n\tprice: " + product["price"] +
              "\n\tunit price: " + product["unitPrice"] + "zl/100g");
}

rp
//('https://markoweswiece.pl/pol_m_Milkhouse-Candle-_Milkhouse-Candle-Duza-Swieca-306.html#60')
//('https://markoweswiece.pl/pol_m_Milkhouse-Candle-305.html')
('https://markoweswiece.pl/pol_m_Yankee-Candle-152.html#60')
    .then(function (htmlString) {

    const dom = new JSDOM(htmlString);
    var products = [];
    var allProducts = dom.window.document.querySelectorAll("h3 >.product__name");
    //alert(allProducts.length)
    for (var i = 0; i<allProducts.length; i++){
      var text = allProducts[i].innerHTML;
    
    //weight
      var regexG = /[0-9]+g/;
      var regexKG = /[0-9]+kg/;
      var foundG = text.match(regexG);
      var foundKG = text.match(regexKG);
      if (foundG != null)
        {
          var weight = foundG[0];
          var weightNum = weight.substr(0,weight.length-1);
          weightNum = parseFloat(weightNum);
        }
      if (foundKG != null)
        {
          var weight = foundKG[0];
          var weightNum = weight.substr(0,weight.length-2);
          weightNum = parseFloat(weightNum)*1000;
        }

      //name
      var n = text.search(weight);
      var productName = text.substr(0, n-1);

      //price node
      var par = allProducts[i].parentElement;
      var nxt = par.nextElementSibling;
      var chld = nxt.firstElementChild;

      //float price
      var price = chld.innerHTML;
      var regexPrice = /[0-9]+,[0-9]+/;
      var foundPrice = price.match(regexPrice);
      price = foundPrice[0];
      price = price.replace(',', '.');
      price = parseFloat(price);
      
      //unit price
      var unitPrice = (100*price/weightNum).toFixed(2);
      
      //create object and add to list
      var product = {name: productName, weight:weight, price: price, unitPrice: unitPrice};
      products.push(product);
    }
    //sort and show products
    products.sort((a,b) => a.unitPrice - b.unitPrice);
    products.forEach(objToStr);
  })
  .catch(function (err) {
      alert("Error")
  });