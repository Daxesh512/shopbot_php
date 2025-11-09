#manba @WonderfulCoders 
#dasturchi @BestProger
#Manbani o'chirgan akkaunti chopilsin

<?php
define('API_KEY','6561816195:AAFNoTvW7hvKPNbBWfOsHiwTXoil6rReuHk');  #bot_token
$adminID = "5383623467"; #admin_id


$bot = bot('getme',['bot'])->result->username;
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
     curl_setopt($ch,CURLOPT_URL,$url);
     curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
     curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
     $res = curl_exec($ch);
     if(curl_error($ch)){
      var_dump(curl_error($ch));
     }else{
      return json_decode($res);
    }
    }


require ("sql.php");

$update = json_decode(file_get_contents("php://input"));
$message = $update->message;
$cid = $message->chat->id;
$mid = $message->message_id;
$idi = $message->from->id;
$text = $message->text; 
$name = $message->chat->first_name;
$type = $message->chat->type;
$data = $update->callback_query->data;
$ccid = $update->callback_query->message->chat->id;
$cmid = $update->callback_query->message->message_id;
$contact = $message->contact;
$phone_number = $contact->phone_number;
$step = file_get_contents("step.txt"); #step_file
$time = date('d.m.y'); #date
mkdir("$cid"); #user_file
mkdir("step");



$main = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"🔖Mahsulotlar"]],
    [['text'=>"📱Aloqa"]],
    ]
]);

$reg = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"🔖Ro`yhatdan o`tish"]],
    ]
]);

$number = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"📞Raqamni jo`natish", 'request_contact'=>true]],
    ]
]);





if ($text == "/start") {
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`users` WHERE `user_id` = '$cid'");
    $get = mysqli_fetch_assoc($result);
if($get){
    bot("sendMessage", [
        'chat_id'=> $cid,
        'text'=>"👋Assalomu aleykum $name botga xush kelibsiz!",
        'parse_mode'=>"html",
        'reply_markup'=>$main
    ]); 
}else {
    bot("sendMessage", [
        'chat_id'=> $cid,
        'text'=>"👋Assalomu aleykum $name botga xush kelibsiz!",
        'parse_mode'=>"html",
        'reply_markup'=>$reg
    ]); 
}
}

if ($text and file_get_contents("$cid/step.txt") == "ism") {
    file_put_contents("$cid/ism.txt", $text);

    bot("sendMessage", [
        'chat_id'=> $cid,
        'text'=>"<i>📞Telefon raqamingizni kiriting...</i>",
        'parse_mode'=>"html",
        'reply_markup'=>$number
    ]); 

    unlink("$cid/step.txt");
}


if ($text == "🔖Ro`yhatdan o`tish") {
    bot("sendMessage", [
        'chat_id'=> $cid,
        'text'=>"<i>👤Ismingizni kiriting...</i>",
        'parse_mode'=>"html",
    ]); 

    file_put_contents("$cid/step.txt", "ism");
} 


if (isset($contact)) {
    bot("sendMessage", [
        'chat_id'=> $cid,
        'text'=>"<i>✅Ro`yhatdan muvoffaqiyatli o`tdingiz</i>",
        'parse_mode'=>"html",
        'reply_markup'=>$main
    ]); 

    $ism = file_get_contents("$cid/ism.txt");

    mysqli_query($connect,"INSERT INTO `sardoruz_greeleaf`.`users` (`id`, `user_id`, `first_name`, `phone`) VALUES (NULL, '$cid', '$ism', '$phone_number');");

    unlink("$cid");
}


if ($text == "📱Aloqa") {
    bot("sendContact", [
        'chat_id'=> $cid,
        'phone_number'=>"+998990592008",
        'first_name'=>"SARDORBEK JUMAYEV",
        'reply_markup'=>$main
    ]);
}

if ($text == "🔖Mahsulotlar") {
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`category`");
    $get = mysqli_fetch_assoc($result);
    if($get){
    $key = [];
    while($us = mysqli_fetch_assoc($result)){
    $categ_id = $us['category_id'];
    $categ = $us['category'];
    $categ = base64_decode($categ);
    $key[] = ["text"=>"$categ","callback_data"=>"open:$categ_id"];
    $keyboard2 = array_chunk($key, 2);
    $keyboard = json_encode([
    'inline_keyboard'=>$keyboard2
    ]);
    }
    bot('sendMessage',[
    'chat_id'=>$cid,
    'text'=>"<i>📂Kerakli kategoriyani tanlang!</i>",
    'parse_mode'=>'html',
    'reply_markup'=>$keyboard
    ]);
    exit;
    }else{
    bot('sendMessage',[
    'chat_id'=>$cid,
    'text'=>"<b>❌Kategoriya mavjud emas!</b>",
    'parse_mode'=>'html',
    ]);
    exit;
    }
}


