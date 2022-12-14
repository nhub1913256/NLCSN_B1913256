<?php

session_start();

use MongoDB\BSON\ObjectId;

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionSuperAdmin();
// List product detail buy

// Handle date search in dashboard
$dateSearch = !empty($_GET['search_date']) ? handleDateRequest($_GET['search_date']) : date('m/d/Y');

$listProductDetailToday = $productDetailCollection->find(['created_at' => $dateSearch])->toArray();

$totalPriceProductN = 0;
foreach ($listProductDetailToday as $product) {
    $totalPriceProductN += !empty($product->price_n) ? $product->price_n * $product->quantity : 0;
}

// List order
$listOrderToday = $orderCollection->find(['created_at' => [
    '$gte' => $dateSearch . ' 00:00:00',
    '$lt' => $dateSearch . ' 23:59:59'
]])->toArray();

$totalOrderToday = 0;
$listOrderIDToday = [];
foreach ($listOrderToday as $order) {
    $listOrderIDToday[] = !empty($order->_id) ? $order->_id : '';
    $totalOrderToday += !empty($order->total_order) ? $order->total_order : 0;
}

// List order detail
$listOrderIDTodayNew = [];
foreach ($listOrderIDToday as $order_id) {
    if (!empty($order_id)) {
        $listOrderIDTodayNew[] = new ObjectId($order_id);
    }
}

$listOrderDetailToday = [];
if (count($listOrderIDTodayNew) > 0) {
    $listOrderDetailToday = $orderDetailCollection->find(['order_id' => ['$in' => $listOrderIDTodayNew]])->toArray();
}

$priceProfit = 0;
$quantityProductInOrder = 0;
foreach ($listOrderDetailToday as $orderDetail) {
    $priceProfit += !empty($orderDetail->quantity) && !empty($orderDetail->price_n) ? $orderDetail->quantity * $orderDetail->price_n : 0;
    $quantityProductInOrder += !empty($orderDetail->quantity) ? $orderDetail->quantity : 0;
}

// List order detail
$dataProductDetail = $productDetailCollection->aggregate([['$match' => ['created_at' => $dateSearch]], [
    '$lookup' =>
    [
        'from' => "product",
        'localField' => "product_id",
        'foreignField' => "_id",
        'as' => "data_product"
    ]
]])->toArray();
$dataProductDetail = array_reverse($dataProductDetail, true);

$totalPriceBuyToday = 0;
$totalProductBuyToday = 0;
foreach ($dataProductDetail as $productDetail) {
    $totalProductBuyToday += !empty($productDetail->quantity) ? $productDetail->quantity : 0;
    $totalPriceBuyToday += !empty($productDetail->quantity) && !empty($productDetail->price_n) ? $productDetail->quantity * $productDetail->price_n : 0;
}

// List product almost sold out
$listProducAlmostSoldOut = $productCollection->find(['quantity' => ['$lt' => 30]])->toArray();

