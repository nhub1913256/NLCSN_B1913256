<div class="row header-main">
    <div class="col-5">
        <img src="./libs/public/img/Trangchu/dautrang.png" alt="" class="header-main-img">
    </div>
    <div class="col-7">
        <div class="header-title">
            <img src="./libs/public/img/Trangchu/logo.jpg" alt="" class="header-title-img">
            <h1 class="header-title-main">NHÀ THUỐC THỊNH VƯỢNG</h1>
            <p class="header-title-sub">BẢO VỆ SỨC KHỎE CỦA MỌI NGƯỜI LÀ SỨ MỆNH CỦA CHÚNG TÔI</p>
        </div>
    </div>
</div>
<style>
    input[type="file"].input_image::-webkit-file-upload-button {
        visibility: hidden;
    }

    input[type="file"].input_image::before {
        content: 'Chọn ảnh tải lên bệnh cần tìm thuốc';
        display: block;
        background: #fff;
        color: #0b7dda;
        padding: 12px 20px;
        outline: none;
        white-space: nowrap;
        -webkit-user-select: none;
        cursor: pointer;
        font-size: 14px;
        border: none !important;
        border-radius: 0;
    }

    .block-search {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .btn-form-search {
        display: block;
        height: 43px;
        border: 1px solid #0b7dda;
        outline: none;
        border-radius: 10px 0 0 10px;
        padding: 0 10px;
        background-color: #0b7dda;
        color: #fff;
        cursor: pointer;
    }

    .option-search {
        background-color: #fff;
        color: #2196F3;
        font-size: 14px;
        display: none;
        position: absolute;
        top: 80%;
        width: 250px;
        z-index: 999;
    }

    .show.option-search {
        display: block !important;
    }

    .option-search {
        list-style: none;
    }

    .option-search li {
        padding: 10px 20px;
    }

    .option-search li:hover {
        background-color: #2196F3;
        color: #fff;
        cursor: pointer;
    }

    #input-search-image {
        border-radius: 0;
        height: 41px;
        width: auto;
        padding: 0;
        border-top: 1px solid #2196F3;
    }

    #box-search-by-image {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    #box-search-by-image label {
        background-color: #fff;
        color: #0b7dda;
        padding: 11px 57px;
        border-radius: 0 5px 5px 0;
        border-top: 1px solid #2196F3;
        border-bottom: 1px solid #2196F3;
        border-right: 1px solid #2196F3;
    }
</style>
<div class="row header-sub">
    <div class="search_box block-search">
        <div class="custom-select">
            <button id="btn-form-search" class="btn-form-search form_search_name">Tìm kiếm bằng: </button>
            <ul class="option-search">
                <li id="search-by-product">Tìm kiếm bằng tên thuốc</li>
                <li id="search-by-image">Chuẩn đoán bệnh bằng hình ảnh</li>
            </ul>
        </div>
        <div class="search_body" style="width: auto">
            <form id="form_search_name" action="./search-product.php" method="POST" enctype="multipart/form-data">
                <div id="box-search-by-image">
                    <input class="input_image" type="file" id="input-search-image" name="search_image" title="Chọn ảnh bệnh cần tìm thuốc">
                    <label for="input-search-image"><i class="fa-solid fa-upload"></i></label>
                </div>
                <div id="box-search-by-product">
                    <input type="text" name="search_name" id="input-search-product" placeholder="Tìm kiếm sản phẩm" style="border-radius: 0;">
                    <button id="btn-search-product"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const btn = document.getElementById('btn-form-search');
    const optionList = document.querySelector('.option-search');

    btn.addEventListener('click', function() {
        optionList.classList.toggle('show');
    });

    document.addEventListener('click', function(event) {
        if (event.target !== btn && !optionList.contains(event.target)) {
            optionList.classList.remove('show');
        }
    });

    const searchByProduct = document.getElementById('search-by-product');
    const searchByImage = document.getElementById('search-by-image');
    const inputSearchProduct = document.getElementById('input-search-product');
    const boxSeachImage = document.getElementById('box-search-by-image');
    const boxSearchProduct = document.getElementById('box-search-by-product');

    boxSeachImage.style.display = "none"
    <?php if (isset($_FILES['search_image']) && !empty($_FILES['search_image']["name"])) : ?>
        boxSearchProduct.style.display = "none"
        boxSeachImage.style.display = "flex"
        optionList.classList.remove('show');
    <?php endif; ?>

    searchByProduct.addEventListener('click', function() {
        boxSearchProduct.style.display = "block"
        boxSeachImage.style.display = "none"
        optionList.classList.remove('show');
    })

    searchByImage.addEventListener('click', function() {
        boxSearchProduct.style.display = "none"
        boxSeachImage.style.display = "flex"
        optionList.classList.remove('show');
    })

    boxSeachImage.addEventListener('change', function() {
        document.querySelector('#form_search_name').submit();
    });
</script>