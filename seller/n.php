<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product and Stock</title>
<style>
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .product, .stock {
    padding: 20px;
    border: 1px solid #ccc;
    margin: 10px;
  }
  .product {
    background-color: lightblue;
  }
  .stock {
    background-color: lightgreen;
  }
</style>
</head>
<body>

<div class="container">
  <button onclick="showProduct()">Product</button>
  <button onclick="showStock()">Stock</button>
</div>

<div class="product">
  <h2>Product Div</h2>
  <!-- Add your product content here -->
</div>

<div class="stock">
  <h2>Stock Div</h2>
  <!-- Add your stock content here -->
</div>

<script>
  function showProduct() {
    document.querySelector('.product').style.display = 'block';
    document.querySelector('.stock').style.display = 'none';
  }

  function showStock() {
    document.querySelector('.product').style.display = 'none';
    document.querySelector('.stock').style.display = 'block';
  }
</script>

</body>
</html>
