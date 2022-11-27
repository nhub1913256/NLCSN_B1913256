<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

if (empty($_GET['id'])) {
    header('location:./admin-order.php');
}

// Get data current order
$data = $orderCollection->aggregate([['$match' => ['_id' => new ObjectId($_GET['id']), 'user_id' => getUserCurrent()['id']]], [
    '$lookup' =>
    [
        'from' => "order_detail",
        'localField' => "_id",
        'foreignField' => "order_id",
        'as' => "data_order_detail"
    ],

]])->toArray();

// Check exist order 
if (empty($data)) {
    header('location:./my-order.php');
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
    <link rel="stylesheet" href="./libs/public/style/admin.css?<?= time() ?>">
    <style>
        .text-success {
            color: rgb(0, 255, 0);
        }

        .text-danger {
            color: rgb(255, 1, 1);
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
                    <div class="row content-box">
                        <div class="content-block-title">
                            <div class="content-text">
                                <p>Cập nhật đơn hàng</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row box-content-dashboard" style="width: 90%">
                                <table>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th></th>
                                    </tr>
                                    <?php foreach ($data[0]->data_order_detail as $orderDetail) : ?>
                                        <?php
                                        $dataProduct = $productCollection->findOne(['_id' => new ObjectId($orderDetail->product_id)]);
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="./product.php?id=<?= !empty($dataProduct->_id) ? $dataProduct->_id : '' ?>">
                                                    <?= !empty($dataProduct->name) ? $dataProduct->name : 'Sản phẩm không tồn tại hoặc đã bị xóa' ?>
                                                </a>
                                            </td>
                                            <td><?= !empty($orderDetail->price_b) ? number_format($orderDetail->price_b) : '' ?></td>
                                            <td><?= !empty($orderDetail->quantity) ? $orderDetail->quantity : '' ?></td>
                                            <td><img src="<?= !empty($dataProduct->image) ? $dataProduct->image : '' ?>" alt=""></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($data[0]->data_order_detail) == 0) : ?>
                                <br>
                                <center>Sản phẩm trống !!!</center>
                            <?php endif; ?>
                            <br>
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <tr>
                                            <td><label for="">Tên người nhận:</label></td>
                                            <td>
                                                <input type="text" class="form-control" value="<?= !empty($data[0]->info_user['full_name']) ? $data[0]->info_user['full_name'] : '' ?>" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td>
                                                <input type="text" class="form-control" value="<?= !empty($data[0]->info_user['email']) ? $data[0]->info_user['email'] : '' ?>" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Số điện thoại:</label></td>
                                            <td>
                                                <input type="text" class="form-control" value="<?= !empty($data[0]->info_user['phone']) ? $data[0]->info_user['phone'] : '' ?>" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Địa chỉ:</label></td>
                                            <td>
                                                <input type="text" class="form-control" value="<?= !empty($data[0]->info_user['address']) ? $data[0]->info_user['address'] : '' ?>" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ghi chú:</label></td>
                                            <td>
                                                <textarea class="form-control" readonly><?= !empty($data[0]->info_user['note']) ? $data[0]->info_user['note'] : '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Trạng thái đơn hàng:</label></td>
                                            <td>
                                                <select name="status" class="form-control" style="width: 99%">
                                                    <option <?= $data[0]->status == ORDER_PHASE_1 ? 'selected' : '' ?> value="<?= ORDER_PHASE_1 ?>">Chờ xác nhận</option>
                                                    <option <?= $data[0]->status == ORDER_PHASE_2 ? 'selected' : '' ?> value="<?= ORDER_PHASE_2 ?>">Đã xác nhận</option>
                                                    <option <?= $data[0]->status == ORDER_PHASE_3 ? 'selected' : '' ?> value="<?= ORDER_PHASE_3 ?>">Đang giao hàng</option>
                                                    <option <?= $data[0]->status == ORDER_PHASE_4 ? 'selected' : '' ?> value="<?= ORDER_PHASE_4 ?>">Giao hàng thành công</option>
                                                    <option <?= $data[0]->status == ORDER_PHASE_5 ? 'selected' : '' ?> value="<?= ORDER_PHASE_5 ?>">Hoàn hàng</option>
                                                </select>
                                                <?php if (!empty($errorValidation['status'])) : ?><span class="text-danger"><?= $errorValidation['status'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="btn-form btn-login">
                                        <a href="./my-order.php" class="btn">Trở lại</a>
                                    </div>
                                </form>
                                <?php include "./libs/layout/notice.php" ?>
                            </div>
                        </div>
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