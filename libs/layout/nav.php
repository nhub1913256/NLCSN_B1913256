<?php
include "./app/connect.php";

// Select collection category
$categoryCollection = $db->category;
// Get data category
$dataCategory = $categoryCollection->find([])->toArray();

?>
<div class="row nav">
    <div class="col-7 nav-left">
        <ul>
            <li><a href="./index.php">Trang chủ</a></li>
            <li id="mega-menu" class="menu-mega"><a href="#"><i class="fa-solid fa-list"></i> Danh mục sản
                    phẩm</a></li>
            <li><a href="./contact.php">Liên hệ</a></li>
        </ul>
    </div>
    <div class="col-5 nav-right">
        <ul>
            <?php if (!empty($_SESSION["data_user"])) : ?>
                <li><a href="./profile.php">Xin chào: <?= $_SESSION["data_user"]["full_name"] ?></a></li>
            <?php else : ?>
                <li><a href="./login.php">Đăng nhập</a></li>
                <li><a href="./register.php">Đăng kí</a></li>
            <?php endif; ?>
            <li><a href="./cart.php"><i class="fa-solid fa-cart-shopping"></i> Giỏ Hàng</a></li>
        </ul>
    </div>
    <div class="menu-mega-content">
        <div class="row">
            <div class="menu-mega-item">
                <h3>Thực phẩm chức năng</h3>
                <?php foreach ($dataCategory as $category) :
                    if (!empty($category->parent_id) && $category->parent_id == PARENT_TPCN["id"]) :
                ?>
                        <a href="./products.php#block-<?= !empty($category->_id) ? $category->_id : '' ?>"><?= !empty($category->name) ? $category->name : '' ?></a>
                <?php endif;
                endforeach; ?>
            </div>

            <div class="menu-mega-item">
                <h3>Thuốc</h3>
                <?php foreach ($dataCategory as $category) :
                    if (!empty($category->parent_id) && $category->parent_id == PARENT_THUOC["id"]) :
                ?>
                        <a href="./products.php#block-<?= !empty($category->_id) ? $category->_id : '' ?>"><?= !empty($category->name) ? $category->name : '' ?></a>
                <?php endif;
                endforeach; ?>
            </div>

            <div class="menu-mega-item">
                <h3>Thiết bị, dụng cụ y tế</h3>
                <?php foreach ($dataCategory as $category) :
                    if (!empty($category->parent_id) && $category->parent_id == PARENT_DEVICE["id"]) :
                ?>
                        <a href="./products.php#block-<?= !empty($category->_id) ? $category->_id : '' ?>"><?= !empty($category->name) ? $category->name : '' ?></a>
                <?php endif;
                endforeach; ?>
            </div>
        </div>
    </div>
</div>
