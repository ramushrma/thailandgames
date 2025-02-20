
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PayingController;
use App\Http\Controllers\Api\GameApiController;
use App\Http\Controllers\Api\AviatorApiController;
use App\Http\Controllers\Api\ZiliApiController;


// IP	145.223.17.195
// Port	65002
// Username	u921379270

Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::get('/profile/{id}', [UserController::class, 'profile']);
Route::get('/wallet/{id}', [UserController::class, 'wallet']);
Route::post('/updateprofile', [UserController::class, 'updateprofile']);
Route::get('/allimage', [UserController::class, 'allimage']);
Route::post('/editpassword', [UserController::class, 'editpassword']);
Route::get('/notification', [UserController::class, 'notification']);
Route::get('/aboutus/{type}', [UserController::class, 'about_us']);
Route::post('/giftcards', [UserController::class, 'giftcards']);
Route::get('/Gifthistory/{userid}', [UserController::class, 'Gifthistory']);
Route::post('/feedback', [UserController::class, 'feedback']);
Route::get('/wallettransfer/{userid}', [UserController::class, 'wallettransfer']);
Route::get('/bankname', [UserController::class, 'bankname']);
Route::post('/addbank', [UserController::class, 'addbank']);
Route::get('/viewbank/{userid}', [UserController::class, 'viewbank']);
Route::get('/getPaymentLimits', [UserController::class, 'getPaymentLimits']);
Route::post('/withdrawal', [UserController::class, 'withdrawal']);
Route::post('/withdrawalhistory', [UserController::class, 'withdrawalhistory']);
Route::post('/payinghistory', [UserController::class, 'payinghistory']);
Route::get('/TransactionType', [UserController::class, 'TransactionType']);
Route::post('/Transaction_wallet_histories', [UserController::class, 'walletHistories']);
Route::get('/customer_service', [UserController::class, 'customer_service']);
Route::get('/live_notification', [UserController::class, 'notifications']);
Route::get('/banner', [UserController::class, 'banner']);





Route::controller(GameApiController::class)->group(function () {
     Route::post('/bets', 'bet');
     Route::post('/dragon_bet', 'dragon_bet');
     Route::get('/win-amount', 'win_amount');
     Route::get('/results','results');
     Route::get('/ab_results','ab_results');
     Route::get('/last_five_result','lastFiveResults');
     Route::get('/last_result','lastResults');
     Route::post('/bet_history','bet_history');
     Route::get('/cron/{game_id}/','cron');
     /// mine game route //
    //  Route::post('/mine_bet','mine_bet');
    //  Route::post('/mine_cashout','mine_cashout');
    //  Route::get('/mine_result','mine_result');
    //  Route::get('/mine_multiplier','mine_multiplier');
    
    //// Plinko Game Route /////
    
    //  Route::post('/plinko_bet','plinkoBet');
    //  Route::get('/plinko_index_list','plinko_index_list');
    //  Route::get('/plinko_result','plinko_result');
    //  Route::get('/plinko_cron','plinko_cron');
    //  Route::post('/plinko_multiplier','plinko_multiplier'); 
});

     Route::controller(AviatorApiController::class)->group(function () {
     Route::post('/aviator_bet','aviatorBet');
     Route::post('/aviator_cashout','aviator_cashout');
     Route::post('/aviator_history','aviator_history');
     Route::get('/aviator_last_five_result','last_five_result');
     Route::get('/aviator_bet_cancel','bet_cancel');
     Route::post('/result_insert_new','result_insert_new');
     Route::get('/result_half_new','result_half_new');
});


    //// Zili Api ///
    Route::post('/user_register',[ZiliApiController::class,'user_register']);  //not in use for registration
    Route::post('/all_game_list',[ZiliApiController::class,'all_game_list']);
    Route::post('/all_game_list_test',[ZiliApiController::class,'all_game_list_test']);
    Route::post('/get_game_url',[ZiliApiController::class,'get_game_url']);
    Route::post('/get_jilli_transactons_details',[ZiliApiController::class,'get_jilli_transactons_details']);
    Route::post('/jilli_deduct_from_wallet',[ZiliApiController::class,'jilli_deduct_from_wallet']);
    Route::post('/jilli_get_bet_history',[ZiliApiController::class,'jilli_get_bet_history']);
    Route::post('/add_in_jilli_wallet ',[ZiliApiController::class,'add_in_jilli_wallet']);
    Route::post('/update_main_wallet ',[ZiliApiController::class,'update_main_wallet']);
    Route::post('/get_jilli_wallet ',[ZiliApiController::class,'get_jilli_wallet']);
    
    
    
    Route::post('/update_jilli_wallet ',[ZiliApiController::class,'update_jilli_wallet']);
    Route::post('/update_jilli_to_user_wallet ',[ZiliApiController::class,'update_jilli_to_user_wallet']);
    
    
    
    
    Route::get('/test_get_user_info ',[ZiliApiController::class,'test_get_user_info']);
    Route::get('/get-reseller-info/{manager_key?}',[ZiliApiController::class,'get_reseller_info']);
    
    //// Zili Api end///

    Route::post('/paywalex', [PayingController::class, 'paywalex']); 
    // Route::post('/registers', [UserController::class, 'registers']); 
    Route::post('/usdt_payin',[PayingController::class,'payin_usdt']);
    Route::post('/payin_call_back',[PayingController::class,'payin_call_back']);
    Route::get('/pay_usdt',[PayingController::class,'pay_usdt']);
    Route::post('/upload_screenshot', [PayingController::class, 'uploadScreenshot']);
    Route::post('/depositHistories', [PayingController::class, 'depositHistories']);
    Route::post('/user_usdt_address', [PayingController::class, 'userusdtaddress']);
    Route::get('/viewUserUsdtAddress/{userid}', [PayingController::class, 'viewUserUsdtAddress']);


 









