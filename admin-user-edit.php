<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionSuperAdmin();

if (empty($_GET['id'])) {
    header('location:./admin-user.php');
}
// Get data current user
$dataUser = $userCollection->findOne(['_id' => new ObjectId($_GET['id'])]);

// Check exist user 
if (empty($dataUser)) {
    header('location:./admin-user.php');
}

if (isset($_POST["submit"])) {
    // validation data 
    $errorValidation = [];
    if (empty($_POST['role'])) {
        $errorValidation['role'] = "Vui lòng chọn vai trò";
    }
    // Validate check role in roles
    else if (!empty($_POST['role']) && !in_array($_POST['role'], [SUPER_ADMIN, CLIENT_WEB, STAFF_ADMIN])) {
        $errorValidation['role'] = "Vai trò không hợp lệ";
    }

    // Check validate
    if (count($errorValidation) == 0 && !empty(getUserCurrent()['role']) && getUserCurrent()['role'] == SUPER_ADMIN) {
        // Data update
        $dataUpdate = [
            'role' => $_POST['role'],
        ];

        // Update data
        $updateResult = $userCollection->updateOne(["_id" => new ObjectId($_POST['id'])], [
            '$set' => $dataUpdate
        ]);

        $messageSuccess = 'Cập nhật vai trò thành công !!!';
    } else {
        $messageError = "Cập nhật vai trò thất bại !!!";
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
                <div class="content-main">
                    <div class="row content-box">
                        <div class="content-block-title">
                            <div class="content-text">
                                <p>Cập nhật vai trò</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <input type="hidden" name="id" value="<?= !empty($dataUser->_id) ? $dataUser->_id : '' ?>">
                                        <tr>
                                            <td><label for="">Họ Tên:</label></td>
                                            <td>
                                                <input type="text" name="full_name" class="form-control" placeholder="Như Nguyễn" value="<?= !empty($dataUser->full_name) ? $dataUser->full_name : '' ?>" readonly>
                                                <?php if (!empty($errorValidation['full_name'])) : ?><span class="text-danger"><?= $errorValidation['full_name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td>
                                                <input type="email" name="email" class="form-control" placeholder="nchnhu@gmail.com" value="<?= !empty($dataUser->email) ? $dataUser->email : '' ?>" readonly>
                                                <?php if (!empty($errorValidation['email'])) : ?><span class="text-danger"><?= $errorValidation['email'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Vai trò:</label></td>
                                            <td>
                                                <select name="role" class="form-control" style="width: 98%">
                                                    <option value="<?= SUPER_ADMIN ?>" <?= !empty($dataUser->role) && $dataUser->role == SUPER_ADMIN ? 'selected' : '' ?>>Quản lý</option>
                                                    <option value="<?= STAFF_ADMIN ?>" <?= !empty($dataUser->role) && $dataUser->role == STAFF_ADMIN ? 'selected' : '' ?>>Nhân viên</option>
                                                    <option value="<?= CLIENT_WEB ?>" <?= !empty($dataUser->role) && $dataUser->role == CLIENT_WEB ? 'selected' : '' ?>>Người dùng</option>
                                                </select>
                                                <?php if (!empty($errorValidation['role'])) : ?><span class="text-danger"><?= $errorValidation['role'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Cập nhật vai trò</button>
                                        <a href="./admin-user.php" class="btn">Trở lại</a>
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