<?php
// Set time asia/Ho Chi Minh +7
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!defined('LIMIT_PAGE')) {
    define('LIMIT_PAGE', 5);
}

if (!defined('SUPER_ADMIN')) {
    define('SUPER_ADMIN', 100);
}

if (!defined('STAFF_ADMIN')) {
    define('STAFF_ADMIN', 2);
}

if (!defined('CLIENT_WEB')) {
    define('CLIENT_WEB', 1);
}

if (!defined('PARENT_TPCN')) {
    define('PARENT_TPCN', [
        "id" => 1,
        "name" => "Thực phẩm chức năng"
    ]);
}

if (!defined('PARENT_THUOC')) {
    define('PARENT_THUOC', [
        "id" => 2,
        "name" => "Thuốc"
    ]);
}

if (!defined('PARENT_DEVICE')) {
    define('PARENT_DEVICE', [
        "id" => 3,
        "name" => "Thiết bị , dụng cụ y tế"
    ]);
}

if (!defined('ORDER_PHASE_1')) {
    define('ORDER_PHASE_1', 1);
}

if (!defined('ORDER_PHASE_2')) {
    define('ORDER_PHASE_2', 2);
}

if (!defined('ORDER_PHASE_3')) {
    define('ORDER_PHASE_3', 3);
}

if (!defined('ORDER_PHASE_4')) {
    define('ORDER_PHASE_4', 4);
}

if (!defined('ORDER_PHASE_5')) {
    define('ORDER_PHASE_5', 5);
}

if (!defined('CATEGORY_SETTING_INDEX_1')) {
    define('CATEGORY_SETTING_INDEX_1', '635add14426d000031002ec4'); 
}

if (!defined('CATEGORY_SETTING_INDEX_2')) {
    define('CATEGORY_SETTING_INDEX_2', '635ae10e426d000031002eca');
}

if (!defined('DEFAULT_PRODUCT_SLIDE')) {
    define('DEFAULT_PRODUCT_SLIDE', 3);
}

function getUserCurrent() {
    if (!empty($_SESSION['data_user'])) {
        return $_SESSION['data_user'];
    }

    return [];
}

function checkUserLogin() {
    if (empty(getUserCurrent())) {
        header('location:./login.php');
    }
}

function removeImage($url) {
    if (file_exists($url)) {
        unlink($url);
    }
}

function handleMoveUploadImage($field, $folder = "common/") {
    // Folder move image upload
    $targetDir  = "./libs/public/image/" . $folder;
    // Image name upload
    $fileName = basename($field["name"]);
    // Path image move server
    $targetFilePath = $targetDir . time() . '_' . $fileName;

    move_uploaded_file($field["tmp_name"], $targetFilePath);

    return $targetFilePath;
}

function oldValue($name) {
    return !empty($_POST[$name]) ? $_POST[$name] : '';
}

function getCurrentPage() {
    $page = 1;

    if (!empty($_GET['page'])) {
        $page = $_GET['page'];
    }

    try {
        if (is_numeric($page) && $page > 0) {
            return ($page - 1) * 5;
        }

        return 0;
    } catch (\Exception $e) {
        return 0;
    }
}

function handlePaginationHtml($current_data=0) {
    $page = 1;

    if (!empty($_GET['page']) && $_GET['page'] != 1) {
        $page = $_GET['page'];
    }
    if (!is_numeric($page)) {
        $page = 1;
    }

    if ((int)getCurrentPage() == 0) {
        if ($current_data == 5) {
            return "
            <a href='?page=" . ((int) $page + 1) . "'>Next <i class='fa-sharp fa-solid fa-forward'></i></a>
            ";
        } else {
            return '';
        }
    }

    if ($current_data < 5) {
        return "
        <a href='?page=" . ((int) $page - 1) . "'><i class='fa-sharp fa-solid fa-backward'></i> Prev</a>
        ";
    }

    return "
    <a href='?page=" . ((int) $page - 1) . "'><i class='fa-sharp fa-solid fa-backward'></i> Prev</a>
    <a href='?page=" . ((int) $page + 1) . "'>Next <i class='fa-sharp fa-solid fa-forward'></i></a>
    ";
}

function checkPermissionStaff() {
    if (!empty(getUserCurrent()['role']) && getUserCurrent()['role'] == CLIENT_WEB) {
        header('location:./profile.php');
    }
}

function checkPermissionSuperAdmin() {
    if (!empty(getUserCurrent()['role']) && getUserCurrent()['role'] != SUPER_ADMIN) {
        header('location:./profile.php');
    }
}

function handleAddToCart($data) {
    if (!empty($_SESSION['carts']) && count($_SESSION['carts']) > 0) {
        if (!empty($_SESSION['carts'][$_POST['id']])) {
            $_SESSION['carts'][$_POST['id']]['quantity'] = $_SESSION['carts'][$_POST['id']]['quantity'] + $_POST['quantity'];
        } else {
            $_SESSION['carts'][$_POST['id']] = [
                'id' => $_POST['id'],
                'quantity' => $_POST['quantity'],
                'name' => !empty($data->name) ? $data->name : '',
                'price_b' => !empty($data->price_b) ? $data->price_b : '',
                'price_n' => !empty($data->price_n) ? $data->price_n : '',
                'image' => !empty($data->image) ? $data->image : '',
                'max_quantity' => !empty($data->quantity) ? $data->quantity : '',
            ];
        }
    } else {
        $_SESSION['carts'][$_POST['id']] = [
            'id' => $_POST['id'],
            'quantity' => $_POST['quantity'],
            'name' => !empty($data->name) ? $data->name : '',
            'price_b' => !empty($data->price_b) ? $data->price_b : '',
            'price_n' => !empty($data->price_n) ? $data->price_n : '',
            'image' => !empty($data->image) ? $data->image : '',
            'max_quantity' => !empty($data->quantity) ? $data->quantity : '',
        ];
    }
}

function getNameStatusOrder($status) {
    $statusName = '';
    switch($status) {
        case ORDER_PHASE_1:
            $statusName = 'Chờ xác nhận';
            break;
        case ORDER_PHASE_2:
            $statusName = 'Đã xác nhận';
            break;
        case ORDER_PHASE_3:
            $statusName = 'Đang giao hàng';
            break;
        case ORDER_PHASE_4:
            $statusName = 'Giao hàng thành công';
            break;
        case ORDER_PHASE_5:
            $statusName = 'Hoàn hàng';
            break;
        default:
            $statusName = 'Chờ xác nhận';
    }

    return $statusName;
}

function handleDateRequest($date) {
    try {
        return date("m/d/Y", strtotime($date));
    } catch (\Exception $e) {
        return date('m/d/Y');
    }
}