if ($data == "kateg") {
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`category`");
    $get = mysqli_fetch_assoc($result);
    if($get){
    $key = [];
    while($us = mysqli_fetch_assoc($result)){
    $categ_id = $us['category_id'];
    $categ = $us['category'];
    $categ = base64_decode($categ);
    $key[] = ["text"=>"$categ","callback_data"=>"open:$categ_id"];
    $keyboard2 = array_chunk($key, 2);
    $keyboard = json_encode([
    'inline_keyboard'=>$keyboard2
    ]);
    }
    bot('editMessageText',[
    'chat_id'=>$ccid,
    'text'=>"<i>📂Kerakli kategoriyani tanlang!</i>",
    'parse_mode'=>'html',
    'reply_markup'=>$keyboard
    ]);
    exit;
    }else{
    bot('sendMessage',[
    'chat_id'=>$cid,
    'text'=>"<b>❌Kategoriya mavjud emas!</b>",
    'parse_mode'=>'html',
    ]);
    exit;
    }
}


if(mb_stripos($data, "open:")!==false){
    $explode = explode("open:",$data);
    $excateg_id = $explode[1];
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`items` WHERE `category_id` = '$excateg_id'");
    $get = mysqli_fetch_assoc($result);
    if($get){
    $key = [];
    while($us = mysqli_fetch_assoc($result)){
    $item_id = $us['item_id'];
    $item_name = $us['name'];
    $item_name = base64_decode($item_name);
    $key[] = ["text"=>"$item_name","callback_data"=>"buy:$item_id"];
    $keyboard2 = array_chunk($key, 2);
    $keyboard = json_encode([
    'inline_keyboard'=>$keyboard2
    ]);
    }
    bot('editMessageText',[
    'chat_id'=>$ccid,
    'message_id'=>$cmid,
    'text'=>"<i>📂Kerakli Mahsulotni tanlang!</i>",
    'parse_mode'=>'html',
    'reply_markup'=>$keyboard
    ]);
    exit;
    }else{
    bot('sendMessage',[
    'chat_id'=>$cid,
    'text'=>"<b>❌Kategoriya mavjud emas!</b>",
    'parse_mode'=>'html',
    ]);
    exit;
    }
    }


    

if(mb_stripos($data, "buy:")!==false){
    $explode = explode("buy:",$data);
    $exitem_id = $explode[1];
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`items` WHERE `item_id` = '$exitem_id'");
    $get = mysqli_fetch_assoc($result);

    $nomi = base64_decode($get["name"]);
    $narxi = $get["price"];
    $info = $get["info"];
    $rasm = $get["img"];
    $itemId = $get["item_id"];
    
    file_put_contents("$ccid/item_id.txt", $itemId);


    bot("sendphoto", [
        'chat_id'=> $ccid,
        'message_id'=>cmid,
        'photo'=> $rasm,
        'caption'=>"<b>$nomi</b>

💰Narxi: $narxi so`m
📝Qo`shimcha ma`lumot: $info",
        'parse_mode'=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"➕", 'callback_data'=>"plus"], ['text'=>"1", 'callback_data'=>"son"], ['text'=>"➖", 'callback_data'=>"minus"]],
                [['text'=>"🛍Buyurtma berish", 'callback_data'=>"zakaz:$itemId"]],        
                [['text'=>"◀️Ortga", 'callback_data'=>"kateg"]],                ]
            ]),
    ]);

    file_put_contents("$cid/soni.txt", "1");
}

if ($data == "plus") {
    $soni = file_get_contents("$ccid/soni.txt");
    $soni = $soni + 1;
    file_put_contents("$ccid/soni.txt", $soni);

    $item_id = file_get_contents("$ccid/item_id.txt");

    bot("editMessageReplyMarkup", [
        'chat_id'=>$ccid,
        'message_id'=>$cmid,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"➕", 'callback_data'=>"plus"], ['text'=>"$soni", 'callback_data'=>"son"], ['text'=>"➖", 'callback_data'=>"minus"]],
                [['text'=>"🛍Buyurtma berish", 'callback_data'=>"zakaz:$item_id"]],        
                [['text'=>"◀️Ortga", 'callback_data'=>"kateg"]],        
            ]
        ])
    ]);
}


if ($data == "minus") {
    $soni = file_get_contents("$ccid/soni.txt");
    $soni = $soni - 1;
    file_put_contents("$ccid/soni.txt", $soni);

    $item_id = file_get_contents("$ccid/item_id.txt");

    bot("editMessageReplyMarkup", [
        'chat_id'=>$ccid,
        'message_id'=>$cmid,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"➕", 'callback_data'=>"plus"], ['text'=>"$soni", 'callback_data'=>"son"], ['text'=>"➖", 'callback_data'=>"minus"]],
                [['text'=>"🛍Buyurtma berish", 'callback_data'=>"zakaz:$item_id"]],        
                [['text'=>"◀️Ortga", 'callback_data'=>"kateg"]],        
            ]
        ])
    ]);
}


if(mb_stripos($data, "zakaz:")!==false){
    $explode = explode("zakaz:",$data);
    $exitem_id = $explode[1];
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`items` WHERE `item_id` = '$exitem_id'");
    $get = mysqli_fetch_assoc($result);

    $result1 = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`users` WHERE `user_id` = '$ccid'");
    $user = mysqli_fetch_assoc($result1);

    $phone = $user["phone"];
    $tg = $user["user_id"];
    $ismi = $user["first_name"];

    $nomi = base64_decode($get["name"]);
    $narxi = $get["price"];
    $rasm = $get["img"];

    $soni = file_get_contents("$ccid/soni.txt");
    $jami = $narxi * $soni;

    bot("sendphoto", [
        'chat_id'=> $adminID,
        'message_id'=>cmid,
        'photo'=> $rasm,
        'caption'=>"<b>$nomi</b>\n🎛Soni: $soni\n💰Narxi: $jami so`m\n📝Ismi: <a href='tg://user?id=$ccid'>$ismi</a>\n📲Raqam:$phone",
        'parse_mode'=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"✅", 'callback_data'=>"tasdiq:$ccid"], ['text'=>"❌", 'callback_data'=>"bekor:$ccid"]],
               ]
            ]),
    ]);
}



