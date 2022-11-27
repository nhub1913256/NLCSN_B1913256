<style>
    .text-success {
        color: rgb(0, 255, 0);
    }

    .text-danger {
        color: rgb(255, 1, 1);
    }
</style>
<style>
    .box-notice {
        width: 350px;
        height: 250px;
        background-color: #fff;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000000;
        box-shadow: 0 0 15px 15px rgba(4, 92, 255, 0.12);
        border-radius: 10px;
    }

    .box-notice .notice {
        color: #5dac46;
        display: flex;
        position: relative;
        justify-content: center;
        text-align: center;
        flex-direction: column;
        vertical-align: middle;
        width: 100%;
        height: 100%;
        padding: 0 10px;
    }

    .box-notice .notice .notice-icon {
        font-size: 50px
    }

    .box-notice .notice .title-notice {
        width: 90%;
    }

    .box-notice .notice button,
    .box-notice .notice a {
        width: 40%;
        background-color: #5dac46;
        color: #fff;
    }

    .bg-notice {
        position: fixed;
        width: 100vw;
        height: 100vh;
        background-color: #04040430;
        z-index: 9999999;
        top: 0;
        left: 0;
    }
</style>

<?php if (!empty($messageSuccess)) : ?>
    <div class="bg-notice"></div>
    <div class="box-notice">
        <div class="notice">
            <div class="notice-icon">
                <img width="180px" src="./libs/public/img/success.gif" alt="">
                <!-- <i class="fa-solid fa-check"></i> -->
            </div>
            <div class="title-notice">
                <p class="text-success"><?= $messageSuccess ?></p>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            location.replace(window.location.href);
        }, 1800);
    </script>
<?php endif; ?>
<?php if (!empty($messageError)) : ?>
    <div class="bg-notice"></div>
    <div class="box-notice">
        <div class="notice">
            <div class="notice-icon">
                <i class="fa-solid fa-xmark" style="color: red"></i>
            </div>
            <div class="title-notice">
                <p class="text-danger"><?= $messageError ?></p>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            $('.bg-notice').remove();
            $('.box-notice').remove();
        }, 2500);
    </script>
<?php endif; ?>