<?php
require_once './functions.php';
require_once $classes_url . 'Category.php';
require_once $classes_url . 'Product.php';

$p = new Product();

if (isset($_POST)) {
    if (count($_POST) > 0) {
        if (isset($_POST['id'])) {
            $p->id = $_POST['id'];
        }
        if (isset($_POST['english_name'])) {
            $p->english_name = $_POST['english_name'];
        }
        if (isset($_POST['swahili_name'])) {
            $p->swahili_name = $_POST['swahili_name'];
        }
        if (isset($_POST['price'])) {
            $p->price = $_POST['price'];
        }
        if (isset($_POST['description'])) {
            $p->description = $_POST['description'];
        }
        if (isset($_FILES['image'])) {
            $p->getById();
            unlink(dirname(__DIR__) . "/static/img/" . $p->feedback['image']);

            $image = $_FILES['image'];
            $image_name = $image['name'];
            $tmp_name = $image['tmp_name'];
            $image_size = $image['size'];

            $image_extension = explode('.', $image_name);
            $image_extension = $image_extension[count($image_extension) - 1];

            $image_upload_path = uniqid() . "." . $image_extension;
            $p->image = $image_upload_path;
            $image_upload_path = dirname(__DIR__) . "/static/img/" . $image_upload_path;

            if (!move_uploaded_file($tmp_name, $image_upload_path)) {
                echo ("not uploaded");
                unset($p->image);
                // die();
            }
        }
        if (isset($_POST['category'])) {
            $p->category = $_POST['category'];
        }

        if ($p->update()) {
            $msg = '<div class="alert alert-success">Product updated successfully</div>';
            $p->getById();
            $product = $p->feedback;
        } else {
            $msg = '<div class="alert alert-danger">' . $p->feedback . '</div>';
        }
    }
}


$c = new Category();
$c->get();
$categories = $c->feedback;

if (isset($_GET['id'])) {
    $p->id = $_GET['id'];
    $p->get();
    $p->getById();
    $product = $p->feedback;
}

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
    <header><?php require './header.php'; ?></header>
    <main>
        <div class="edit-product">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Edit Product</h1>
                    </div>
                </div>
                <?php
                if (isset($msg)) {
                    echo $msg;
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <form action="./edit_product.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="english_name">English Name</label>
                                <input type="text" name="english_name" id="english_name" class="form-control" value="<?= $product['english_name']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="swahili_name">Swahili Name</label>
                                <input type="text" name="swahili_name" id="swahili_name" class="form-control" value="<?= $product['swahili_name']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control" value="<?= $product['price']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control"><?= $product['description']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                                <img src="../static/img/<?= $product['image'] ?>" alt="IMAGE" width="90px" style="margin-top: 10px;" id="show-image">
                            </div>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?php echo $category; ?>" <?php if ($product['category'] == $category) echo 'selected'; ?>><?php echo $category; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <input type="hidden" name="id" value="<?= $product['id']; ?>">
                                <input type="submit" value="Update" class="btn btn-primary">
                                <a href="./products.php" class="btn btn-danger">DASHBOARD</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer><?php require './footer.php'; ?></footer>
    <script src="../static/js/jquery.min.js"></script>
    <script src="../static/js/bootstrap.min.js"></script>
    <script>
        $('#image').on('change', () => {
            let file = $('#image')[0].files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#show-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
        // });
    </script>
</body>

</html>