// List order detail today
$dataOrderDetail = [];
if (count($listOrderIDTodayNew) > 0) {
    $dataOrderDetail = $orderDetailCollection->aggregate([['$match' => ['order_id' => ['$in' => $listOrderIDTodayNew]]], [
        '$lookup' =>
        [
            'from' => "product",
            'localField' => "product_id",
            'foreignField' => "_id",
            'as' => "data_product"
        ]
    ]])->toArray();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nh?? thu???c Th???nh V?????ng</title>
    <link rel="icon" href="./libs/public/img/Trangchu/logo.jpg" type="image/gif">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./libs/public/style/style.css?<?= time() ?>">
    <link rel="stylesheet" href="./libs/public/style/index.css?<?= time() ?>">
    <link rel="stylesheet" href="./libs/public/style/admin.css?<?= time() ?>">
    <style>
        .table-dashboard-day th {
            width: 35%;
        }

        .table-dt td,
        .table-dt th {
            width: 20% !important;
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
                <div class="content-main content-box">
                    <div class="content-block-title">
                        <div class="content-text">
                            <p>Th???ng k??</p>
                        </div>
                    </div>
                    <div class="row box-content-dashboard">
                        <table>
                            <tr>
                                <th>T???ng ng?????i d??ng</th>
                                <th>T???ng s??? s???n ph???m</th>
                                <th>T???ng s??? ????n h??ng</th>
                            </tr>
                            <tr>
                                <td><?= count($userCollection->find([])->toArray()) ?></td>
                                <td><?= count($productCollection->find([])->toArray()) ?></td>
                                <td><?= count($orderCollection->find([])->toArray()) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row box-content-dashboard form-search-dashboard">
                        <div class="col-12">
                            <div class="box-search">
                                <form action="" method="get">
                                    <label for="">Th???ng k?? theo ng??y:</label>
                                    <input type="date" name="search_date" class="form-control" value="<?= $_GET['search_date'] ?? date('Y-m-d') ?>">
                                    <button class="btn btn-search-dashboard" type="submit">T??m ki???m</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row box-content-dashboard" style="margin-top: 40px;">
                        <div class="row" style="justify-content: center; margin-bottom: 10px;">
                            <center>
                                <h3>Th???ng k?? doanh thu <?= date("m/d/Y", strtotime($dateSearch)) ?></h3>
                            </center>
                        </div>
                        <table class="table-dashboard-day" style="margin-bottom: 10px;">
                            <tr>
                                <th colspan="2">T???ng ????n h??ng:</th>
                                <td colspan="4"><?= count($listOrderToday) ?> ????n h??ng</td>
                            </tr>
                            <tr>
                                <th colspan="2">T???ng s???n ph???m b??n ra:</th>
                                <td colspan="4"><?= $quantityProductInOrder ?> s???n ph???m</td>
                            </tr>

                            <tr>
                                <th colspan="2">Doanh thu:</th>
                                <td colspan="4"><?= number_format($totalOrderToday) ?> VN??</td>
                            </tr>
                            <tr>
                                <th colspan="2">Gi?? nh???p v??o:</th>
                                <td colspan="4"><?= number_format($priceProfit) ?> VN??</td>
                            </tr>
                            <tr>
                                <th colspan="2">L???i nhu???n:</th>
                                <td colspan="4"><?= number_format($totalOrderToday - $priceProfit) ?> VN??</td>
                            </tr>
                        </table>
                        <div class="row" style="justify-content: center;">
                            <a href="#detail-product-order" class="btn">Xem chi ti???t th???ng k?? doanh thu</a>
                        </div>
                    </div>
                    <div class="row box-content-dashboard" style="margin-top: 40px;">
                        <div class="row" style="justify-content: center; margin-bottom: 10px;">
                            <center>
                                <h3>S???n ph???m nh???p v??o <?= date("m/d/Y", strtotime($dateSearch)) ?></h3>
                            </center>
                        </div>
                        <table class="table-dashboard-day">
                            <tr>
                                <th colspan="3">T???ng s??? ti???n nh???p h??ng:</th>
                                <td colspan="4"><?= number_format($totalPriceBuyToday) ?> VN??</td>

                            </tr>
                            <tr>
                                <th colspan="3">S??? l?????ng nh???p:</th>
                                <td colspan="4"><?= $totalProductBuyToday ?> s???n ph???m</td>
                            </tr>
                        </table>
                        <div class="row" style="justify-content: center;">
                            <a href="#detail-product-buy" class="btn">Xem chi ti???t s???n ph???m ???????c nh???p</a>
                        </div>
                    </div>
                    <div class="row box-content-dashboard" style="margin-top: 40px;" id="detail-product-order">
                        <div class="row" style="justify-content: center; margin-bottom: 10px;">
                            <center>
                                <h3>Chi ti???t s???n ph???m b??n ???????c ng??y <?= date("m/d/Y", strtotime($dateSearch)) ?></h3>
                            </center>
                        </div>
                        <table class="table-dashboard-day table-dt" style="margin-bottom: 10px;">

                            <tr>
                                <th>S???n ph???m</th>
                                <th>S??? l?????ng</th>
                                <th>T???ng nh???p</th>
                                <th>T???ng b??n</th>
                                <th>L???i nhu???n</th>
                            </tr>
                            <?php
                            foreach ($dataOrderDetail as $productDetail) :
                                $totalBuy = !empty($productDetail->quantity) && !empty($productDetail->price_n) ? $productDetail->quantity * $productDetail->price_n : 0;
                                $totalSale = !empty($productDetail->quantity) && !empty($productDetail->price_b) ? $productDetail->quantity * $productDetail->price_b : 0;
                            ?>
                                <tr>
                                    <td>
                                        <a href="./admin-product-edit.php?id=<?= !empty($productDetail->product_id) ? $productDetail->product_id : '' ?>" target="_blank">
                                            <img src="<?= !empty($productDetail->data_product[0]->image) ? $productDetail->data_product[0]->image : '' ?>" alt="">
                                            <br>
                                            <?= !empty($productDetail->data_product[0]->name) ? $productDetail->data_product[0]->name : '' ?>
                                        </a>
                                    </td>
                                    <td><?= !empty($productDetail->quantity) ? $productDetail->quantity : 0 ?></td>
                                    <td><?= number_format($totalBuy) ?></td>
                                    <td><?= number_format($totalSale) ?></td>
                                    <td><?= number_format($totalSale - $totalBuy) ?></td>
                                </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>

                    <div class="row box-content-dashboard" style="margin-top: 40px;" id="detail-product-buy">
                        <div class="row" style="justify-content: center; margin-bottom: 10px;">
                            <center>
                                <h3>Chi ti???t s???n ph???m nh???p v??o ng??y <?= date("m/d/Y", strtotime($dateSearch)) ?></h3>
                            </center>
                        </div>
                        <table class="table-dashboard-day">
                            <tr>
                                <th>S???n ph???m</th>
                                <th>S??? l?????ng</th>
                                <th>Gi?? 1 s???n ph???m</th>
                                <th>T???ng gi?? nh???p</th>
                                <th>C???p nh???t l??c</th>
                            </tr>
                            <?php $totalPriceBuyToday = 0;
                            foreach ($dataProductDetail as $productDetail) :
                                $totalPriceBuyToday += !empty($productDetail->quantity) && !empty($productDetail->price_n) ? $productDetail->quantity * $productDetail->price_n : 0
                            ?>
                                <tr>
                                    <td>
                                        <a href="./admin-product-edit.php?id=<?= !empty($productDetail->product_id) ? $productDetail->product_id : '' ?>" target="_blank">
                                            <img src="<?= !empty($productDetail->data_product[0]->image) ? $productDetail->data_product[0]->image : '' ?>" alt="">
                                            <br>
                                            <?= !empty($productDetail->data_product[0]->name) ? $productDetail->data_product[0]->name : '' ?>
                                        </a>
                                    </td>
                                    <td><?= !empty($productDetail->quantity) ? $productDetail->quantity : 0 ?></td>
                                    <td><?= !empty($productDetail->price_n) ? number_format($productDetail->price_n) : 0 ?></td>
                                    <td><?= number_format(!empty($productDetail->quantity) && !empty($productDetail->price_n) ? $productDetail->quantity * $productDetail->price_n : 0) ?></td>
                                    <td><?= !empty($productDetail->updated_at) ? $productDetail->updated_at : '' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
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

</html>