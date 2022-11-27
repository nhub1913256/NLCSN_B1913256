<?php
use MongoDB\Client;

require_once "vendor/autoload.php";

$conn = new Client("mongodb://127.0.0.1:27017");

//select database
$db = $conn->NLCSN;

//select collection order
$orderCollection = $db->order;

//select collection order detail
$orderDetailCollection = $db->order_detail;

// Select collection agent
$agentCollection = $db->agent;

// Select collection product
$productCollection = $db->product;

// Select collection category
$categoryCollection = $db->category;

// Select collection user
$userCollection = $db->user;

// Select collection product detail
$productDetailCollection = $db->product_detail;