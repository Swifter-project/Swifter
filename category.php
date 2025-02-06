<div>
    <table class="table table-stripped">
        <thead>
            <tr>
                <th>English Name</th>
                <th>Swahili Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Product Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prod->feedback as $product) : ?>
                <tr>
                    <td><?php echo $product['english_name']; ?></td>
                    <td><?php echo $product['swahili_name']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><img src="<?php echo $img_url . $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100" height="100"></td>
                    <td class="edit">
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>