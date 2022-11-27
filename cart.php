<!-- ACTION  -->
<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Get current user
if (!empty(getUserCurrent()) && !empty(getUserCurrent()['id'])) {
    $dataUser = $userCollection->findOne(['_id' => new ObjectId(getUserCurrent()['id'])]);
}

$dataCart = !empty($_SESSION['carts']) ? $_SESSION['carts'] : [];
$sumCart = 0;

//Handle update carts
if (isset($_POST['update_cart'])) {
    if (!empty($_POST['quantity']) && is_array($_POST['quantity']) && !empty($_SESSION['carts'])) {
        $count = 0;
        foreach ($_POST['quantity'] as $key => $quantity) {
            if (is_numeric($quantity) && $quantity == 0) {
                unset($_SESSION['carts'][$key]);
                $count++;
            } elseif (is_numeric($quantity) && $quantity <= $_SESSION['carts'][$key]['max_quantity']) {
                $_SESSION['carts'][$key]['quantity'] = $quantity;
                $count++;
            }
        }

        if ($count > 0) {
            $messageSuccess = "Cập nhật giỏ hàng thành công";
        }
    }
}

// Handle delete product in carts
if (!empty($_GET['delete_cart'])) {
    if (!empty($_SESSION['carts'][$_GET['delete_cart']])) {
        unset($_SESSION['carts'][$_GET['delete_cart']]);

        $messageSuccess = "Xóa sản phẩm ra giỏ hàng thành công";
    }
}

