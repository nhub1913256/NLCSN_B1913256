<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionStaff();

// Get data current user
$data = $categoryCollection->find([])->toArray();

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
                            <p>Danh mục thuốc</p>
                        </div>
                    </div>
                    <div class="row box-content-dashboard">
                        <div class="btn-form">
                            <a href="./admin-category-add.php" class="btn">Thêm danh mục thuốc</a>
                        </div>
                        <br><br><br>
                        <table>
                            <tr>
                                <th colspan="3">THỰC PHẨM CHỨC NĂNG</th>
                            </tr>
                            <?php
                            if (!empty($data) && count($data) > 0) :
                                foreach ($data as $category) :
                                    if (!empty($category->parent_id) && $category->parent_id == PARENT_TPCN["id"]) :
                            ?>
                                        <tr>
                                            <td><?= !empty($category->name) ? $category->name : '' ?></td>
                                            <td>Chức năng: <?= !empty($category->description) ? $category->description : '' ?></td>
                                            <td>
                                                <a href="./admin-category-edit.php?id=<?= !empty($category->_id) ? $category->_id : '' ?>" class="action-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a onclick="return confirm('Bạn có chắn chắn muốn xóa danh mục thuốc này ?')" href="?id=<?= !empty($category->_id) ? $category->_id : '' ?>&delete=true" class="action-btn">
                                                    <i class="fa-sharp fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                            <?php endif;
                                endforeach;
                            endif; ?>


                            <!-- // -->
                            <tr>
                                <th colspan="3">THUỐC</th>
                            </tr>
                            <?php
                            if (!empty($data) && count($data) > 0) :
                                foreach ($data as $category) :
                                    if (!empty($category->parent_id) && $category->parent_id == PARENT_THUOC["id"]) :
                            ?>
                                        <tr>
                                            <td><?= !empty($category->name) ? $category->name : '' ?></td>
                                            <td>Chức năng: <?= !empty($category->description) ? $category->description : '' ?></td>
                                            <td>
                                                <a href="./admin-category-edit.php?id=<?= !empty($category->_id) ? $category->_id : '' ?>" class="action-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a onclick="return confirm('Bạn có chắn chắn muốn xóa danh mục thuốc này ?')" href="?id=<?= !empty($category->_id) ? $category->_id : '' ?>&delete=true" class="action-btn">
                                                    <i class="fa-sharp fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                            <?php endif;
                                endforeach;
                            endif; ?>

                            <!-- // -->
                            <tr>
                                <th colspan="3">
                                    Thiết bị, dụng cụ y tế
                                </th>
                            </tr>
                            <?php
                            if (!empty($data) && count($data) > 0) :
                                foreach ($data as $category) :
                                    if (!empty($category->parent_id) && $category->parent_id == PARENT_DEVICE["id"]) :
                            ?>
                                        <tr>
                                            <td><?= !empty($category->name) ? $category->name : '' ?></td>
                                            <td>Chức năng: <?= !empty($category->description) ? $category->description : '' ?></td>
                                            <td>
                                                <a href="./admin-category-edit.php?id=<?= !empty($category->_id) ? $category->_id : '' ?>" class="action-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a onclick="return confirm('Bạn có chắn chắn muốn xóa danh mục thuốc này ?')" href="?id=<?= !empty($category->_id) ? $category->_id : '' ?>&delete=true" class="action-btn">
                                                    <i class="fa-sharp fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                            <?php endif;
                                endforeach;
                            endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php include "./libs/layout/notice.php" ?>
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