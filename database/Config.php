<?php

  
  $conn = mysqli_connect('localhost','root','','php_api',3306);

  //create products table
  $sql = "CREATE TABLE IF NOT EXISTS products (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name`  VARCHAR(255) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `quantity` INT NOT NULL,
    `description` TEXT NOT NULL,
    `image` VARCHAR(200) NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )";

  mysqli_query($conn,$sql);
  
?>