<?php if (!empty(getUserCurrent())) : ?>
    <div class="row">
        <div class="col-12">
            <div class="content-box-left content-box-intro">
                <h4>Danh mục</h4>
                <div class="content-list">
                    <a href="./profile.php">Thông tin tài khoản</a>
                    <a href="./my-order.php">Đơn hàng của tôi</a>
                    <?php if (getUserCurrent()['role'] != CLIENT_WEB) : ?>
                        <a href="./admin-category.php">Danh mục sản phẩm</a>
                        <a href="./admin-agent.php">Nhà cung cấp sản phẩm</a>
                        <a href="./admin-product.php">Danh sách sản phẩm</a>
                        <a href="./admin-order.php">Danh sách đơn hàng</a>
                        <?php if (getUserCurrent()['role'] == SUPER_ADMIN) : ?>
                            <a href="./admin-user.php">Danh sách người dùng</a>
                            <a href="./admin-dashboard.php">Thống kê bán hàng</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php endif; ?>
<div class="row">
    <div class="col-12">
        <div class="content-box-left content-box-intro">
            <h4>Giới thiệu</h4>
            <div class="content-list">
                <a href="">Lịch sử phát triển</a>
                <a href="">Thành tựu đạt được</a>
                <a href="">Hướng phát triển</a>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <div class="content-box-left content-box-info">
            <h4>Thông tin</h4>
            <div class="content-list">
                <a href="">Dược phẩm mới</a>
                <a href="">Dược phẩm đã hết</a>
                <a href="">Sản phẩm sắp về</a>
            </div>
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="banner-content-left">
        <img src="./libs/public/img/Trangchu/banner-doc.jpg" alt="">
    </div>
</div>