if(mb_stripos($data, "tasdiq:")!==false and $ccid == $adminID){
    $explode = explode("tasdiq:",$data);
    $user_id = $explode[1];

    bot("editMessageText", [
        'chat_id'=> $user_id,
        'message_id'=>$cmid,
        'text'=>"<i>☺️Buyurtmangiz tasdiqlandi.</i>",
        'parse_mode'=>"html",
        'reply_markup'=>$main
    ]);
}

if(mb_stripos($data, "bekor:")!==false and $ccid == $adminID){
    $explode = explode("bekor:",$data);
    $user_id = $explode[1];

    bot("editMessageText", [
        'chat_id'=> $user_id,
        'message_id'=>$cmid,
        'text'=>"<i>🗑Buyurtmangiz bekor qilindi.</i>",
        'parse_mode'=>"html",
        'reply_markup'=>$main
    ]);
}


### ====== ADMIN PANEL ====== ###

$adminPage = json_encode([
    'inline_keyboard'=>[
        [['text'=>"➕Kategoriya", 'callback_data'=>"addCategory"], ['text'=>"➖Kategoriya", 'callback_data'=>"delCategory"]],
        [['text'=>"➕Mahsulot", 'callback_data'=>"addItem"], ['text'=>"➖Mahsulot", 'callback_data'=>"delItem"]],
    ]
]);

$ort = json_encode([
    'inline_keyboard'=>[
    [['text'=>"◀️Ortga", 'callback_data'=>"ortga"]],
    ]
]);

if ($text == "/admin" && $cid == $adminID or $data == "ortga") {
    bot("sendMessage", [
        'chat_id'=> $cid,
        'message_id'=>$mid,
        'text'=>"<b>🧑‍💻$name admin panelga xush kelibsiz!</b>",
        'parse_mode'=>"html",
        'reply_markup'=>$adminPage
    ]); 
}

if (file_get_contents("step/$cid.txt") == "addCategory") {

    $category_id = uniqid();
    $category = base64_encode($text);
    mysqli_query($connect,"INSERT INTO `sardoruz_greeleaf`.`category` (`id`, `category_id`, `category`) VALUES (NULL, '$category_id', '$category');");


    bot("SendMessage", [
        'chat_id'=> $cid,
        'message_id'=>$mid,
        'text'=>"<b>✅$text |Kategoriya| Muvoffaqiyatli qo`shildi</b>",
        'parse_mode'=>"html",
        'reply_markup'=>$adminPage
    ]);

    unlink("step/$cid.txt");
}

