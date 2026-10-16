<?php

$main_menu = json_encode([
'inline_keyboard'=>[
[['text'=> '🕹 بازی با کاربر تصادفی 🕹','callback_data'=>'PlayRandom']],
[['text'=> 'بازی با پسر 👱🏻','callback_data'=>'PlayWithBoys'],['text'=> '👱🏻‍♀️ بازی با دختر','callback_data'=>'PlayWithGirls']],
[['text'=> '🎯 بازی در پیوی|گروه 🎯','switch_inline_query'=>'']],
[['text'=> 'فروشگاه ما 🛍','callback_data'=>'BuyCoins'],['text'=> '💰 سکه رایگان','callback_data'=>'GetCoins']],
[['text'=> '👤 حساب من 👤','callback_data'=>'MyAccountInfo']],
[['text'=> 'ارسال سوال 📑','callback_data'=>'SubmitQuestions'],['text'=> '👨🏻‍💻 پشتیبانی','callback_data'=>'ContanctUS']],
]
]);

$admin_panel = json_encode([
'resize_keyboard' => true,
'keyboard' => [
[['text' => 'آمار ربات']],
[['text' => 'ارسال همگانی'],['text' => 'فروارد همگانی']],
[['text' => 'مشخصات کاربر']],
[['text' => 'مسدود کردن کاربر'],['text' => 'آزاد کردن کاربر']],
[['text' => 'تنظیم سکه']],
[['text' => 'افزودن سوال'],['text' => 'حذف سوال']],
[['text' => 'مشاهده سوال']],
[['text' => 'برگشت به منوی کاربری']],
]
]);