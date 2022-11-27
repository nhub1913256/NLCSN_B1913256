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
        $errorValidation['name'] = "Tên danh mục thuốc không được để trống";
    }

    if (empty($_POST['description'])) {
        $errorValidation['description'] = "Mô tả danh mục thuốc không được để trống";
    }

    if (empty($_POST['parent_id'])) {
        $errorValidation['parent_id'] = "Vui lòng chọn phân loại danh mục thuốc";
    } elseif (!empty($_POST['parent_id']) && !in_array($_POST['parent_id'], [PARENT_TPCN["id"], PARENT_THUOC["id"], PARENT_DEVICE["id"]])) {
        $errorValidation['parent_id'] = "Phân loại danh mục thuốc không hợp lệ";
    }

    // Check validate
    if (count($errorValidation) == 0) {
        $insertResult = $categoryCollection->insertOne([
            'name'    => $_POST['name'],
            'description'  => $_POST['description'],
            'parent_id'  => $_POST['parent_id'],
        ]);

        $messageSuccess = 'Thêm danh mục thuốc thành công !!!';
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
                                <p>Thêm danh mục thuốc</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <tr>
                                            <td><label for="">Tên danh mục thuốc:</label></td>
                                            <td>
                                                <input type="text" name="name" class="form-control" placeholder="Bảo vệ tim mạch" value="<?= oldValue('name') ?>">
                                                <?php if (!empty($errorValidation['name'])) : ?><span class="text-danger"><?= $errorValidation['name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Mô tả:</label></td>
                                            <td>
                                                <input type="text" name="description" class="form-control" placeholder="Cần Thơ" value="<?= oldValue('description') ?>">
                                                <?php if (!empty($errorValidation['description'])) : ?><span class="text-danger"><?= $errorValidation['description'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Phân loại:</label></td>
                                            <td>
                                                <select name="parent_id" class="form-control" style="width: 99%">
                                                    <option value="<?= PARENT_TPCN["id"] ?>"><?= PARENT_TPCN["name"] ?></option>
                                                    <option value="<?= PARENT_THUOC["id"] ?>"><?= PARENT_THUOC["name"] ?></option>
                                                    <option value="<?= PARENT_DEVICE["id"] ?>"><?= PARENT_DEVICE["name"] ?></option>
                                                </select>
                                                <?php if (!empty($errorValidation['parent_id'])) : ?><span class="text-danger"><?= $errorValidation['parent_id'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Thêm danh mục thuốc</button>
                                        <a href="./admin-category.php" class="btn">Trở lại</a>
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