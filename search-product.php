<!-- ACTION  -->
<?php

use MongoDB\BSON\Regex;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Get data product search
$nameProduct = $_GET['search_name'] ?? '';
$nameProductRegex = new Regex($nameProduct, 'i');
$data = $productCollection->find(['name' => $nameProductRegex])->toArray();
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
    <style>
        .text-success {
            color: rgb(0, 255, 0);
        }

        .text-danger {
            color: rgb(255, 1, 1);
        }

        section {
            margin-top: 30px;
            height: auto;
            max-height: 310px;
        }

        .list_product {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
        }

        .list_product .product-box {
            margin-bottom: 40px;
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
                    <center>
                        <h3 class="title-product-block">Kết quả tìm kiếm: <?= $_GET['search_name'] ?? '' ?></h3>
                    </center>

                    <div class="row list_product">
                        <?php foreach ($data as $product) : ?>
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
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($data) == 0) : ?>
                        <div class="row" style="justify-content: center;">
                            <center>Không có kết quả tìm kiếm phù hợp !!!</center>
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