// Handle order
if (isset($_POST['order'])) {
    // validation data 
    $errorValidation = [];
    if (empty($_POST['full_name'])) {
        $errorValidation['full_name'] = "Họ tên không được để trống";
    }

    if (empty($_POST['address'])) {
        $errorValidation['address'] = "Địa chỉ không được để trống";
    }

    if (empty($_POST['total_order']) || (!empty($_POST['total_order']) && !is_numeric($_POST['total_order']))) {
        $errorValidation['total_order'] = "Tổng đơn không hợp lệ";
    }

    if (empty($_POST['email'])) {
        $errorValidation['email'] = "Email không được để trống";
    }

    if (empty($_POST['phone'])) {
        $errorValidation['phone'] = "Số điện thoại không được để trống";
    }

    if (!empty($_POST['note']) && strlen($_POST['note']) > 1000) {
        $errorValidation['note'] = "Ghi chú quá dài, không được quá 1000 kí tự";
    }

    if (count($errorValidation) == 0 && !empty($_SESSION['carts'])) {
        $insertResult = $orderCollection->insertOne([
            'user_id'    => new ObjectId(getUserCurrent()['id']),
            'info_user'  => [
                'full_name'  => $_POST['full_name'],
                'email'  => $_POST['email'],
                'phone'  => $_POST['phone'],
                'address'  => $_POST['address'],
                'note'  => $_POST['note'],
            ],
            'total_order' => $_POST['total_order'],
            'created_at' => date('m/d/Y H:i:s'),
            'status' => ORDER_PHASE_1,
        ]);

        $dataCartDetail = [];
        foreach ($_SESSION['carts'] as $cart) {
            $insertOrderDetailResult = $orderDetailCollection->insertOne([
                'product_id'  => new ObjectId($cart['id']),
                'quantity'  => $cart['quantity'],
                'price_b'  => $cart['price_b'],
                'price_n'  => $cart['price_n'],
                'order_id' => $insertResult->getInsertedId(),
            ]);
        }

        $messageSuccess = 'Đặt hàng thành công !!!';
        unset($_SESSION['carts']);
    } else {
        $messageError = 'Đặt hàng thất bại !!!';
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
    <link rel="stylesheet" href="./libs/public/style/product.css?<?= time() ?>">
    <link rel="stylesheet" href="./libs/public/style/cart.css?<?= time() ?>">
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
                    <div class="row">
                        <section>
                            <div class="section-box-product">
                                <div class="section-title">
                                    <h3>Giỏ Hàng</h3>
                                </div>
                                <?php if (!empty($messageSuccessCart)) : ?>
                                    <center><span class="text-success"><?= $messageSuccessCart ?></span></center>
                                    <br>
                                <?php endif; ?>
                                <?php if (count($dataCart) > 0) : ?>
                                    <div class="cart-list">
                                        <form action="" method="POST">
                                            <table>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá</th>
                                                    <th></th>
                                                </tr>
                                                <?php
                                                foreach ($dataCart as $cart) :
                                                    $sumCart += !empty($cart['price_b']) ? $cart['price_b']*$cart['quantity'] : 0;
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <a href="./product.php?id=<?= !empty($cart['id']) ? $cart['id'] : '' ?>">
                                                                <img src="<?= !empty($cart['image']) ? $cart['image'] : '' ?>" alt="">
                                                                <p class="title-product"><?= !empty($cart['name']) ? $cart['name'] : '' ?></p>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <i class="note-quantity">*Sản phẩm còn: <?= !empty($cart['max_quantity']) ? $cart['max_quantity'] : '' ?></i>
                                                            <input type="number" name="quantity[<?= !empty($cart['id']) ? $cart['id'] : '' ?>]" value="<?= !empty($cart['quantity']) ? $cart['quantity'] : '' ?>" class="form-control">
                                                        </td>
                                                        <td class="product-price"><?= !empty($cart['price_b']) ? number_format($cart['price_b']) : '' ?> VNĐ</td>
                                                        <td>
                                                            <a onclick="return confirm('Bạn có chắn chắn muốn xóa sản phẩm này ra giỏ hàng ?')" href="?delete_cart=<?= !empty($cart['id']) ? $cart['id'] : '' ?>">
                                                                <i class="fa-sharp fa-solid fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <br>
                                            <div class="product-box-footer" style="text-align: center">
                                                <button class="btn btn-order" type="submit" name="update_cart">Cập nhật giỏ hàng</button>
                                            </div>
                                        </form>
                                    </div>
                                <?php else : ?>
                                    <center><i>Giỏ hàng trống !!!</i></center>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="box-price">
                                <h5>Phương thức thanh toán:</h5>
                                <p class="box-price-total">Thanh toán khi nhận hàng</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="box-price">
                                <h5>Tổng tiền:</h5>
                                <p class="box-price-total"><?= number_format($sumCart) ?> VND</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="content-box-form-cart">
                                <h2>Thông tin nhận hàng</h2>
                                <form action="" method="POST">
                                    <input type="hidden" name="total_order" value="<?= $sumCart ?>">
                                    <table>
                                        <tr>
                                            <td><label for="">Họ Tên:</label></td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Như Nguyễn" name="full_name" value="<?= !empty($dataUser->full_name) ? $dataUser->full_name : '' ?>">
                                                <?php if (!empty($errorValidation['full_name'])) : ?><span class="text-danger"><?= $errorValidation['full_name'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Địa Chỉ:</label></td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Ninh Kiều, TP. Cần Thơ" name="address" value="<?= !empty($dataUser->address) ? $dataUser->address : '' ?>">
                                                <?php if (!empty($errorValidation['address'])) : ?><span class="text-danger"><?= $errorValidation['address'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td>
                                                <input type="email" class="form-control" placeholder="nchnhu@gmail.com" name="email" value="<?= !empty($dataUser->email) ? $dataUser->email : '' ?>">
                                                <?php if (!empty($errorValidation['email'])) : ?><span class="text-danger"><?= $errorValidation['email'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Số Điện Thoại:</label></td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="0987654321" name="phone" value="<?= !empty($dataUser->phone) ? $dataUser->phone : '' ?>">
                                                <?php if (!empty($errorValidation['phone'])) : ?><span class="text-danger"><?= $errorValidation['phone'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Ghi chú:</label></td>
                                            <td>
                                                <textarea name="note" style="height: 70px;" placeholder="Ghi chú thêm (Không bắt buộc)" class="form-control"><?= oldValue('note') ?></textarea>
                                                <?php if (!empty($errorValidation['note'])) : ?><span class="text-danger"><?= $errorValidation['note'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php if (!empty(getUserCurrent()) && !empty($dataCart)) : ?>
                                        <div class="product-box-footer">
                                            <button class="btn btn-order" type="submit" name="order">Đặt hàng</button>
                                        </div>
                                    <?php else : ?>
                                        <i class="text-danger">
                                            <?php empty(getUserCurrent()) ? "*Bạn cần phải đăng nhập trước khi đặt hàng" : "" ?>
                                        </i>
                                    <?php endif; ?>
                                </form>
                                <?php include "./libs/layout/notice.php" ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="banner-content">
                            <img src="./img/Trangchu/banner.JPG" alt="">
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