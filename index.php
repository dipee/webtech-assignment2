<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User CRUD API Documentation</title>
    <!-- import css file -->
    <link rel="stylesheet" href="static/css/style.css">
    
</head>
<body>
    <h1>Simple E-Commerce CRUD API Documentation</h1>
    <p>This API allows you to perform CRUD operations (Create, Read, Update, Delete) data.</p>

    <h1>APIS</h1> 

<h2>User</h2>

<h3>{base_url}/api/user.php</h3>

<ul>
    <li>GET (Get all users)</li>

    <li>POST (Create new user)</li>
    <li>PATCH (PARAM-id) (Update user)</li>
    <li>DELTE (PARAM-id) (delete user)</li>
</ul>

<h2>Product</h2>

<h3>{base_url}/api/product.php</h3>

<ul>
    <li>GET (Get all products)</li>
    
    <li>POST (Create new product)</li>
    <li>PATCH (PARAM-id) (Update product)</li>
    <li>DELTE (PARAM-id) (delete product)</li>
</ul>

<h2>Cart</h2>

<h3>{base_url}/api/cart.php</h3>

<ul>
    <li>GET (PARAM - user_id) (Get all products for user)</li>
    
    <li>POST (Create new cart entry)</li>
    <li>PATCH (PARAM-user_id, product_id) (Update cart)</li>
    <li>DELTE (PARAM-user_id, product_id) (delete cart entry)</li>
</ul>

<h2>Comment</h2>

<h3>{base_url}/api/comment.php</h3>

<ul>
    <li>GET (PARAM - user_id) (Get all comments)</li>
    
    <li>POST (Create new comment)</li>
    <li>PATCH (PARAM-id) (Update comment)</li>
    <li>DELTE (PARAM-id) (delete comment)</li>
</ul>

<h2>Product Comment</h2>

<h3>{base_url}/api/product_comments.php</h3>

<ul>
    <li>GET (PARAM - product_id) (Get all product comments)</li>

</ul>

<h2>Order</h2>

<h3>{base_url}/api/order.php</h3>

<ul>
    <li>GET (PARAM - user_id) (Get all orders of user)</li>
    
    <li>POST (Create new order)</li>
</ul>


</body>
</html>
