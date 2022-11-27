<!-- ACTION  -->
<?php
session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check user login
if (!empty(getUserCurrent())) {
    header('location:./profile.php');
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

    if (empty($_POST['email'])) {
        $errorValidation['email'] = "Email không được để trống";
    } elseif (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorValidation['email'] = "Email sai định dạng";
    }

    if (empty($_POST['phone'])) {
        $errorValidation['phone'] = "Số điện thoại không được để trống";
    }

    if (empty($_POST['password'])) {
        $errorValidation['password'] = "Mật khẩu không được để trống";
    }

    if (empty($_POST['password'])) {
        $errorValidation['password'] = "Mật khẩu không được để trống";
    } elseif (!empty($_POST['password']) && (strlen($_POST['password']) < 8 || strlen($_POST['password']) > 20)) {
        $errorValidation['password'] = "Mật khẩu phải dài 8 đến 20 kí tự";
    }

    if (!empty($_POST['password']) && $_POST['password'] != $_POST['password_confirm']) {
        $errorValidation['password_confirm'] = "Mật khẩu xác nhận không đúng";
    }

    if ($_FILES['image']['size'] == 0) {
        $errorValidation['image'] = "Ảnh đại diện không được để trống";
    }

    if (count($errorValidation) == 0) {
        // Check exist email
        $dataUser = $userCollection->findOne(['email' => $_POST['email']]);

        if (empty($dataUser)) {
            $insertResult = $userCollection->insertOne([
                'full_name'    => $_POST['full_name'],
                'gender'  => $_POST['gender'],
                'birthday'  => $_POST['birthday'],
                'address'  => $_POST['address'],
                'email'  => $_POST['email'],
                'phone'  => $_POST['phone'],
                'image'  => handleMoveUploadImage($_FILES['image'], 'user/'),
                'password'  => md5($_POST['password']),
                'role' => CLIENT_WEB,
                'created_at' => date('m/d/Y'),
            ]);

            $messageSuccess = 'Đăng kí thành công !!!';
            $_POST = [];
        } else {
            $errorValidation['email'] = "Email đã được sử dụng vui lòng điền email khác";
        }
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
                                <p>ĐĂNG KÝ</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <tr>
                                            <td><label for="">Họ Tên:</label></td>
                                            <td>
                                                <input type="text" class="form-control" name="full_name" value="<?= oldValue("full_name") ?>" placeholder="Như Nguyễn">
                                                <?php if (!empty($errorValidation['full_name'])) : ?><span class="text-danger"><?= $errorValidation['full_name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Giới tính:</label></td>
                                            <td>
                                                <label for="male">Nam</label>
                                                <input type="radio" name="gender" value="1" id="male" checked>
                                                <label for="female">Nữ</label>
                                                <input type="radio" name="gender" value="2" id="female">
                                                <?php if (!empty($errorValidation['gender'])) : ?><span class="text-danger"><?= $errorValidation['gender'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ngày Sinh:</label></td>
                                            <td>
                                                <input type="date" name="birthday" class="form-control" value="<?= oldValue("birthday") ?>">
                                                <?php if (!empty($errorValidation['birthday'])) : ?><span class="text-danger"><?= $errorValidation['birthday'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Địa Chỉ:</label></td>
                                            <td>
                                                <input type="text" name="address" class="form-control" value="<?= oldValue("address") ?>">
                                                <?php if (!empty($errorValidation['address'])) : ?><span class="text-danger"><?= $errorValidation['address'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td>
                                                <input type="email" name="email" class="form-control" placeholder="nchnhu@gmail.com" value="<?= oldValue("email") ?>">
                                                <?php if (!empty($errorValidation['email'])) : ?><span class="text-danger"><?= $errorValidation['email'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Số Điện Thoại:</label></td>
                                            <td>
                                                <input type="text" name="phone" class="form-control" placeholder="0987654321" value="<?= oldValue("phone") ?>">
                                                <?php if (!empty($errorValidation['phone'])) : ?><span class="text-danger"><?= $errorValidation['phone'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ảnh Đại Diện:</label></td>
                                            <td>
                                                <input type="file" name="image" class="form-control" value="<?= oldValue("image") ?>">
                                                <?php if (!empty($errorValidation['image'])) : ?><span class="text-danger"><?= $errorValidation['image'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Mật Khẩu:</label></td>
                                            <td>
                                                <input type="password" name="password" class="form-control" placeholder="Nhập 8 - 20 kí tự" value="<?= oldValue("password") ?>">
                                                <?php if (!empty($errorValidation['password'])) : ?><span class="text-danger"><?= $errorValidation['password'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Xác Nhận Mật Khẩu:</label></td>
                                            <td>
                                                <input type="password" name="password_confirm" class="form-control" placeholder="Nhập 8 - 20 kí tự" value="<?= oldValue("password_confirm") ?>">
                                                <?php if (!empty($errorValidation['password_confirm'])) : ?><span class="text-danger"><?= $errorValidation['password_confirm'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="rules">
                                        <span>Thông qua việc tạo tài khoản, bạn đồng ý với <a href="">Điều khoản & Quyền riêng tư của chúng tôi.</a></span>
                                    </div>

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Đăng Kí</button>
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