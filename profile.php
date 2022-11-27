<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// CHeck login
checkUserLogin();

// Get data current user
$dataUser = $userCollection->findOne(['_id' => $_SESSION['data_user']["id"]]);

// Logout user
if (!empty($_GET['logout'])) {
    unset($_SESSION['data_user']);
    header('location:./login.php');
}

if (isset($_POST["submit"])) {
    // validation data 
    $errorValidation = [];
    if (empty($_POST['full_name'])) {
        $errorValidation['full_name'] = "Họ tên không được để trống";
    }

    if (empty($_POST['gender'])) {
        $errorValidation['gender'] = "Bạn phải chọn mục giới tính";
    }

    if (empty($_POST['birthday'])) {
        $errorValidation['birthday'] = "Ngày sinh không được để trống";
    }

    if (empty($_POST['address'])) {
        $errorValidation['address'] = "Địa chỉ không được để trống";
    }

    if (empty($_POST['birthday'])) {
        $errorValidation['birthday'] = "Ngày sinh không được để trống";
    }

    if (empty($_POST['phone'])) {
        $errorValidation['phone'] = "Số điện thoại không được để trống";
    }

    if (!empty($_POST['password']) && (strlen($_POST['password']) < 8 || strlen($_POST['password']) > 20)) {
        $errorValidation['password'] = "Mật khẩu phải dài 8 đến 20 kí tự";
    }

    if (!empty($_POST['password']) && $_POST['password'] != $_POST['password_confirm']) {
        $errorValidation['password_confirm'] = "Mật khẩu xác nhận không đúng";
    }

    // Check validate
    if (count($errorValidation) == 0) {
        // Data update
        $dataUpdate = [
            'full_name' => $_POST['full_name'],
            'gender'  => $_POST['gender'],
            'birthday'  => $_POST['birthday'],
            'address'  => $_POST['address'],
            'phone'  => $_POST['phone'],
        ];
        
        if (!empty($_POST['password'])) {
            $dataUpdate[] = [
                'password'  => md5($_POST['password']),
            ];
        }

        // CHeck data image post
        if ($_FILES['image']['size'] > 0) {
            $pathImage = handleMoveUploadImage($_FILES['image'], 'user/');
            $dataUpdate["image"] = $pathImage;
            removeImage($dataUser->image);
        }


        // Update data
        $updateResult = $userCollection->updateOne(["_id" => new ObjectId($_SESSION['data_user']['id'])], [
            '$set' => $dataUpdate
        ]);

        $messageSuccess = 'Cập nhật thông tin thành công !!!';
    } else {
        $messageError = "Cập nhật thông tin thất bại !!!";
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
                                <p>Thông tin tài khoản</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <input type="hidden" name="id">
                                        <tr>
                                            <td><label for="">Vai trò:</label></td>
                                            <td>
                                                <?php if (!empty($dataUser->role) && $dataUser->role == SUPER_ADMIN) : ?>
                                                    Quản lý
                                                <?php elseif (!empty($dataUser->role) && $dataUser->role == STAFF_ADMIN) : ?>
                                                    Nhân viên
                                                <?php else : ?>
                                                    Khách hàng
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Họ Tên:</label></td>
                                            <td>
                                                <input type="text" name="full_name" class="form-control" placeholder="Như Nguyễn" value="<?= !empty($dataUser->full_name) ? $dataUser->full_name : '' ?>">
                                                <?php if (!empty($errorValidation['full_name'])) : ?><span class="text-danger"><?= $errorValidation['full_name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Giới tính:</label></td>
                                            <td>
                                                <label for="male">Nam</label>
                                                <input type="radio" name="gender" value="1" id="male" <?= !empty($dataUser->gender) && $dataUser->gender == 1 ? "checked" : '' ?>>
                                                <label for="female">Nữ</label>
                                                <input type="radio" name="gender" value="2" id="female" <?= !empty($dataUser->gender) && $dataUser->gender == 2 ? "checked" : '' ?>>
                                                <?php if (!empty($errorValidation['gender'])) : ?><span class="text-danger"><?= $errorValidation['gender'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ngày Sinh:</label></td>
                                            <td>
                                                <input type="date" name="birthday" class="form-control" value="<?= !empty($dataUser->birthday) ? $dataUser->birthday : '' ?>">
                                                <?php if (!empty($errorValidation['birthday'])) : ?><span class="text-danger"><?= $errorValidation['birthday'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Địa Chỉ:</label></td>
                                            <td>
                                                <input type="text" name="address" class="form-control" value="<?= !empty($dataUser->address) ? $dataUser->address : '' ?>">
                                                <?php if (!empty($errorValidation['address'])) : ?><span class="text-danger"><?= $errorValidation['address'] ?></span><?php endif; ?>
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
                                            <td><label for="">Số Điện Thoại:</label></td>
                                            <td>
                                                <input type="text" name="phone" class="form-control" placeholder="0987654321" value="<?= !empty($dataUser->phone) ? $dataUser->phone : '' ?>">
                                                <?php if (!empty($errorValidation['phone'])) : ?><span class="text-danger"><?= $errorValidation['phone'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ảnh Đại Diện:</label></td>
                                            <td>
                                                <input type="file" name="image" class="form-control">
                                                <?php if (!empty($pathImage)) : ?>
                                                    <img width="150px" src="<?= $pathImage ?>" alt="">
                                                <?php else : ?>
                                                    <img width="150px" src="<?= !empty($dataUser->image) ? $dataUser->image : '' ?>" alt="">
                                                <?php endif; ?>
                                                <?php if (!empty($errorValidation['image'])) : ?><span class="text-danger"><?= $errorValidation['image'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Mật Khẩu:</label></td>
                                            <td><input type="password" name="password" class="form-control" placeholder="Nhập 8 - 20 kí tự">
                                                <?php if (!empty($errorValidation['password'])) : ?><span class="text-danger"><?= $errorValidation['password'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Xác Nhận Mật Khẩu:</label></td>
                                            <td>
                                                <input type="password" name="password_confirm" class="form-control" placeholder="Nhập 8 - 20 kí tự">
                                                <?php if (!empty($errorValidation['password_confirm'])) : ?><span class="text-danger"><?= $errorValidation['password_confirm'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Cập nhật thông tin</button>
                                        <a href="./profile.php?logout=<?= !empty($dataUser->full_name) ? $dataUser->full_name : '' ?>" class="btn">Đăng xuất</a>
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