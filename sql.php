<?php

define("DB_SERVER", "localhost"); # localhost 99% holatda o`zgarmaydi
define("DB_USERNAME", "fanzero1804_makar"); #db_name 
define("DB_PASSWORD", "Qqww1122"); #db_password
define("DB_NAME", "fanzero1804_makar"); #db_name 
$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($connect, "utf8mb4");
if($connect){
    echo "Ulandi!";
}else{
	echo "Ulanmadi!";
}



mysqli_query($connect,"CREATE TABLE `items` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` text NOT NULL,
`category_id` text NOT NULL,
`item_id` text NOT NULL,
`price` text NOT NULL,
`info` text NOT NULL,
`img` text NOT NULL,
PRIMARY KEY (`id`)
)");

mysqli_query($connect,"CREATE TABLE `category` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`category_id` text NOT NULL,
`category` text NOT NULL,
PRIMARY KEY (`id`)
)");

mysqli_query($connect,"CREATE TABLE `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` text NOT NULL,
`first_name` text NOT NULL,
`phone` text NOT NULL,
PRIMARY KEY (`id`)
)");
?>