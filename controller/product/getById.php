<?php   
include "../../database/Config.php";  

header("Content-Type: application/json");  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods:GET");   

$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET'){

    //get id from param request
    $id = $_GET['id']?? null;

    $query = "SELECT * FROM products WHERE `id` = $id ";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);


    //check product not found with id
    if(empty($product)){
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "message" => "Product not found with id $id"
        ]);
        exit;
    }


    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'products' => $product
    ]);

}else{
    http_response_code(405);
    echo json_encode([
        'status'  => 405,
        'message' => 'Method Not Allowed'
    ]); 
}