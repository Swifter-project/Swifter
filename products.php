<?php
require_once './session.php';
require_once $classes_url . 'Category.php';
require_once $classes_url . 'Product.php';

$c = new Category();
$c -> get();
$categories = $c -> feedback;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <title>DELI | ADMIN</title>
</head>
<body>
    <header><?php require './header.php';?></header>
    <main>
        <div class="action">
            <button id="btn-add-product" class="btn btn-primary">Add Product</button>
        </div>
        <div id="div-add-product">
            <h2>Add Product</h2>
            <form action="add_product.php" method="post" enctype="multipart/form-data" id="form-add-product">
                <div class="form-group">
                    <label for="english_name">English Name</label>
                    <input type="text" name="english_name" id="english_name" class="form-control" required placeholder="name">
                </div>
                <div class="form-group">
                    <label for="swahili_name">Swahili Name</label>
                    <input type="text" name="swahili_name" id="swahili_name" class="form-control" required placeholder="Jina">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control" required placeholder="Price in Ksh">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" maxlength="40" class="form-control" required placeholder="Do not exceed 40 characters"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option>--select category--</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="category">
            <?php
                if(count($categories) > 0){
                $prod = new Product();
                    foreach($categories as $category){
                        $prod->category = $category;
                        $prod->getByCategory();
                        echo '<div class="category">';
                        echo '<h3>' . $category . '</h3>';
                        if(count($prod->feedback) > 0){
                            require './category.php';
                        }
                        else
                            echo '<div class="alert alert-danger">No products found</div>';
                        echo '</div>';
                    }
                }
                else
                    echo '<div class="alert alert-danger">No categories found</div>';
            ?>
        </div>
    </main>
    <footer><?php require './footer.php';?></footer>
    <script src="../static/js/jquery.min.js"></script>
    <script src="../static/js/bootstrap.min.js"></script>
    <script src="../static/js/script.js"></script>
</body>
</html>