<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Get data product
$data = $categoryCollection->aggregate([[
    '$lookup' =>
    [
        'from' => "product",
        'localField' => "_id",
        'foreignField' => "category_id",
        'as' => "data_product"
    ],

]])->toArray();
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
                        <h3 class="title-product-block"><?= PARENT_TPCN["name"] ?></h3>
                    </center>

                    <?php foreach ($data as $category) :
                        if (!empty($category->parent_id) && $category->parent_id == PARENT_TPCN["id"]) :
                    ?>
                            <div class="row" id="block-<?= !empty($category->_id) ? $category->_id : '' ?>">
                                <section>
                                    <div class="section-box-product">
                                        <div class="section-title">
                                            <h5><?= !empty($category->name) ? $category->name : '' ?></h5>
                                        </div>
                                        <?php if (count($category->data_product) > 0) : ?>
                                            <div class="section-products">
                                                <div id="slide-product">
                                                    <?php if (count($category->data_product) > 3) : ?>
                                                        <div class="button-slide">
                                                            <button class="btn-slide btn-slide-left" data-id="product-1"><i class="fa-solid fa-chevron-left"></i></button>
                                                            <button class="btn-slide btn-slide-right" data-id="product-1"><i class="fa-solid fa-chevron-right"></i></button>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="box-slide">
                                                        <div class="slider-products slider-products-first">
                                                            <div class="box-overflow">

                                                                <?php foreach ($category->data_product as $product) : ?>
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
                    <?php endif;
                    endforeach; ?>

                    <center>
                        <h3 class="title-product-block"><?= PARENT_THUOC["name"] ?></h3>
                    </center>

                    <?php foreach ($data as $category) :
                        if (!empty($category->parent_id) && $category->parent_id == PARENT_THUOC["id"]) :
                    ?>
                            <div class="row" id="block-<?= !empty($category->_id) ? $category->_id : '' ?>">
                                <section>
                                    <div class="section-box-product">
                                        <div class="section-title">
                                            <h5><?= !empty($category->name) ? $category->name : '' ?></h5>
                                        </div>
                                        <?php if (count($category->data_product) > 0) : ?>
                                            <div class="section-products">
                                                <div id="slide-product">
                                                    <?php if (count($category->data_product) > 3) : ?>
                                                        <div class="button-slide">
                                                            <button class="btn-slide btn-slide-left" data-id="product-1"><i class="fa-solid fa-chevron-left"></i></button>
                                                            <button class="btn-slide btn-slide-right" data-id="product-1"><i class="fa-solid fa-chevron-right"></i></button>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="box-slide">
                                                        <div class="slider-products slider-products-first">
                                                            <div class="box-overflow">

                                                                <?php foreach ($category->data_product as $product) : ?>
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
                    <?php endif;
                    endforeach; ?>
                    <center>
                        <h3 class="title-product-block"><?= PARENT_DEVICE["name"] ?></h3>
                    </center>
                    <?php foreach ($data as $category) :
                        if (!empty($category->parent_id) && $category->parent_id == PARENT_DEVICE["id"]) :
                    ?>
                            <div class="row" id="block-<?= !empty($category->_id) ? $category->_id : '' ?>">
                                <section>
                                    <div class="section-box-product">
                                        <div class="section-title">
                                            <h5><?= !empty($category->name) ? $category->name : '' ?></h5>
                                        </div>
                                        <?php if (count($category->data_product) > 0) : ?>
                                            <div class="section-products">
                                                <div id="slide-product">
                                                    <?php if (count($category->data_product) > 3) : ?>
                                                        <div class="button-slide">
                                                            <button class="btn-slide btn-slide-left" data-id="product-1"><i class="fa-solid fa-chevron-left"></i></button>
                                                            <button class="btn-slide btn-slide-right" data-id="product-1"><i class="fa-solid fa-chevron-right"></i></button>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="box-slide">
                                                        <div class="slider-products slider-products-first">
                                                            <div class="box-overflow">

                                                                <?php foreach ($category->data_product as $product) : ?>
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
                    <?php endif;
                    endforeach; ?>
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
    <script>
        <?php foreach ($data as $category) : ?>
            var numItems<?= $category->_id ?> = $('#block-<?= $category->_id ?> #slide-product .slider-products-first .product-box').length
            var countSlide<?= $category->_id ?> = 0
            var moveSlide<?= $category->_id ?> = 230
            $("#block-<?= $category->_id ?> #slide-product .btn-slide-right").click(function() {
                countSlide<?= $category->_id ?>++
                if (countSlide<?= $category->_id ?> <= (numItems<?= $category->_id ?> - 3)) {
                    $("#block-<?= $category->_id ?> #slide-product .slider-products-first").css('left', '-' + countSlide<?= $category->_id ?> * moveSlide<?= $category->_id ?> + 'px')
                }

                if (countSlide<?= $category->_id ?> >= (numItems<?= $category->_id ?> - 3)) {
                    countSlide<?= $category->_id ?> = (numItems<?= $category->_id ?> - 3)
                }
            })

            $("#block-<?= $category->_id ?> #slide-product .btn-slide-left").click(function() {
                countSlide<?= $category->_id ?>--
                if (countSlide<?= $category->_id ?> >= 0) {
                    $("#block-<?= $category->_id ?> #slide-product .slider-products-first").css('left', '-' + countSlide<?= $category->_id ?> * moveSlide<?= $category->_id ?> + 'px')
                } else {
                    countSlide<?= $category->_id ?> = 0
                }
            })
        <?php endforeach; ?>
    </script>
</body>

</html>