<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionStaff();

// Get data product
$data = $productCollection->aggregate([['$sort' => ['created_at' => -1]], [
    '$lookup' =>
    [
        'from' => "category",
        'localField' => "category_id",
        'foreignField' => "_id",
        'as' => "data_category"
    ],

], [
    '$lookup' =>
    [
        'from' => "agent",
        'localField' => "agent_id",
        'foreignField' => "_id",
        'as' => "data_agent"
    ],

], ['$skip' => getCurrentPage()], ['$limit' => 5]])->toArray();

if (!empty($_GET['id']) && !empty($_GET['delete'])) {
    $dataCategory = $categoryCollection->findOne(['_id' => new ObjectId($_GET['id'])]);
    if (!empty($dataCategory) && !empty(getUserCurrent()['role']) && getUserCurrent()['role'] != CLIENT_WEB) {
        $categoryCollection->deleteOne(['_id' => new ObjectId($_GET['id'])]);

        $messageSuccess = 'Danh mục thuốc đã được xóa !!!';
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
                <div class="content-main content-box">
                    <div class="content-block-title">
                        <div class="content-text">
                            <p>Danh sách thuốc</p>
                        </div>
                    </div>
                    <div class="row box-content-dashboard">
                        <div class="btn-form">
                            <a href="./admin-product-add.php" class="btn">Thêm thuốc mới</a>
                        </div>
                        <br><br><br>
                        <table>
                            <tr>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Hình ảnh</th>
                                <th>Loại</th>
                                <th>Nhà cung cấp</th>
                                <th></th>
                            </tr>
                            <?php foreach ($data as $product) : ?>
                                <tr>
                                    <td><?= !empty($product->name) ? $product->name : '' ?></td>
                                    <td><?= !empty($product->price_b) ? number_format($product->price_b) : '' ?></td>
                                    <td><?= !empty($product->quantity) ? number_format($product->quantity) : 0 ?></td>
                                    <td><img src="<?= !empty($product->image) ? $product->image : '' ?>" alt=""></td>
                                    <td><?= !empty($product->data_category[0]) && !empty($product->data_category[0]->name) ? $product->data_category[0]->name : '' ?></td>
                                    <td><?= !empty($product->data_agent[0]) && !empty($product->data_agent[0]->name) ? $product->data_agent[0]->name : '' ?></td>
                                    <td>
                                        <a href="./admin-product-edit.php?id=<?= !empty($product->_id) ? $product->_id : '' ?>" class="action-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <br>
                                        <a onclick="return confirm('Bạn có chắn chắn muốn xóa sản phẩm này ?')" href="?id=<?= !empty($product->_id) ? $product->_id : '' ?>&delete=true" class="action-btn">
                                            <i class="fa-sharp fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($data) == 0) : ?>
                        <br>
                        <center>Danh sách nhà cung cấp sản phẩm trống !!!</center>
                    <?php endif; ?>
                    <br>
                    <?php include "./libs/layout/notice.php" ?>
                    <div class="paginate">
                        <?= handlePaginationHtml(count($data)) ?>
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