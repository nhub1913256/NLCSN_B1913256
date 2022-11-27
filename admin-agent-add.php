<!-- ACTION  -->
<?php

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionStaff();

if (isset($_POST["submit"])) {
    // validation data 
    $errorValidation = [];
    if (empty($_POST['name'])) {
        $errorValidation['name'] = "Họ tên không được để trống";
    }

    if (empty($_POST['address'])) {
        $errorValidation['address'] = "Địa chỉ không được để trống";
    }

    if (empty($_POST['email'])) {
        $errorValidation['email'] = "Email không được để trống";
    } elseif (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorValidation['email'] = "Email sai định dạng";
    }

    if (empty($_POST['phone'])) {
        $errorValidation['phone'] = "Số điện thoại không được để trống";
    }

    // Check validate
    if (count($errorValidation) == 0) {
        $insertResult = $agentCollection->insertOne([
            'name'    => $_POST['name'],
            'address'  => $_POST['address'],
            'email'  => $_POST['email'],
            'phone'  => $_POST['phone'],
        ]);

        $messageSuccess = 'Thêm nhà cung cấp thành công !!!';
        $_POST = [];
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
                                <p>Thêm nhà cung cấp</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <tr>
                                            <td><label for="">Tên nhà cung cấp:</label></td>
                                            <td>
                                                <input type="text" name="name" class="form-control" placeholder="Thịnh Vượng" value="<?= oldValue('name') ?>">
                                                <?php if (!empty($errorValidation['name'])) : ?><span class="text-danger"><?= $errorValidation['name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td>
                                                <input type="email" name="email" class="form-control" placeholder="thinhvuong@gmail.com" value="<?= oldValue('email') ?>">
                                                <?php if (!empty($errorValidation['email'])) : ?><span class="text-danger"><?= $errorValidation['email'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Số điện thoại:</label></td>
                                            <td>
                                                <input type="text" name="phone" class="form-control" placeholder="0987654321" value="<?= oldValue('phone') ?>">
                                                <?php if (!empty($errorValidation['phone'])) : ?><span class="text-danger"><?= $errorValidation['phone'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Địa chỉ:</label></td>
                                            <td>
                                                <input type="text" name="address" class="form-control" placeholder="Cần Thơ" value="<?= oldValue('address') ?>">
                                                <?php if (!empty($errorValidation['address'])) : ?><span class="text-danger"><?= $errorValidation['address'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Thêm nhà cung cấp</button>
                                        <a href="./admin-agent.php" class="btn">Trở lại</a>
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