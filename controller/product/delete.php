<?php   
include "../../database/Config.php";  

header("Content-Type: application/json");  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods:DELETE");  


$method = $_SERVER['REQUEST_METHOD']; 

if($method === "DELETE"){
    //Delete product with params
    $id = $_GET['id'];

    // Check if product note found
    $query = "SELECT * FROM `products` WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if(empty($product)){
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "message" => "Product not found with id ".$id
        ]);
        exit;
    }


    //Remove Image from directory

    $image = $product['image'];
    //localhost:3000/public/35454534564.jpg;
    $imageDir = "../../public/".basename($image);
    if($image != ''){
        if(file_exists($imageDir)){
            unlink($imageDir);
        }
    }


    //Delete Product from db
    $query = "DELETE FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if($result){
        http_response_code(200);
        echo json_encode([
            "status" => 200,
            "message" => "Product deleted successfully with id ".$id
        ]);
        exit;
    }


}else{
    http_response_code(405);
    echo json_encode([
        "status" => 405,
        "message" => "Method not allowed"
    ]);
    exit;
}