if ($data == "addCategory") {
    bot("editMessageText", [
        'chat_id'=> $ccid,
        'message_id'=>$cmid,
        'text'=>"<i>📁Kategoriya qo`shish uchun nom kiriting...</i>",
        'parse_mode'=>"html",
        'reply_markup'=>$ort
    ]);

    file_put_contents("step/$ccid.txt", "addCategory"); 
}


if($data == "delCategory"){
    $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`category`");
    $get = mysqli_fetch_assoc($result);
    if($get){
    $key = [];
    while($us = mysqli_fetch_assoc($result)){
    $categ_id = $us['category_id'];
    $categ = $us['category'];
    $categ = base64_decode($categ);
    $key[] = ["text"=>"$categ","callback_data"=>"del:$categ_id"];
    $keyboard2 = array_chunk($key, 2);
    $keyboard = json_encode([
    'inline_keyboard'=>$keyboard2
    ]);
    }
    bot('sendMessage',[
    'chat_id'=>$ccid,
    'text'=>"<i>🗑O`chirmoqchi bo`lgan kategoriyangizni tanlang!</i>",
    'parse_mode'=>'html',
    'reply_markup'=>$keyboard
    ]);
    exit;
    }else{
    bot('sendMessage',[
    'chat_id'=>$ccid,
    'text'=>"<b>❌Kategoriya mavjud emas!</b>",
    'parse_mode'=>'html',
    ]);
    exit;
    }
    }


    if(mb_stripos($data, "del:")!==false){
        $explode = explode("del:",$data);
        $excateg_id = $explode[1];
        $result = mysqli_query($connect,"DELETE FROM `sardoruz_greeleaf`.`category` WHERE `category_id` = '$excateg_id'");
        $row = mysqli_fetch_assoc($result);
        
            bot("editMessageText", [
                'chat_id'=> $ccid,
                'message_id'=>$cmid,
                'text'=>"<b>✅O`chirildi</b>\n\n<i>🗄Kerakli bo`limni tanlang...</i>",
                'parse_mode'=>"html",
                'reply_markup'=>$adminPage
            ]);
        
        }




        // if (file_get_contents("step/$cid.txt") == "addCategory") {

        //     // $category_id = uniqid();
        //     // $category = base64_encode($text);
        //     // mysqli_query($connect,"INSERT INTO `sardoruz_greeleaf`.`category` (`id`, `category_id`, `category`) VALUES (NULL, '$category_id', '$category');");
        
        
        //     bot("SendMessage", [
        //         'chat_id'=> $cid,
        //         'message_id'=>$mid,
        //         'text'=>"<b>✅$text |Mahsulot| Muvoffaqiyatli qo`shildi</b>",
        //         'parse_mode'=>"html",
        //         'reply_markup'=>$adminPage
        //     ]);
        
        //     unlink("step/$cid.txt");
        // }

        if ($data == "addItem") {
            bot("deletemessage", [
                'chat_id'=>$ccid,
                'message_id'=>$cmid
            ]);
        
            $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`category`");
            $get = mysqli_fetch_assoc($result);
            if($get){
            $key = [];
            while($us = mysqli_fetch_assoc($result)){
            $categ_id = $us['category_id'];
            $categ = $us['category'];
            $categ = base64_decode($categ);
            $key[] = ["text"=>"$categ","callback_data"=>"addItem:$categ_id"];
            $keyboard2 = array_chunk($key, 2);
            $keyboard = json_encode([
            'inline_keyboard'=>$keyboard2
            ]);
            }
            bot("sendmessage", [
                'chat_id'=> $ccid,
                'text'=>"<i>📋Mahsulot qo`shish uchun kerakli kategoriyani tanlang.</i>",
                'parse_mode'=>"html",
                'reply_markup'=>$keyboard
            ]);
        
            file_put_contents('step.txt', "addItem");
        }
        }
        
        if(mb_stripos($data, "addItem:")!==false){
            $explode = explode("addItem:",$data);
            $excateg_id = $explode[1];
            file_put_contents('category.txt', $excateg_id);
            
            bot("sendmessage", [
                'chat_id'=> $ccid,
                'text'=>"<i>🖼Mahsulot qo`shish uchun rasm yuboring.</i>",
                'parse_mode'=>"html",
            ]);
        
            file_put_contents('step.txt', "pic");
        }
        
        $photo = $message->photo;
        $file = $photo[count($photo)-1]->file_id;
        if ($step == "pic") {
            file_put_contents('pic.txt', $file);
            
            bot("sendmessage", [
                'chat_id'=> $cid,
                'text'=>"<i>📌Mahsulot nomini kiriting...</i>",
                'parse_mode'=>"html",
            ]);
        
            file_put_contents('step.txt', "name");
        }
        
        if ($step == "name") {
            $text = base64_encode($text);
            file_put_contents('name.txt', $text);
            
            bot("sendmessage", [
                'chat_id'=> $cid,
                'text'=>"<i>💰Mahsulot narxini kiriting...\n(Masalan: 15000 yoki 30000)</i>",
                'parse_mode'=>"html",
            ]);
        
            file_put_contents('step.txt', "price");
        }
        
        if ($step == "price") {
            #$text = str_replace("'","\'",$text);
            file_put_contents('price.txt', $text);
            
            bot("sendmessage", [
                'chat_id'=> $cid,
                'text'=>"<i>📝Mahsulot haqida qo`shimcha ma`lumot kiriting...</i>",
                'parse_mode'=>"html",
            ]);
        
            file_put_contents('step.txt', "info");
        }
        
        $check = json_encode([
            'inline_keyboard'=>[
                [['text'=>"✅ Tasdiqlash", 'callback_data'=>"check"], ['text'=>"❌ bekor qilish", 'callback_data'=>"cancel"]],
            ]
        ]);
        
        if ($step == "info") {
            #$text = str_replace("'","\'",$text);
            file_put_contents('info.txt', $text);
        
            $rasm = file_get_contents("pic.txt");
            $nomi = file_get_contents("name.txt");
            $nomi = base64_decode($nomi);
            $narxi = file_get_contents("price.txt");
            $info = file_get_contents("info.txt");
            
            bot("sendphoto", [
                'chat_id'=> $cid,
                'photo'=> $rasm,
                'caption'=>"<b>$nomi</b>
        
💰Narxi: $narxi $valyuta
📝Qo`shimcha ma`lumot: $info",
                'parse_mode'=>"html",
                'reply_markup'=>$check
            ]);
        
            unlink("step.txt");
        }
        
        if ($data == "check") {
            bot("deletemessage", [
                'chat_id'=>$ccid,
                'message_id'=>$cmid
            ]);
        
            $rasm = file_get_contents("pic.txt");
            $nomi = file_get_contents("name.txt");
            $narxi = file_get_contents("price.txt");
            $narxi = str_replace("'","\'",$narxi);
            $info = file_get_contents("info.txt");
            $info = str_replace("'","\'",$info);
            $category = file_get_contents("category.txt");
            
            bot("sendmessage", [
                'chat_id'=> $ccid,
                'text'=>"<b>✅Mahsulot bazaga qo`shildi.</b>",
                'parse_mode'=>"html",
            ]);

            $item_id = uniqid();
        
            mysqli_query($connect,"INSERT INTO `sardoruz_greeleaf`.`items` (`id`, `name`, `category_id`, `item_id`, `price`, `info`, `img`) VALUES (NULL, '$nomi', '$category', '$item_id', '$narxi', '$info', '$rasm');");
        
            unlink("pic.txt");
            unlink("name.txt");
            unlink("price.txt");
            unlink("info.txt");
            unlink("category.txt");
        }



        if ($data == "delItem") {
        
            $result = mysqli_query($connect,"SELECT * FROM `sardoruz_greeleaf`.`items`");
            $get = mysqli_fetch_assoc($result);
            if($get){
            $key = [];
            while($us = mysqli_fetch_assoc($result)){
            $item_id = $us['item_id'];
            $item_name = $us['name'];
            $item_name = base64_decode($item_name);
            $key[] = ["text"=>"$item_name","callback_data"=>"delItem:$item_id"];
            $keyboard2 = array_chunk($key, 2);
            $keyboard = json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>$keyboard2
            ]);
            }
            bot("sendmessage", [
                'chat_id'=> $ccid,
                'text'=>"<i>🗑Qaysi mahsulotni o`chirasiz tanlang.</i>",
                'parse_mode'=>"html",
                'reply_markup'=>$keyboard
            ]);
        
        }
        }
        
        if(mb_stripos($data, "delItem:")!==false){
            $explode = explode("delItem:",$data);
            $exitem_id = $explode[1];
            bot("sendmessage", [
                'chat_id'=> $cid,
                'text'=>"🗑Mahsulot o`chirildi.",
                'parse_mode'=>"html",
            ]);
        
            $text = base64_encode($text);
            mysqli_query($connect,"DELETE FROM `sardoruz_greeleaf`.`items` WHERE `item_id`='$exitem_id'");
        }
?>