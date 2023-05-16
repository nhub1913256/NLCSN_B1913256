<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

if (empty($_GET['id'])) {
    header('location:./products.php');
}

// Get data current product
$data = $productCollection->findOne(['_id' => new ObjectId($_GET['id'])]);

// Check exist product 
if (empty($data)) {
    header('location:./products.php');
}

// Get data product relationship
$dataRelationship = null;
if ($data->category_id) {
    $dataRelationship = $productCollection->find(['category_id' => new ObjectId($data->category_id)], ['limit' => 10])->toArray();
    $countRelationship = $productCollection->count(['category_id' => 'true']);
}

// Handle add product to cart
if (isset($_POST['add_to_cart'])) {
    // Validation field
    $errorValidation = [];
    if (empty($_POST['quantity'])) {
        $errorValidation['quantity'] = 'Vui lòng chọn số lượng sản phẩm trước khi thêm vào giỏ hàng';
    } elseif (!empty($_POST['quantity']) && ($_POST['quantity'] < 0 || $_POST['quantity'] > $data->quantity ?? 0)) {
        $errorValidation['quantity'] = 'Số lượng sản phẩm không hợp lệ';
    }

    if (empty($_POST['id']) || (!empty($_POST['id']) && (new ObjectId($_GET['id']) != $data->_id))) {
        $errorValidation['id'] = 'Sản phẩm không hợp lệ';
    }

    // Handle
    if (count($errorValidation) == 0) {
        handleAddToCart($data);

        $messageSuccess = 'Thêm vào giỏ hàng thành công !!!';
    }
}
?>
<!-- END ACTION  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhà thuốc Thịnh Vượng</title>
    <link rel="icon" href="./libs/public/img/Trangchu/logo.jpg" type="image/gif">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./libs/public/style/style.css?<?= time() ?>">
    <link rel="stylesheet" href="./libs/public/style/index.css?<?= time() ?>">
    <link rel="stylesheet" href="./libs/public/style/product.css?<?= time() ?>">
    <style>
        .text-success {
            color: rgb(0, 255, 0);
        }

        .text-danger {
            color: rgb(255, 1, 1);
        }

        section {
            height: auto;
        }

        .row section {
            margin-top: 0px;
        }

        .section-box-product {
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- HEADER -->
        <?php include "./libs/layout/header.php" ?>
        <!-- END HEADER -->

        <!-- NAV -->
        <?php include "./libs/layout/nav.php" ?>
        <!-- END NAV -->

        <!-- CONTENT  -->
        <div class="row">
            <!-- SIDE BAR  -->
            <div class="col-4 content-left">
                <?php include "./libs/layout/side_bar.php" ?>
            </div>
            <!-- END SIDE BAR  -->

            <!-- MAIN CONTENT  -->
            <div class="col-8">
                <div class="content-main">
                    <div class="row">
                        <section>
                            <div class="section-box-product">
                                <div class="section-title">
                                    <h3>Sản Phẩm</h3>
                                </div>

                                <div class="products-detail">
                                    <form method="POST" action="">
                                        <input type="hidden" value="<?= !empty($data->_id) ? $data->_id : '' ?>" name="id">
                                        <div class="product-detail-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="product-box-img">
                                                        <img src="<?= !empty($data->image) ? $data->image : '' ?>" alt="<?= !empty($data->name) ? $data->name : '' ?>">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="product-box-body">
                                                        <p class="product-title">
                                                            <?= !empty($data->name) ? $data->name : '' ?>
                                                        </p>
                                                        <?php if (!empty($errorValidation['id'])) : ?><span class="text-danger"><?= $errorValidation['id'] ?></span><?php endif; ?>
                                                        <p>Chức năng: <i><?= !empty($data->description) ? $data->description : '' ?></i></p>
                                                        <br>
                                                        <?php if (!empty($data->for_sick)) : ?>
                                                            <p>Sử dụng cho bệnh: <i><?= !empty($data->for_sick) ? $data->for_sick : '' ?></i></p>
                                                        <?php endif; ?>
                                                        <br>
                                                        <p>Số lượng còn: <i><?= !empty($data->quantity) ? $data->quantity : 0 ?></i></p>
                                                        <?php if (!empty($errorValidation['quantity'])) : ?><span class="text-danger"><?= $errorValidation['quantity'] ?></span><?php endif; ?>
                                                        <div class="product-price">
                                                            <?= !empty($data->price_b) ? number_format($data->price_b) : '' ?> VNĐ
                                                        </div>
                                                        <div class="product-quantity">
                                                            <label for="">Số lượng: </label>
                                                            <input type="number" name="quantity" class="form-control" min="1" max="<?= !empty($data->quantity) ? $data->quantity : 0 ?>" value="<?= !empty(oldValue('quantity')) ? oldValue('quantity') : 1 ?>">
                                                        </div>
                                                    </div>
                                                    <div class="product-box-footer">
                                                        <button class="btn btn-order" type="submit" name="add_to_cart">Thêm vào giỏ hàng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php include "./libs/layout/notice.php" ?>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="row">
                        <div class="banner-content">
                            <img src="./libs/public/img/Trangchu/banner.JPG" alt="">
                        </div>
                    </div>

                    <?php if (count($dataRelationship) - 1 > 0) : ?>
                        <div class="row" id="block-1">
                            <section style="max-height: 310px;">
                                <div class="section-box-product">
                                    <div class="section-title">
                                        <h3>Sản Phẩm Liên Quan</h3>
                                    </div>

                                    <div class="section-products">
                                        <div id="slide-product">
                                            <?php if (count($dataRelationship) - 1 > 3) : ?>
                                                <div class="button-slide">
                                                    <button class="btn-slide btn-slide-left"><i class="fa-solid fa-chevron-left"></i></button>
                                                    <button class="btn-slide btn-slide-right"><i class="fa-solid fa-chevron-right"></i></button>
                                                </div>
                                            <?php endif; ?>
                                            <div class="box-slide">
                                                <div class="slider-products slider-products-first">
                                                    <div class="box-overflow">
                                                        <?php foreach ($dataRelationship as $product) :
                                                            if (!empty($product->_id != $data->_id)) :
                                                        ?>
                                                                <div class="product-box">
                                                                    <a href="./product.php?id=<?= !empty($product->_id) ? $product->_id : '' ?>">
                                                                        <div class="product-box-img">
                                                                            <img src="<?= !empty($product->image) ? $product->image : '' ?>" alt="">
                                                                        </div>
                                                                        <div class="product-box-body">
                                                                            <p class="product-title">
                                                                                <?= !empty($product->name) ? $product->name : '' ?>
                                                                            </p>
                                                                            <div class="product-price">
                                                                                <?= !empty($product->price_b) ? number_format($product->price_b) : '' ?> VNĐ
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-box-footer">
                                                                            <button class="btn">Đặt hàng</button>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                        <?php endif;
                                                        endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END MAIN CONTENT  -->
        </div>
        <!-- END CONTENT  -->

        <!-- FOOTER  -->
        <?php include "./libs/layout/footer.php" ?>
        <!-- END FOOTER  -->

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./libs/public/script/script.js"></script>
</body>

</html>