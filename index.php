<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Get data product
$dataFirst = $productCollection->aggregate([
    [
        '$match' => ['category_id' => new ObjectId(CATEGORY_SETTING_INDEX_1)]
    ],
    ['$sort' => ['date' => -1]], ['$limit' => 10]
])->toArray();

$dataSecond = $productCollection->aggregate([
    [
        '$match' => ['category_id' => new ObjectId(CATEGORY_SETTING_INDEX_2)]
    ],
    ['$sort' => ['date' => -1]], ['$limit' => 10]
])->toArray();

$titleDataFirst = $categoryCollection->findOne(['_id' => new ObjectId(CATEGORY_SETTING_INDEX_1)]);
$titleDataSecond = $categoryCollection->findOne(['_id' => new ObjectId(CATEGORY_SETTING_INDEX_2)]);
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
            height: auto;
            max-height: 310px;
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
                    <div class="row content-block-banner">
                        <div class="col-12">
                            <div class="content-banner-text">
                                <p>CHIẾN DỊCH "PHÒNG BỆNH HƠN CHỮA BỆNH"</p>
                            </div>
                            <div class="content-banner">
                                <img src="./libs/public/img/Trangchu/phaodai.jpg" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="block-1">
                        <section>
                            <div class="section-box-product">
                                <div class="section-title">
                                    <h3><?= !empty($titleDataFirst->name) ? $titleDataFirst->name : '' ?></h3>
                                </div>
                                <?php if (count($dataFirst) > 0) : ?>
                                    <div class="section-products">
                                        <div id="slide-product">
                                            <?php if (count($dataFirst) > DEFAULT_PRODUCT_SLIDE) : ?>
                                                <div class="button-slide">
                                                    <button class="btn-slide btn-slide-left"><i class="fa-solid fa-chevron-left"></i></button>
                                                    <button class="btn-slide btn-slide-right"><i class="fa-solid fa-chevron-right"></i></button>
                                                </div>
                                            <?php endif; ?>
                                            <div class="box-slide">
                                                <div class="slider-products slider-products-first">
                                                    <div class="box-overflow">
                                                        <?php foreach ($dataFirst as $product) : ?>
                                                            <div class="product-box">
                                                                <a href="./product.php?id=<?= !empty($product->_id) ? $product->_id : '' ?>">
                                                                    <div class="product-box-img">
                                                                        <img src="<?= !empty($product->image) ? $product->image : '' ?>" alt="<?= !empty($product->name) ? $product->name : '' ?>">
                                                                    </div>
                                                                    <div class="product-box-body">
                                                                        <p class="product-title">
                                                                            <?= !empty($product->name) ? $product->name : '' ?>
                                                                        </p>
                                                                        <div class="product-price">
                                                                            <?= !empty($product->price_b) ? $product->price_b : '' ?> VNĐ
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-box-footer">
                                                                        <button class="btn">Đặt hàng</button>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="null-product null-box-title">
                                        <p>Sản phẩm trống hoặc hết hàng !!!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>

                    <div class="row">
                        <div class="banner-content">
                            <img src="./libs/public/img/Trangchu/banner.JPG" alt="">
                        </div>
                    </div>

                    <div class="row" id="block-2">
                        <section>
                            <div class="section-box-product">
                                <div class="section-title">
                                    <h3><?= !empty($titleDataSecond->name) ? $titleDataSecond->name : '' ?></h3>
                                </div>
                                <?php if (count($dataSecond) > 0) : ?>
                                    <div class="section-products">
                                        <div id="slide-product">
                                            <?php if (count($dataSecond) > DEFAULT_PRODUCT_SLIDE) : ?>
                                                <div class="button-slide">
                                                    <button class="btn-slide btn-slide-left"><i class="fa-solid fa-chevron-left"></i></button>
                                                    <button class="btn-slide btn-slide-right"><i class="fa-solid fa-chevron-right"></i></button>
                                                </div>
                                            <?php endif; ?>
                                            <div class="box-slide">
                                                <div class="slider-products slider-products-first">
                                                    <div class="box-overflow">
                                                        <?php foreach ($dataSecond as $product) : ?>
                                                            <div class="product-box">
                                                                <a href="./product.php?id=<?= !empty($product->_id) ? $product->_id : '' ?>">
                                                                    <div class="product-box-img">
                                                                        <img src="<?= !empty($product->image) ? $product->image : '' ?>" alt="<?= !empty($product->name) ? $product->name : '' ?>">
                                                                    </div>
                                                                    <div class="product-box-body">
                                                                        <p class="product-title">
                                                                            <?= !empty($product->name) ? $product->name : '' ?>
                                                                        </p>
                                                                        <div class="product-price">
                                                                            <?= !empty($product->price_b) ? $product->price_b : '' ?> VNĐ
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-box-footer">
                                                                        <button class="btn">Đặt hàng</button>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="null-product null-box-title">
                                        <p>Sản phẩm trống hoặc hết hàng !!!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
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