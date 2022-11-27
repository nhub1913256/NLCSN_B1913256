<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionStaff();

$data = $orderCollection->find([], ['skip' => getCurrentPage(), 'limit' => 5, 'sort' => ['created_at' => -1]])->toArray();

if (!empty($_GET['full_name'])) {
    $data = $orderCollection->find(['info_user.full_name' => new Regex($_GET['full_name'], 'i')])->toArray();
}

if (!empty($_GET['phone'])) {
    $data = $orderCollection->find(['info_user.phone' => new Regex($_GET['phone'], 'i')])->toArray();
}

if (!empty($_GET['full_name']) && !empty($_GET['phone'])) {
    $data = $orderCollection->find([
        'info_user.full_name' => new Regex($_GET['full_name'], 'i'),
        'info_user.phone' => new Regex($_GET['phone'], 'i')
    ])->toArray();
}

if (!empty($_GET['id']) && !empty($_GET['delete'])) {
    $dataOrder = $orderCollection->findOne(['_id' => new ObjectId($_GET['id'])]);
    if (!empty($dataOrder) && !empty(getUserCurrent()['role']) && getUserCurrent()['role'] != CLIENT_WEB) {
        $orderCollection->deleteOne(['_id' => new ObjectId($_GET['id'])]);
        $orderDetailCollection->deleteMany(['order_id' => new ObjectId($_GET['id'])]);

        $messageSuccess = 'Đơn hàng được xóa !!!';
    }
}
?>
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
                            <p>Danh sách đơn hàng</p>
                        </div>
                    </div>
                    <div class="box-content-dashboard box-search-form">
                        <form action="" method="get" class="form-search-common">
                            <div class="box-search-common">
                                <div class="form-group">
                                    <label for="">Tên khách hàng</label>
                                    <input type="text" name="full_name" placeholder="Nhập tên khách hàng" class="form-control" value="<?= $_GET['full_name'] ?? '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Số điện thoại</label>
                                    <input type="text" name="phone" placeholder="Nhập số điện thoại" class="form-control" value="<?= $_GET['phone'] ?? '' ?>">
                                </div>
                                <div class="form-button-search">
                                    <button class="btn btn-search-common" type="submit">Tìm kiếm</button>
                                    <a href="./admin-order.php" class="btn btn-search-common">Làm mới</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row box-content-dashboard">
                        <table>
                            <tr>
                                <th>Tên người nhận</th>
                                <th>Thông tin nhận</th>
                                <th>Giá đơn hàng</th>
                                <th>Ngày tạo đơn hàng</th>
                                <th>Tình trạng đơn hàng</th>
                                <th></th>
                            </tr>
                            <?php foreach ($data as $order) : ?>
                                <tr>
                                    <td><?= !empty($order->info_user) && !empty($order->info_user['full_name']) ? $order->info_user['full_name'] : '' ?></td>
                                    <td><?= ($order->info_user['phone'] ?? '') . ' - ' . ($order->info_user['email'] ?? '') . ' - ' . ($order->info_user['address'] ?? '') ?></td>
                                    <td><?= !empty($order->total_order) ? number_format($order->total_order) : '' ?></td>
                                    <td><?= !empty($order->created_at) ? $order->created_at : '' ?></td>
                                    <td><?= !empty($order->status) ? getNameStatusOrder($order->status ?? ORDER_PHASE_1) : '' ?></td>
                                    <td>
                                        <a href="./admin-order-edit.php?id=<?= !empty($order->_id) ? $order->_id : '' ?>" class="action-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <br><br>
                                        <a onclick="return confirm('Bạn có chắn chắn muốn xóa order này ?')" href="?id=<?= !empty($order->_id) ? $order->_id : '' ?>&delete=true" class="action-btn">
                                            <i class="fa-sharp fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($data) == 0) : ?>
                        <br>
                        <?php if (empty($_GET['full_name']) && empty($_GET['phone'])) : ?>
                            <center>Đơn hàng trống !!!</center>
                        <?php else : ?>
                            <center>Không có kết quả tìm kiếm phù hợp !!!</center>
                        <?php endif; ?>
                    <?php endif; ?>
                    <br>
                    <?php include "./libs/layout/notice.php" ?>
                    <div class="paginate">
                        <?php if (empty($_GET['full_name']) && empty($_GET['phone'])) : ?>
                            <?= handlePaginationHtml(count($data)) ?>
                        <?php endif; ?>
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