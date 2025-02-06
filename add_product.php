<?php
    require_once './functions.php';
    require_once dirname(__DIR__) . "/static/classes/Product.php";

    $product = new Product();
    $path_to_image = dirname(__DIR__) . '/static/img/';
    
        if(isset($_FILES['image']) && validate_form(['swahili_name', 'english_name', 'category', 'description', 'price'])){
            $image = $_FILES['image'];
            $image_name = $image['name'];
            $tmp_name = $image['tmp_name'];
            $image_size = $image['size'];
    
            // if($image_size > 10485760){
            //     echo "Image size cannot exceed 10MB";
            //     http_response_code(501);
            //     die();
            // }
    
            $image_extension = explode('.', $image_name);
            $image_extension = $image_extension[count($image_extension) -1];
            
            // $allowed_extensions = array('jpg', 'jpeg', 'png');
    
            // if(!in_array($image_extension, $allowed_extensions)){
            //     echo "Upload PNG or JPEG file";
            //     http_response_code(501);
            //     die();
            // }
    
            $image_upload_path = uniqid() . "." . $image_extension;
            $product -> image = $image_upload_path;
            $image_upload_path = dirname(__DIR__) . "/static/img/" . $image_upload_path;

            if(!move_uploaded_file($tmp_name, $image_upload_path)){
                echo("not uploaded");
                die();
            }

            $product -> english_name = $_POST['english_name'];
            $product -> swahili_name = $_POST['swahili_name'];
            $product -> category = $_POST['category'];
            $product -> description = $_POST['description'];
            $product -> price = $_POST['price'];
            
            if($product->add())
                header('Location: ./products.php');
            else
                echo $product->feedback;
        }
?>