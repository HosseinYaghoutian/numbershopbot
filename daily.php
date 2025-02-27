<?php
// کرون جاب هر دقیقه یک بار فعال شود
include('bot.php');
//===================================================================
$sendtoall = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM sendall  LIMIT 1"));
if($sendtoall["step"] == "sendall"){
$alluser = mysqli_num_rows(mysqli_query($connect,"select id from user"));
$users = mysqli_query($connect,"SELECT id FROM `user` LIMIT 200 OFFSET {$sendtoall["user"]}");
if($sendtoall["msgid"] == false){
while($row = mysqli_fetch_assoc($users)){
     bot('sendmessage',[
          'chat_id'=>$row["id"],        
		  'text'=>$sendtoall["text"],
        ]);
}	
}
else
{
while($row = mysqli_fetch_assoc($users)){
		bot('sendphoto',[
	'chat_id'=>$row["id"],
	'photo'=>$sendtoall["msgid"],
	'caption'=>$sendtoall["text"],
 ]);
 		bot('sendDocument',[
	'chat_id'=>$row["id"],
	'document'=>$sendtoall["msgid"],
	'caption'=>$sendtoall["text"],
 ]);
}
}
$plus = $sendtoall["user"] + 200;
$connect->query("UPDATE sendall SET user = '$plus' LIMIT 1");
if($plus >= $alluser){
  bot('sendmessage',[
      'chat_id'=>$admin[0],
      'text'=>"📍 پیام برای همه کابران ارسال شد",
 ]);
$connect->query("UPDATE sendall SET step = 'none' LIMIT 1");	
}
}
//================================================
if($sendtoall["step"] == "forall"){
$alluser = mysqli_num_rows(mysqli_query($connect,"select id from user"));
$users = mysqli_query($connect,"SELECT id FROM `user` LIMIT 200 OFFSET {$sendtoall["user"]}");
while($row = mysqli_fetch_assoc($users)){
		bot('ForwardMessage',[
'chat_id'=>$row["id"],   
'from_chat_id'=>$sendtoall["chat"],
'message_id'=>$sendtoall["msgid"],
]);	
}
$plus = $sendtoall["user"] + 200;
$connect->query("UPDATE sendall SET user = '$plus' LIMIT 1");
if($plus >= $alluser){
  bot('sendmessage',[
      'chat_id'=>$admin[0],
      'text'=>"📍 پیام برای همه کابران فوروارد شد",
 ]);
$connect->query("UPDATE sendall SET step = 'none' LIMIT 1");	
}
}
//=================================================
$daily = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM daily  LIMIT 1"));
$timenow = date("Y-m-d H:i:s");
if($timenow > $daily["time"]){
$alluser = mysqli_num_rows(mysqli_query($connect,"select id from user"));
$users = mysqli_query($connect,"SELECT id FROM `user` LIMIT 200 OFFSET {$daily["user"]}");
while($row = mysqli_fetch_assoc($users)){
     bot('sendmessage',[
          'chat_id'=>$row["id"],        
		  	'text'=>"☎️ چند روزی هست از ربات شماره مجازی تاپ نامبر استفاده نکردی !
💳 دلت نمیخواد یک استعلام بگیری ؟ شماره های جدید و خیلی خاص داخل ربات اضافه شدن .

😆 یک شماره بخر برای خودت بخر اونم خیلی سریع به صورت اتوماتیک و با درگاه مطمئن بانکی
🗣 تازه ! با معرفی ربات به دوستات 5 درصد از هر افزایش موجودی به عنوان هدیه بهت داده میشه !
👇🏻 همین الان دست به کارشو و از دکمه های زیر استفاده کن و شماره مجازی خودت رو بساز .

📣 @$usernamebot
🤖 @$channelby",
'reply_markup'=>json_encode([
    'inline_keyboard'=>[
			[
	['text'=>"🌟 شروع دوباره",'callback_data'=>"join"]
	],
				[
	['text'=>"🛍 کانال خرید ها",'url'=>"https://t.me/$channelby"]
	],
              ]
        ])
        ]);
}	
$plus = $daily["user"] + 200;
$connect->query("UPDATE daily SET user = '$plus' LIMIT 1");
if($plus >= $alluser){
  bot('sendmessage',[
      'chat_id'=>$admin[0],
      'text'=>"📍 پیام یاداوری ارسال شد",
 ]);
$time = date("Y-m-d H:i:s", strtotime("+3 day"));
$connect->query("UPDATE daily SET time = '$time' , user = '0' LIMIT 1");	
}
}	
?> 