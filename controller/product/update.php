<?php   
include "../../database/Config.php";  

header("Content-Type: application/json");  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods:POST");   

$method = $_SERVER['REQUEST_METHOD'];  

if ($method === 'POST') { 

    $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

    //get params from request
    $id = $_GET['id'];


    //Response Not found product
    $sql = "SELECT `image` FROM `products` WHERE `id` = $id ";
    $result = mysqli_query($conn,$sql);
    $product = mysqli_fetch_assoc($result);
    
    if(empty($product)){
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "message" => "Product not found with id ".$id
        ]);
        exit;
    }

    
    $name = '';
    $price = '';
    $qty   = '';
    $imageStore = '';

    if (strpos($contentType, 'application/json') !== false) {

        // If Content-Type is JSON, decode the JSON payload
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $price = $data['price'] ?? '';
        $qty = $data['quantity'] ?? '';


    }else{

        // If Content-Type is form data
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $qty = $_POST['quantity'] ?? '';

        //If you update with new image
        if(isset($_FILES['image'])){

            $image = $_FILES['image']['name'];

            //generate new name for image
            $imageName = rand(0,999999999).'.'. pathinfo($image,PATHINFO_EXTENSION);
            
            //move directory
            $target_file = "../../public/$imageName";
            move_uploaded_file($_FILES['image']['tmp_name'],$target_file);

            $imageStore = "http://localhost:3000/public/$imageName";


            //Remove old image
            if(!empty($product['image'])){
                $imageDir = "../../public/". basename($product['image']);
                if(file_exists($imageDir)){
                    unlink($imageDir);
                }

            }


        }else{
            $imageStore = $product['image'];
        }

    }

    
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

    //Validation end

    //convert name to  
    $name = mysqli_real_escape_string($conn, $name);


    
    //store to database
    $sql = "UPDATE `products` SET 
           `name`='$name',
           `price`='$price',
           `quantity`='$qty',
           `image`='$imageStore'
            WHERE `id` = $id ";

    $result = mysqli_query($conn,$sql);
    
    

    //Respone Product updated successfully to  the client
    $sql = "SELECT * FROM `products` WHERE `id` = $id ";
    $result = mysqli_query($conn,$sql);

    $product = mysqli_fetch_assoc($result);

    //add timestamp to response
    $product['updated_at'] = date('Y-m-d H:i:s', strtotime($product['updated_at']));


    http_response_code(201); 
    echo json_encode([  
        "status" => 201, 
        "product" => $product,
        "message" => "Product updated successfully."  
    ]); 



}else {  
    
    http_response_code(405); // Method Not Allowed  
    echo json_encode([  
        "status" => 405,  
        "message" => "Method Not Allowed."  
    ]);  
}  
?>