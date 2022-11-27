<!-- ACTION  -->
<?php

use MongoDB\BSON\ObjectId;

session_start();

include "./app/helper.php";
include "./app/connect.php";

// Select collection category
$categoryCollection = $db->category;

// Select collection product
$productCollection = $db->product;

// Get data product
$data = $categoryCollection->aggregate([[
    '$lookup' =>
    [
        'from' => "product",
        'localField' => "_id",
        'foreignField' => "category_id",
        'as' => "data_product"
    ],

]])->toArray();
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

        section {
            height: auto;
            max-height: 310px;
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
                                <p>LIÊN HỆ</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 contact-info">
                                    <p><i class="fa-solid fa-location-dot"></i> 132, Tòa Nhà ABC, Hưng Lợi, Ninh Kiều, TP. Cần Thơ</p>
                                    <p><i class="fa-solid fa-phone-volume"></i> Hotline: 0123456789</p>
                                    <br>
                                    <span><i>Nếu bạn có góp ý, vui lòng liên hệ bằng thư điện tử hoặc sử dụng biểu mẫu bên
                                            dưới. Xin cảm ơn!</i></span>
                                </div>
                                <div class="col-6">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15715.9931113177!2d105.75366486642802!3d10.016999916135262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a08834267f2d19%3A0xb08ca83b6ace2a75!2zSMawbmcgTOG7o2ksIE5pbmggS2nhu4F1LCBD4bqnbiBUaMahLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1666420822542!5m2!1svi!2s" width="300" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="content-block-title">
                            <div class="content-text">
                                <p>Góp Ý</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="">
                                    <table>
                                        <tr>
                                            <td><label for="">Họ Tên:</label></td>
                                            <td><input type="text" class="form-control" placeholder="Như Nguyễn"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td><input type="email" class="form-control" placeholder="nchnhu@gmail.com">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><textarea name="" id="" cols="30" rows="5" class="form-control" placeholder="Nội dung cần góp ý"></textarea></td>
                                        </tr>
                                    </table>

                                    <div class="btn-form btn-login">
                                        <button class="btn">Gửi Góp Ý</button>
                                    </div>
                                </form>
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