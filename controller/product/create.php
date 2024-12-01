<?php   
include "../../database/Config.php";  

header("Content-Type: application/json");  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods:POST");   

$method = $_SERVER['REQUEST_METHOD'];  

if ($method === 'POST') {  


    $data = json_decode(file_get_contents("php://input"),true);

    $name = $data['name'];
    $price = $data['price'];
    $qty   = $data['quantity'];
    

    //create rules validation with request
    $errors = [];  
    
    if (empty($name)) {  
        $errors['name'] = "name is required.";  
    }  
    
    if (!isset($price) || $price <= 0) {  
        $errors['price'] = "price must be a positive number.";  
    }  

    
    if (!isset($qty) ||  $qty < 0 || intval($qty) != $qty) {  
        $errors['quantity'] = "quantity must be a non-negative integer.";  
    }  

      
    if (!empty($errors)) {  
        http_response_code(400);  
        echo json_encode([  
            "status" => 400,  
            "message" => "Validation failed.",  
            "errors" => $errors  
        ]);  
        exit;  
    }


    //convert name to 
    $name = mysqli_real_escape_string($conn, $name);


    //store to database
    $sql = "INSERT INTO `products`(`name`,`price`,`quantity`) VALUES('$name','$price','$qty')";
    $result = mysqli_query($conn,$sql);
    
    //current insert with id
    $last_id = mysqli_insert_id($conn);

    //select from db
    $sql = "SELECT * FROM `products` WHERE `id` = $last_id ";
    $result = mysqli_query($conn,$sql);
    $product = mysqli_fetch_assoc($result);


    http_response_code(201); 
    echo json_encode([  
        "status" => 201, 
        "product" => $product,
        "message" => "Resource created successfully."  
    ]); 



}else {  
    
    http_response_code(405); // Method Not Allowed  
    echo json_encode([  
        "status" => 405,  
        "message" => "Method Not Allowed."  
    ]);  
}  
?>