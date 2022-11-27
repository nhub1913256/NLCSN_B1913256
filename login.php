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
    if (empty($_POST['email'])) {
        $errorValidation['email'] = "Email không được để trống";
    } elseif (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorValidation['email'] = "Email sai định dạng";
    }

    if (empty($_POST['password'])) {
        $errorValidation['password'] = "Mật khẩu không được để trống";
    }

    // Check error validate
    if (count($errorValidation) == 0) {
        // Check exist account user
        $dataUser = $userCollection->findOne(['email' => $_POST['email'], 'password' => md5($_POST['password'])]);

        // If data user not null
        if (!empty($dataUser)) {
            // Save data user into session
            $_SESSION["data_user"] = [
                "id" => $dataUser->_id,
                "email" => $dataUser->email,
                "full_name" => $dataUser->full_name,
                "role" => $dataUser->role,
            ];

            header('location:./profile.php');
        } else {
            $messageError = "Tài khoản hoặc mật khẩu không đúng";
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
                                <p>ĐĂNG NHẬP</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-box-form content-box-login">
                                <form action="" method="POST">
                                    <table>
                                        <tr>
                                            <td><label for="">Email:</label></td>
                                            <td>
                                                <input type="email" name="email" value="<?= oldValue("email") ?>" class="form-control" placeholder="nchnhu@gmail.com">
                                                <?php if (!empty($errorValidation['email'])) : ?><span class="text-danger"><?= $errorValidation['email'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Mật Khẩu:</label></td>
                                            <td>
                                                <input type="password" name="password" class="form-control" placeholder="Nhập 8 - 20 kí tự">
                                                <?php if (!empty($errorValidation['password'])) : ?><span class="text-danger"><?= $errorValidation['password'] ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- <div class="rules">
                                        <label for="remember-login">Ghi nhớ đăng nhập</label>
                                        <input type="checkbox" id="remember-login">
                                        <span>Bạn quên <a href="">mật khẩu?</a></span>
                                    </div> -->

                                    <div class="btn-form btn-login">
                                        <button class="btn" type="submit" name="submit">Đăng Nhập</button>
                                    </div>
                                </form>
                                <?php if (!empty($messageError)) : ?>
                                    <center><span class="text-danger"><?= $messageError ?></span></center>
                                <?php endif; ?>
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