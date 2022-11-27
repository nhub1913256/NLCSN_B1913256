<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionSuperAdmin();

// Get data current user
$dataUser = $userCollection->find([], ['skip' => getCurrentPage(), 'limit' => 5, 'sort' => ['created_at' => -1]])->toArray();

if (!empty($_GET['id']) && !empty($_GET['delete'])) {
    $data = $userCollection->findOne(['_id' => new ObjectId($_GET['id'])]);
    if (!empty($data) && !empty(getUserCurrent()['role']) && getUserCurrent()['role'] == SUPER_ADMIN) {
        $userCollection->deleteOne(['_id' => new ObjectId($_GET['id'])]);

        $messageSuccess = 'Người dùng đã được xóa !!!';
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
                            <p>Danh Sách Người Dùng</p>
                        </div>
                    </div>
                    <div class="row box-content-dashboard">
                        <table>
                            <tr>
                                <th>Vai trò</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Địa chỉ</th>
                                <th></th>
                            </tr>
                            <?php foreach ($dataUser as $user) : ?>
                                <tr>
                                    <td><?= !empty($user->role) && $user->role == SUPER_ADMIN ? 'Quản lý' : ($user->role == STAFF_ADMIN ? 'Nhân viên' : 'Khách hàng') ?></td>
                                    <td><?= !empty($user->full_name) ? $user->full_name : '' ?></td>
                                    <td><?= !empty($user->email) ? $user->email : '' ?></td>
                                    <td><?= !empty($user->phone) ? $user->phone : '' ?></td>
                                    <td><?= !empty($user->address) ? $user->address : '' ?></td>
                                    <td>
                                        <a href="./admin-user-edit.php?id=<?= !empty($user->_id) ? $user->_id : '' ?>" class="action-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <!-- Check cannot delete user with role SUPER_ADMIN  -->
                                        <?php if (!empty($user->role) && $user->role != SUPER_ADMIN) : ?>
                                            <a onclick="return confirm('Bạn có chắn chắn muốn xóa người dùng này ?')" href="?id=<?= !empty($user->_id) ? $user->_id : '' ?>&delete=true" class="action-btn">
                                                <i class="fa-sharp fa-solid fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <?php if (count($dataUser) == 0) : ?>
                        <br>
                        <center>Danh sách nhà cung cấp sản phẩm trống !!!</center>
                    <?php endif; ?>
                    <br>
                    <?php include "./libs/layout/notice.php" ?>
                    <div class="paginate">
                        <?= handlePaginationHtml(count($dataUser)) ?>
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