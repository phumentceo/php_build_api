<?php   
include "../../database/Config.php";  

header("Content-Type: application/json");  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods:GET");   

$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET'){
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    $products = mysqli_fetch_all($result,MYSQLI_ASSOC);


    http_response_code(200);
    
    echo json_encode([
        'status' => 200,
        'products' => $products
    ]);

}else{
    http_response_code(405);
    echo json_encode([
        'status'  => 405,
        'message' => 'Method Not Allowed'
    ]); 
}