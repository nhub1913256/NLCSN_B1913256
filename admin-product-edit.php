<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Check login
checkUserLogin();

checkPermissionStaff();

$dataCategory = $categoryCollection->find([])->toArray();
$dataAgent = $agentCollection->find([])->toArray();

if (empty($_GET['id'])) {
    header('location:./admin-product.php');
}

$productCollection = $db->product;
// Get data current product
$data = $productCollection->aggregate([['$match' => ['_id' => new ObjectId($_GET['id'])]], [
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

]])->toArray();

// Check exist agent 
if (empty($data)) {
    header('location:./admin-category.php');
}

if (isset($_POST["submit"])) {
    // validation data 
    $errorValidation = [];
    if (empty($_POST['name'])) {
        $errorValidation['name'] = "Tên sản phẩm không được để trống";
    }

    if (empty($_POST['price_n'])) {
        $errorValidation['price_n'] = "Giá nhập không được để trống";
    } elseif (!empty($_POST['price_n']) && (!is_numeric($_POST['price_n']) || $_POST['price_n'] < 0)) {
        $errorValidation['price_n'] = "Giá nhập không hợp lệ";
    }

    if (empty($_POST['price_b'])) {
        $errorValidation['price_b'] = "Giá bán không được để trống";
    } elseif (!empty($_POST['price_b']) && (!is_numeric($_POST['price_b']) || $_POST['price_b'] < 0)) {
        $errorValidation['price_b'] = "Giá bán không hợp lệ";
    }

    if (!isset($_POST['quantity'])) {
        $errorValidation['quantity'] = "Số lượng không được để trống";
    } elseif (isset($_POST['quantity']) && (!is_numeric($_POST['quantity']) || $_POST['quantity'] < 0 || $_POST['quantity'] > 1000)) {
        $errorValidation['quantity'] = "Số lượng không hợp lệ";
    }

    if (empty($_POST['date'])) {
        $errorValidation['date'] = "Ngày nhập không được để trống";
    }

    if (empty($_POST['category_id'])) {
        $errorValidation['category_id'] = "Phân loại không được để trống";
    }

    if (empty($_POST['agent_id'])) {
        $errorValidation['agent_id'] = "Nhà cung cấp không được để trống";
    }

    if (empty($_POST['description'])) {
        $errorValidation['description'] = "Mô tả sản phẩm không được để trống";
    } elseif (!empty($_POST['description']) && strlen($_POST['description']) > 1000) {
        $errorValidation['description'] = "Mô tả sản phẩm quá dài";
    }

    // Check validate
    if (count($errorValidation) == 0) {
        // Data update
        $dataUpdate = [
            'name'    => $_POST['name'],
            'price_n'    => $_POST['price_n'],
            'price_b'    => $_POST['price_b'],
            'date'    => $_POST['date'],
            'description'  => $_POST['description'],
            'agent_id'  => new ObjectId($_POST['agent_id']),
            'category_id'  => new ObjectId($_POST['category_id']),
            'quantity'    => (int)($data[0]->quantity + $_POST['quantity']),
        ];

        // CHeck data image post
        if ($_FILES['image']['size'] > 0) {
            $pathImage = handleMoveUploadImage($_FILES['image'], 'product/');
            $dataUpdate["image"] = $pathImage;
            removeImage($data[0]->image);
        }

        // Update data
        $updateResult = $productCollection->updateOne(["_id" => new ObjectId($_POST['id'])], [
            '$set' => $dataUpdate
        ]);

        if ($_POST['quantity'] > 0) {
            $productDetailCollection->insertOne([
                'product_id' => new ObjectId($_POST['id']),
                'quantity'    => (int)$_POST['quantity'],
                'price_n'    => $_POST['price_n'],
                'price_b'    => $_POST['price_b'],
                'created_at' => date('m/d/Y'),
                'updated_at' => date('d/m/Y H:i'),
            ]);
        }
        
        $messageSuccess = 'Cập nhật thông tin thành công !!!';
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
                                <p>Cập nhật thuốc/thiết bị mới</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <table>
                                        <input type="hidden" name="id" value="<?= !empty($data[0]->_id) ? $data[0]->_id : '' ?>">
                                        <tr>
                                            <td><label for="">Tên sản phẩm:</label></td>
                                            <td>
                                                <input type="text" name="name" class="form-control" placeholder="Bảo vệ tim mạch" value="<?= !empty($data[0]->name) ? $data[0]->name : '' ?>">
                                                <?php if (!empty($errorValidation['name'])) : ?><span class="text-danger"><?= $errorValidation['name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Giá nhập:</label></td>
                                            <td>
                                                <input type="number" name="price_n" class="form-control" min="0" value="<?= !empty($data[0]->price_n) ? $data[0]->price_n : '' ?>" placeholder="VNĐ">
                                                <?php if (!empty($errorValidation['price_n'])) : ?><span class="text-danger"><?= $errorValidation['price_n'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Giá bán:</label></td>
                                            <td>
                                                <input type="number" name="price_b" class="form-control" min="0" value="<?= !empty($data[0]->price_b) ? $data[0]->price_b : '' ?>" placeholder="VNĐ">
                                                <?php if (!empty($errorValidation['price_b'])) : ?><span class="text-danger"><?= $errorValidation['price_b'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Số lượng:</label></td>
                                            <td>
                                                <?= !empty($data[0]->quantity) ? $data[0]->quantity : 0 ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Nhập thêm:</label></td>
                                            <td>
                                                <input type="number" name="quantity" min="0" max="1000" class="form-control" placeholder="Từ 0 - 1000" value="<?= oldValue('quantity') ?>">
                                                <?php if (!empty($errorValidation['quantity'])) : ?><span class="text-danger"><?= $errorValidation['quantity'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Hình ảnh:</label></td>
                                            <td>
                                                <input type="file" name="image" class="form-control">
                                                <img width="150px" src="<?= !empty($data[0]->image) ? $data[0]->image : '' ?>" alt="">
                                                <?php if (!empty($errorValidation['image'])) : ?><span class="text-danger"><?= $errorValidation['image'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ngày nhập:</label></td>
                                            <td>
                                                <input type="date" name="date" class="form-control" min="0" value="<?= !empty($data[0]->date) ? $data[0]->date : '' ?>">
                                                <?php if (!empty($errorValidation['date'])) : ?><span class="text-danger"><?= $errorValidation['date'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Phân loại:</label></td>
                                            <td>
                                                <select name="category_id" class="form-control" style="width: 99%">
                                                    <?php foreach ($dataCategory as $category) : ?>
                                                        <option value="<?= !empty($category->_id) ? $category->_id : '' ?>" <?= !empty($data[0]->data_category[0]->_id) && $data[0]->data_category[0]->_id == $category->_id ? 'selected' : '' ?>><?= !empty($category->name) ? $category->name : '' ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if (!empty($errorValidation['category_id'])) : ?><span class="text-danger"><?= $errorValidation['category_id'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Nhà cung cấp:</label></td>
                                            <td>
                                                <select name="agent_id" class="form-control" style="width: 99%">
                                                    <?php foreach ($dataAgent as $agent) : ?>
                                                        <option value="<?= !empty($agent->_id) ? $agent->_id : '' ?>" <?= !empty($data[0]->data_agent[0]->_id) && $data[0]->data_agent[0]->_id == $agent->_id ? 'selected' : '' ?>><?= !empty($agent->name) ? $agent->name : '' ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if (!empty($errorValidation['agent_id'])) : ?><span class="text-danger"><?= $errorValidation['agent_id'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Mô tả:</label></td>
                                            <td>
                                                <textarea cols="30" rows="4" name="description" class="form-control" placeholder="Chức năng"><?= !empty($data[0]->description) ? $data[0]->description : '' ?></textarea>
                                                <?php if (!empty($errorValidation['description'])) : ?><span class="text-danger"><?= $errorValidation['description'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Cập nhật thuốc / thiết bị</button>
                                        <a href="./admin-product.php" class="btn">Trở lại</a>
                                        <br>
                                        <br>
                                        <a href="./product.php?id=<?= !empty($data[0]->_id) ? $data[0]->_id : '' ?>" target="_blank" class="btn">Xem sản phẩm</a>
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