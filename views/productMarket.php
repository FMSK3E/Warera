<?php

ob_start();
if (isset($_SESSION['userId'])) { ?>
    <table class="table table-dark" style="margin-left: 585px; margin-top: 50px; width: 750px;">
        <thead>
            <tr>
                <th scope="col">Seller</th>
                <th scope="col">Product</th>
                <th scope="col">Stock</th>
                <th scope="col">Price</th> <!-- COMMENT S'OCCUPER DES DEVISES -->
                <th scope="col">Action</th> <!-- BUY / REMOVE  -->
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($products as $product) {
            echo '<tr>
                    <td><a href=index.php?action=profile&id='.$product['id_seller'].'>'.$product['username_seller'].'</a></td>
                    <td>'.$product['product'].'</td>
                    <td>'.$product['stock'].'</td>
                    <td>'.$product['price'].'</td>';
                    if ($product['id_seller'] == $_SESSION['userId']) {
                        echo '<td>
                                <form action="index.php?action=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX&product_id='.$product['id'].'" method="POST">
                                    <button type="submit" name="product_market_remove_submit">Remove product</button>
                                </form>
                            </td>';
                    } else if ($product['country_market'] == $_SESSION['nationality']) {
                        echo '<td>
                                <form action="index.php?action=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX&product_id='.$product['id'].'" method="POST">
                                    <input type="number" name="quantity" min="1" max='.$product['stock'].'>
                                    <button type="submit" name="product_market_buy_submit">Buy product</button>
                                </form>
                            </td>';
                    } else {
                        echo '<td>You need to be in the country to buy !</td>';
                    }
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
<?php
} else {
    header("Location: index.php");
    exit();
}
$content = ob_get_clean();

require 'base.php';