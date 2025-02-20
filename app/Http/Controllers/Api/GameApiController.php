<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Bet,Card,AdminWinnerResult,User,Betlog,GameSetting,VirtualGame,BetResult,MineGameBet,PlinkoBet,PlinkoIndexList};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helper\jilli;
use Illuminate\Support\Facades\DB;

class GameApiController extends Controller
{
    
//     public function dragonBet()
// {
//     $userid = request('userid');
//     $dragon = request('dragon');
//     $tiger = request('tiger');
//     $tie = request('tie');

//     // Retrieve the latest game number
//     $latestGame = BetResult::orderBy('id', 'desc')->first();
//     $gamesno = $latestGame ? $latestGame->gamesno + 1 : 1;

//     // Check if userid is provided
//     if (empty($userid)) {
//         return response()->json(['msg' => 'User Id Required', 'status' => '400']);
//     }

//     // Validate that at least one bet amount is provided
//     if (empty($dragon) && empty($tiger) && empty($tie)) {
//         return response()->json(['status' => '400']);
//     }

//     // Calculate total bet amount
//     $dragonamount = (int)$dragon;
//     $tigeramount = (int)$tiger;
//     $tieamount = (int)$tie;
//     $totalAmount = $dragonamount + $tigeramount + $tieamount;

//     // Check user wallet balance
//     $user = User::find($userid);
//     if (!$user || $user->wallet < $totalAmount) {
//         return response()->json(['msg' => 'Insufficient funds', 'status' => '400']);
//     }

//     // Prepare bet data
//     $betData = [
//         'gamesno' => $gamesno,
//         'userid' => $userid,
//         'dragon' => $dragonamount > 0 ? $dragonamount : null,
//         'tiger' => $tigeramount > 0 ? $tigeramount : null,
//         'tie' => $tieamount > 0 ? $tieamount : null,
//         'datetime' => now(),
//     ];

//     // Insert the bet
//     DragonBet::create($betData);

//     // Deduct amount from user wallet
//     $user->wallet -= $totalAmount;
//     $user->save();

//     return response()->json(['msg' => 'Bet Successfully..!', 'status' => '200']);
// }
	
// public function dragon_bet(Request $request){
//     // Validate request input
//     $validator = Validator::make($request->all(), [
//         'userid' => 'required|exists:users,id',
//         'game_id' => 'required|exists:betlogs,game_id',
//         'json' => 'required',
//     ]);

//     $validator->stopOnFirstFailure();

//     if ($validator->fails()) {
//         return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
//     }

//     // Get the current timestamp
//     $datetime = now();

//     // Use the JSON data directly as an array (no need to decode)
//     $testData = $request->json;
//      $uid=$request->userid;
// 	//$update_wallet = jilli::update_user_wallet($uid);
//     // Find the user and game
//     $user = User::find($request->userid);
//     $gameId = $request->game_id;

//     // Generate a unique order ID
//     $orderId = now()->format('YmdHis') . rand(11111, 99999);

//     // Get the games number for the game_id
//     $gamesno = Betlog::where('game_id', $gameId)->value('games_no');

//     // Track if the user has sufficient balance
//     $insufficientBalance = false;

//     // Loop through the bets in the decoded JSON array
//     foreach ($testData as $item) {
//         $number = $item['number'];
//         $amount = $item['amount'];

//         // Check for valid amount and user balance
//         if ($amount <= 0 || !is_numeric($amount)) {
//             return response()->json([
//                 'msg' => "Invalid bet amount for number $number",
//                 'status' => 400,
//             ]);
//         }

//         if ($user->wallet < $amount) {
//             $insufficientBalance = true;
//             break; // No need to continue, break on first insufficient balance
//         }

//         // Create a new Bet record
//         Bet::create([
//             'amount' => $amount,
//             'trade_amount' => $amount,
//             'number' => $number,
//             'games_no' => $gamesno,
//             'game_id' => $gameId,
//             'userid' => $user->id,
//             'status' => 0,
//             'order_id' => $orderId,
//             'created_at' => $datetime,
//             'updated_at' => $datetime,
//         ]);

//         // Handle the virtual game multiplier, if applicable
//       // $virtualGame = VirtualGame::where('number', $number)->first();
//       $virtualGame = DB::table('virtual_games')->where('game_id',$gameId)->where('number',$number)->first();
//       //dd($virtualGame);
       
//         if ($virtualGame) {
//             $multiplyAmt = $amount * $virtualGame->multiplier;
//             Betlog::where('game_id', $gameId)
//                 ->where('number', $virtualGame->actual_number)
//                 ->increment('amount', $multiplyAmt);
//         }
//     }

//     // If insufficient balance, return an error response
//     if ($insufficientBalance) {
//         return response()->json([
//             'msg' => "Insufficient balance for one or more bets",
//             'status' => 400,
//         ]);
//     }

//     // Deduct the total amount from the user's wallet
//     $totalAmount = array_sum(array_column($testData, 'amount'));
//     $user->decrement('wallet', $totalAmount);

// 	//$deduct_jili = jilli::deduct_from_wallet($uid,$amount);
	
//     return response()->json([
//         'status' => 200,
//         'message' => 'Bet placed successfully'
//     ]);
// }

public function dragon_bet(Request $request){
    // Validate request input
    $validator = Validator::make($request->all(), [
        'userid' => 'required|exists:users,id',
        'game_id' => 'required|exists:betlogs,game_id',
        'json' => 'required',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
    }

    // Get the current timestamp
    $datetime = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    $testData = $request->json;
    $uid = $request->userid;

    // Find the user and game
    $user = User::find($uid);
    $gameId = $request->game_id;

    // Generate a unique order ID
    $orderId = now()->format('YmdHis') . rand(11111, 99999);

    // Get the games number for the game_id
    $gamesno = Betlog::where('game_id', $gameId)->value('games_no');

    // Calculate total bet amount
    $totalAmount = array_sum(array_column($testData, 'amount'));

    // Check total available balance (bonus + wallet)
    $totalBalance = $user->bonus + $user->wallet;

    if ($totalBalance < $totalAmount) {
        return response()->json([
            'msg' => "Insufficient balance for one or more bets",
            'status' => 400,
        ]);
    }

    // Deduct from bonus first, then from wallet if necessary
    if ($user->bonus >= $totalAmount) {
        $user->bonus -= $totalAmount;
    } else {
        $remainingAmount = $totalAmount - $user->bonus;
        $user->bonus = 0;
        $user->wallet -= $remainingAmount;
    }

    // Save updated balances
    $user->save();

    // Place bets
    foreach ($testData as $item) {
        $number = $item['number'];
        $amount = $item['amount'];

        if ($amount <= 0 || !is_numeric($amount)) {
            return response()->json([
                'msg' => "Invalid bet amount for number $number",
                'status' => 400,
            ]);
        }

        // Create a new Bet record
        Bet::create([
            'amount' => $amount,
            'trade_amount' => $amount,
            'number' => $number,
            'games_no' => $gamesno,
            'game_id' => $gameId,
            'userid' => $user->id,
            'status' => 0,
            'order_id' => $orderId,
            'created_at' => $datetime,
            'updated_at' => $datetime,
        ]);

        // Handle virtual game multiplier, if applicable
        $virtualGame = DB::table('virtual_games')->where('game_id', $gameId)->where('number', $number)->first();
        if ($virtualGame) {
            $multiplyAmt = $amount * $virtualGame->multiplier;
            Betlog::where('game_id', $gameId)
                ->where('number', $virtualGame->actual_number)
                ->increment('amount', $multiplyAmt);
        }
    }

    return response()->json([
        'status' => 200,
        'message' => 'Bet placed successfully'
    ]);
}



public function dragon_bet_old(Request $request){
    $validator = Validator::make($request->all(), [
        'userid' => 'required|exists:users,id',
        'game_id' => 'required|exists:betlogs,game_id',
        'json' => 'required|json',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
    }

    $datetime = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    $testData = json_decode($request->json, true); // Decode JSON string to array
    $user = User::find($request->userid);
    $gameId = $request->game_id;

    $orderId = now()->format('YmdHis') . rand(11111, 99999);

    $gamesno = Betlog::where('game_id', $gameId)->value('games_no');

    foreach ($testData as $item) {
        $number = $item['number'];
        $amount = $item['amount'];

        if ($user->wallet >= $amount && $amount >= 1) {
            // Create a new Bet
            Bet::create([
                'amount' => $amount,
                'trade_amount' => $amount,
                'number' => $number,
                'games_no' => $gamesno,
                'game_id' => $gameId,
                'userid' => $user->id,
                'status' => 0,
                'order_id' => $orderId,
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ]);

            // Update the relevant bet log and user's wallet
            $virtualGame = VirtualGame::where('number', $number)->first();
            if ($virtualGame) {
                $multiplyAmt = $amount * $virtualGame->multiplier;
                Betlog::where('game_id', $gameId)
                    ->where('number', $virtualGame->actual_number)
                    ->increment('amount', $multiplyAmt);
            }

            $user->decrement('wallet', $amount);
            //$user->where('recharge', '>', 0)->decrement('recharge', $amount);
        } else {
            return response()->json([
                'msg' => "Insufficient balance",
                'status' => "400",
            ]);
        }
    }

    return response()->json([
        'status' => 200,
        'message' => 'Bet Successfully',
    ]);
}

//   public function bet(Request $request){
//     // Validate the request
//     $validator = Validator::make($request->all(), [
//         'userid' => 'required|exists:users,id',
//         'game_id' => 'required|exists:virtual_games,game_id',
//         'number' => 'required',
//         'amount' => 'required|numeric|min:1',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
//     }
//     // $amt=$request->amount;
//     // dd($amt);
// 	$uid=$request->userid;
// 	//$update_wallet = jilli::update_user_wallet($uid);
//     $user = User::findOrFail($request->userid);
    
// 	$amount=$request->amount;
//     // Check user wallet balance
//     if ($user->wallet < $request->amount) {
//         return response()->json(['status' => 400, 'message' => 'Insufficient balance']);
//     }

//     $commission = $request->amount * 0.05; // Calculate commission
//     //dd($commission); 
//     $betAmount = $request->amount - $commission; // Net bet amount
//     //dd($betAmount);
//     // Get virtual games data
//     $virtualGames = VirtualGame::where('number', $request->number)
//         ->where('game_id', $request->game_id)
//         ->get(['multiplier', 'actual_number']);

//     // Create a new bet
//     $bet = Bet::create([
//         'amount' => $request->amount,
//         'trade_amount' => $betAmount,
//         'commission' => $commission,
//         'number' => $request->number,
//         'games_no' => Betlog::where('game_id', $request->game_id)->value('games_no'),
//         'game_id' => $request->game_id,
//         'userid' => $user->id,
//         'order_id' => now()->format('YmdHis') . rand(11111, 99999),
//         'created_at' => now(),
//         'updated_at' => now(),
//         'status' => 0,
//     ]);
//     //dd($bet);

//     // Update bet logs
//     foreach ($virtualGames as $game) {
//         Betlog::where('game_id', $request->game_id)
//             ->where('number', $game->actual_number)
//             ->increment('amount', $betAmount * $game->multiplier);
//     }

//     // Update user's wallet and recharge
//     $user->decrement('wallet', $request->amount);
//     //$user->decrement('recharge', $request->amount);
//     $user->increment('today_turnover', $request->amount);
	
// 	//$deduct_jili = jilli::deduct_from_wallet($uid,$amount);

//     return response()->json(['status' => 200, 'message' => 'Bet Successfully']);
// }
public function bet(Request $request) {
    // Validate the request
    $validator = Validator::make($request->all(), [
        'userid' => 'required|exists:users,id',
        'game_id' => 'required|exists:virtual_games,game_id',
        'number' => 'required',
        'amount' => 'required|numeric|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $user = User::findOrFail($request->userid);
    $amount = $request->amount;
    $datetime = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    // Check total available balance (Bonus + Wallet)
    $totalBalance = $user->bonus + $user->wallet;

    if ($totalBalance < $amount) {
        return response()->json(['status' => 400, 'message' => 'Insufficient balance']);
    }

    // Deduct from Bonus first, then Wallet if needed
    $remainingAmount = $amount;

    if ($user->bonus >= $remainingAmount) {
        $user->decrement('bonus', $remainingAmount);
        $remainingAmount = 0;
    } else {
        $remainingAmount -= $user->bonus;
        $user->bonus = 0;
        $user->save();
    }

    if ($remainingAmount > 0) {
        $user->decrement('wallet', $remainingAmount);
    }

    // Calculate commission (5%) and net bet amount
    $commission = $amount * 0.05;
    $betAmount = $amount - $commission;

    // Get virtual games data
    $virtualGames = VirtualGame::where('number', $request->number)
        ->where('game_id', $request->game_id)
        ->get(['multiplier', 'actual_number']);

    // Create a new bet
    $bet = Bet::create([
        'amount' => $amount,
        'trade_amount' => $betAmount,
        'commission' => $commission,
        'number' => $request->number,
        'games_no' => Betlog::where('game_id', $request->game_id)->value('games_no'),
        'game_id' => $request->game_id,
        'userid' => $user->id,
        'order_id' => now()->format('YmdHis') . rand(11111, 99999),
        'created_at' => $datetime,
        'updated_at' => $datetime,
        'status' => 0,
    ]);

    // Update bet logs
    foreach ($virtualGames as $game) {
        Betlog::where('game_id', $request->game_id)
            ->where('number', $game->actual_number)
            ->increment('amount', $betAmount * $game->multiplier);
    }

    // Increment user's turnover
    $user->increment('today_turnover', $amount);

    return response()->json(['status' => 200, 'message' => 'Bet Successfully']);
}


    public function win_amount(Request $request){
    $validator = Validator::make($request->all(), [ 
        'userid' => 'required|integer',
        'game_id' => 'required|integer',
        'games_no' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
    }

    $game_id = $request->game_id;
    $userid = $request->userid;
    $game_no = $request->games_no;
    
    // echo "$game_id,$userid,$game_no";
    // die;
   
    $win_amount = Bet::selectRaw('SUM(win_amount) AS total_amount, games_no, game_id AS gameid, win_number AS number, 
        CASE WHEN SUM(win_amount) = 0 THEN "lose" ELSE "win" END AS result')
        ->where('games_no', $game_no)
        ->where('game_id', $game_id)
        ->where('userid', $userid)
        ->groupBy('games_no', 'game_id', 'win_number')
        ->first();
       
    if ($win_amount) {
         $win = [
    'win' => $win_amount->total_amount,
    'games_no' => $win_amount->games_no,
    'result' => $win_amount->result,
    'gameid' => $win_amount->gameid,
    'number' => $win_amount->number
];
        
        return response()->json([
            'message' => 'Successfully',
            'status' => 200,
            'data' => $win,
            
        ], 200);
    } else {
        return response()->json(['msg' => 'No record found', 'status' => 400], 200);
    }
}

// public function results(Request $request)
// {
//     // Validate incoming request data
//     $validator = Validator::make($request->all(), [
//         'game_id' => 'required',
//         'limit' => 'required|integer|min:1', // Ensure limit is a positive integer
//         'offset' => 'integer|min:0', // Ensure offset is a non-negative integer
//         'created_at' => 'array', // Expect created_at as an array
//         'created_at.from' => 'date|nullable', // Validate from date
//         'created_at.to' => 'date|nullable', // Validate to date
//         'status' => 'string|nullable', // Optional status validation
//     ]);

//     $validator->stopOnFirstFailure();

//     // Return error response if validation fails
//     if ($validator->fails()) {
//         return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
//     }

//     // Extract validated parameters
//     $gameId = $request->game_id;
//     $limit = $request->limit;
//     $offset = $request->offset ?? 0;
//     $fromDate = $request->created_at['from'] ?? null;
//     $toDate = $request->created_at['to'] ?? null;

//     // Build the query using Eloquent
//     $query = BetResult::with(['virtualGame', 'gameSetting'])
//         ->where('game_id', $gameId);

//     // Add date range filter if both dates are provided
//     if ($fromDate && $toDate) {
//         $query->whereBetween('created_at', [$fromDate, $toDate]);
//     }

//     // Execute the query with ordering, offset, and limit
//     $results = $query->orderBy('id', 'desc')
//                       ->offset($offset)
//                       ->limit($limit)
//                       ->get();

//     // Return the results in a JSON response
//     return response()->json([
//         'status' => 200,
//         'message' => 'Data found',
//         'data' => $results
//     ]);
// }

 public function results(Request $request){
    $validator = Validator::make($request->all(), [
        'game_id' => 'required',
        'limit' => 'required|integer',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $game_id = $request->game_id;
    $limit = $request->limit;
    $offset = $request->offset ?? 0;
    $from_date = $request->from_date;
    $to_date = $request->to_date;
    $status = $request->status;

    // Build the query
    $query = BetResult::where('game_id', $game_id);

    if (!empty($from_date) && !empty($to_date)) {
        $query->whereBetween('created_at', [$from_date, $to_date]);
    }

    if (!empty($status)) {
        $query->where('status', $status);
    }

    // Retrieve the results with limit and offset
    $results = $query->orderBy('id', 'desc')
                     ->offset($offset)
                     ->limit($limit)
                     ->get();

    // Get the total count of bet_results for the game_id
    $total_result = BetResult::where('game_id', $game_id)->count();
    $last_winner=DB::select("SELECT `random_card` FROM `bet_results` WHERE `game_id`=13 ORDER BY `games_no` DESC LIMIT 1 OFFSET 1");
    $winner=$last_winner[0]->random_card;
   // dd($winner);
    return response()->json([
        'status' => 200,
        'message' => 'Data found',
        'total_result' => $total_result,
        'data' => $results,
        'winner_no'=>$winner
    ]);
}

//// last
public function lastFiveResults(Request $request){
    $validator = Validator::make($request->all(), [
        'game_id' => 'required',
        'limit' => 'required|integer'
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }
    
    $game_id = $request->game_id;
    $limit = (int) $request->limit;
    $offset = (int) ($request->offset ?? 0);
    // $from_date = $request->from_date;
    // $to_date = $request->to_date;

    $query = BetResult::where('game_id', $game_id);

    // Apply date range filter if provided
    // if ($from_date && $to_date) {
    //     $query->whereBetween('created_at', [$from_date, $to_date]);
    // }

    // Fetch the results with limit and offset
    $results = $query
        ->orderBy('games_no', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();
//dd($query);
    return response()->json([
        'status' => 200,
        'message' => 'Data found',
        'data' => $results
    ]);
}

// last result ///
    public function lastResults(Request $request){
    //dd($request);
    $validator = Validator::make($request->all(), [
        'game_id' => 'required',
    ]);
 
    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }
    
    $game_id = $request->game_id;
    //dd($game_id);
    // $offset = (int) ($request->offset ?? 0);
    // $from_date = $request->from_date;
    // $to_date = $request->to_date;
    $results= BetResult::where('game_id', $game_id)->latest()->first();
   // dd($results);
    // $query = BetResult::where('game_id', $game_id);

    // // Apply date range filter if provided
    // if ($from_date && $to_date) {
    //     $query->whereBetween('created_at', [$from_date, $to_date]);
    // }

    // // Fetch the results with limit and offset
    // $results = $query
    //     ->orderBy('id', 'desc')
    //     ->offset($offset)
    //     ->limit(1)
    //     ->get();

    return response()->json([
        'status' => 200,
        'message' => 'Data found',
        'data' => $results
    ]);
}

// public function bet_history(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'userid' => 'required',
//         'game_id' => 'required',
//         'limit' => 'sometimes|integer',
//         'offset' => 'sometimes|integer',
//         'created_at' => 'sometimes|date',
//     ]);

//     $validator->stopOnFirstFailure();

//     if ($validator->fails()) {
//         return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
//     }

//     $userid = $request->userid;
//     $game_id = $request->game_id;
//     $limit = $request->limit ?? 10000;
//     $offset = $request->offset ?? 0;
//     $from_date = $request->created_at;
//     $to_date = $request->created_at;

//     $query = Bet::with(['gameSetting', 'virtualGame'])
//         ->where('userid', $userid)
//         ->where('game_id', $game_id);

//     if ($from_date) {
//         $query->whereBetween('created_at', [$from_date, $to_date]);
//     }

//     $results = $query->orderBy('id', 'DESC')
//                      ->offset($offset)
//                      ->limit($limit)
//                      ->get();

//     $total_bet = Bet::where('userid', $userid)->count();

//     if ($results->isNotEmpty()) {
//         return response()->json([
//             'status' => 200,
//             'message' => 'Data found',
//             'total_bets' => $total_bet,
//             'data' => $results,
//         ]);
//     } else {
//         return response()->json([
//             'status' => 400,
//             'message' => 'No Data found',
//             'data' => $results,
//         ]);
//     }
// }

public function bet_history(Request $request){
    // Validate the request
    $validator = Validator::make($request->all(), [
        'userid' => 'required|integer',
        'game_id' => 'required|integer',
        'limit' => 'integer|nullable',
        'offset' => 'integer|nullable',
        'from_date' => 'date|nullable',
        'to_date' => 'date|nullable',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    // Extract validated data
    $userid = $request->userid;
    $game_id = $request->game_id;
    $limit = $request->limit ?? 10000;
    $offset = $request->offset ?? 0;

    // Build the query
    $query = DB::table('bets')
        ->select('bets.*', 'game_settings.name AS game_name', 'virtual_games.name AS virtual_game_name')
        ->leftJoin('game_settings', 'game_settings.id', '=', 'bets.game_id')
        ->leftJoin('virtual_games', function ($join) {
            $join->on('virtual_games.game_id', '=', 'bets.game_id')
                 ->on('virtual_games.number', '=', 'bets.number');
        })
        ->where('bets.userid', $userid)
        ->where('bets.game_id', $game_id);

    // Apply date filters if provided
    if ($request->from_date) {
        $query->where('bets.created_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->where('bets.created_at', '<=', $request->to_date);
    }
    // Apply pagination
    $results = $query->orderBy('bets.id', 'DESC')
                     ->offset($offset)
                     ->limit($limit)
                     ->distinct()
                     ->get();
    // Get total bets count for the user
   $total_bet = DB::table('bets')
    ->where('userid', $userid)
    ->where('game_id', $game_id)
    ->count(); 
      
    
    // Prepare the response
    if ($results->isNotEmpty()) {
        return response()->json([
            'status' => 200,
            'message' => 'Data found',
            'total_bets' => $total_bet,
            'data' => $results
        ]);
    } else {
        return response()->json([
            'status' => 200,
            'message' => 'No Data found',
            'data' => []
        ]);
    }
}

 public function cron($game_id){
    // Fetch winning percentage using Eloquent
    $winningSetting = GameSetting::find($game_id);
    //dd($winningSetting);
    $percentage = $winningSetting->winning_percentage;

    // Get the latest game number from bet logs
    $latestBetLog = Betlog::where('game_id', $game_id)->orderBy('id', 'desc')->first();
    
    if (!$latestBetLog) {
        return; // Handle the case when there are no bets
    }

    $game_no = $latestBetLog->games_no;
    $period = $game_no;
    //dd($period);

    // Calculate total amount from bets
    $totalAmount = Bet::where('game_id', $game_id)
                      ->where('games_no', $period)
                      ->sum('amount');

    $percentageAmount = $totalAmount * $percentage * 0.01;

    // Get the first bet that meets the criteria
    $lessAmount = Betlog::where('game_id', $game_id)
                        ->where('games_no', $period)
                        ->where('amount', '<=', $percentageAmount)
                        ->orderBy('amount')
                        ->first();

    if (!$lessAmount) {
        $lessAmount = Betlog::where('game_id', $game_id)
                            ->where('games_no', $period)
                            ->where('amount', '>=', $percentageAmount)
                            ->orderBy('amount')
                            ->first();
    }

    $zeroAmount = Betlog::where('game_id', $game_id)
                         ->where('games_no', $period)
                         ->where('amount', 0)
                         ->inRandomOrder()
                         ->first();

    // Fetch the admin winner result
    $adminWinner = AdminWinnerResult::where('gamesno', $period)
                                     ->where('gameId', $game_id)
                                     ->first();

    // Get min and max numbers from bet logs
    $minMax = Betlog::selectRaw('MIN(number) as mins, MAX(number) as maxs')
                    ->where('game_id', $game_id)
                    ->first();

    $res = null;

    if ($adminWinner) {
        $res = $adminWinner->number;
    } elseif ($totalAmount < 150) {
        $res = rand($minMax->mins, $minMax->maxs);
    } elseif ($totalAmount > 150 && $lessAmount) {
        $res = $lessAmount->number;
    }

    // Call respective methods based on game_id
    if (in_array($game_id, [1, 2, 3, 4])) {
        $this->colour_prediction_and_bingo($game_id, $period, $res);
    } elseif ($game_id == 10) {
        $this->dragon_tiger($game_id, $period, $res);
    } elseif (in_array($game_id, [6, 7, 8, 9])) {
        $this->trx($game_id, $period, $res);
    }elseif ($game_id == 13) {
        $this->andarbaharpatta($game_id, $period, $res);
    }
}
	
	
	
	 private function andarbaharpatta($game_id,$period,$res){
         //dd($game_id,$period,$res);
		 //dd($game_id);
      $lastimage=DB::select("SELECT cards.*, bet_results.random_card AS rand_card, bet_results.`game_id` AS gameiid,bet_results.id as rid FROM cards JOIN bet_results ON cards.card = bet_results.random_card WHERE bet_results.`game_id` = $game_id ORDER BY bet_results.id DESC LIMIT 1; 
        ");
	//	 dd($lastimage);
 
            //card id
         $rescardid = $lastimage[0]->id;
       
         $result=$lastimage[0]->card;//  card number
             
        
     $randomNumber = rand(18, 38); 
     
     $evenNumber = $randomNumber * 2; 
   


 $randomNumbers = rand(18, 38); 
     $evenNumbersss = $randomNumbers % 2; 
      
if($evenNumbersss ==1){
$dragon=$randomNumbers;

}else{
    $dragon=$randomNumbers-1;
    
}
     //echo $dragon; 
     $limit=$dragon-1;
     $patta=DB::select("SELECT * FROM cards where card != $result  ORDER BY RAND(colour) LIMIT $limit");
     //dd($patta);
     $pattafinal =DB::select("SELECT * FROM cards where card = $result  && id !=$rescardid ORDER BY RAND(id) LIMIT 1");
     $cards=array();
     foreach($patta as $item)
     {
  
     $image = $item->card;
     $cards[] = $image;
//dd($cards);
    
     }
    
      $cards[]=DB::select("SELECT * FROM cards where card = $result  && id !=$rescardid ORDER BY RAND(id) LIMIT 9")[0]->card;
     $dataa=json_encode($cards);
		 //dd($dataa);
    $nextresultcard =DB::select("SELECT * FROM cards where id !=$rescardid ORDER BY RAND(colour) LIMIT 1")[0]->card;
    
   
    
     DB::select("INSERT INTO `bet_results`( `number`, `games_no`, `game_id`, `status`,`json`,`random_card`) VALUES ('$res','$period','$game_id','1','$dataa','$nextresultcard')"); 
      $this->amountdistributioncolors($game_id,$period,$res);
      DB::select("UPDATE `betlogs` SET amount=0,games_no=games_no+1 where game_id =  '$game_id'"); 
     return true;
     
  }


     private function colour_prediction_and_bingo($game_id, $period, $res){
            //echo"$game_id,$period,$res";
            // Fetch the colours associated with the given game_id and result
            $colours = VirtualGame::where('actual_number', $res)
                ->where('game_id', $game_id)
                ->where('multiplier', '!=', '1.5')
                ->pluck('name');
        //dd($colours);
            // Convert the collection to JSON
            $pdata = json_encode($colours);
            //dd($pdata);
            // Insert the bet result
            BetResult::create([
                'number' => $res,
                'games_no' => $period,
                'game_id' => $game_id,
                'status' => 1,
                'json' => $pdata,
                'random_card' => $res
            ]);
        
            
            
            // Call the amount distribution method
            $this->amountdistributioncolors($game_id, $period, $res);
            // Update bet logs
            Betlog::where('game_id', $game_id)
                ->update(['amount' => 0, 'games_no' => \DB::raw('games_no + 1')]);
        
            return true;
        
        }
/// trx ////

   private function trx($game_id,$period,$result){
      
       $colour=DB::select("SELECT `name` FROM `virtual_games` WHERE actual_number=$result && game_id=$game_id && `multiplier` !='1.5'");
      
       $tokens=$this->generateRandomString().$result;
		 
       $json=[];
       foreach ($colour as $item){
           $json[]=$item->name;
       }
       $pdata=json_encode($json);
		 $blockk = DB::table('bet_results')
            ->selectRaw('`block` + CASE 
                            WHEN ? = 6 THEN 20 
                            WHEN ? = 7 THEN 60 
                            WHEN ? = 8 THEN 100 
                            ELSE 200 
                          END AS adjusted_block', [$game_id, $game_id, $game_id])
            ->where('game_id', $game_id)
            ->orderByDesc('id')
            ->limit(1)
            ->first();
	$block=$blockk->adjusted_block;
       DB::select("
     INSERT INTO `bet_results` (`number`, `games_no`, `game_id`, `status`, `json`, `random_card`, `token`,`block`)VALUES ('$result', '$period', '$game_id', '1', '$pdata','$result', '$tokens','$block')");
          $this->amountdistributioncolors($game_id,$period,$result);
         DB::select("UPDATE `bets` SET `status`=2 WHERE `games_no`='$period' && `game_id`=  '$game_id' && number ='$result' && `status`=0;");
         DB::select("UPDATE `betlogs` SET amount=0,games_no=games_no+1 where game_id =  '$game_id';");
      return true;
			
   }
   
     private function generateRandomString($length = 4) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $maxIndex)];
    }
    return $randomString;
}


    private function dragon_tiger($game_id, $period, $res){
    $data = [];
    
    try {
        if ($res == 1) {
            $rand = rand(2, 13);
            $card1 = Card::where('card', '>', $rand)
                ->inRandomOrder()
                ->first();
                
            $rand2 = rand(2, $rand - 2);
            $card2 = Card::where('card', '>', $rand2)
                ->inRandomOrder()
                ->first();
                
            $data = [$card1->card ?? null, $card2->card ?? null];
        } elseif ($game_id == 2) {
            $rand = rand(2, 13);
            $card2 = Card::where('card', '>', $rand)
                ->inRandomOrder()
                ->first();
                
            $rand2 = rand(2, $rand - 2);
            $card1 = Card::where('card', '>', $rand2)
                ->inRandomOrder()
                ->first();
                
            $data = [$card1->card ?? null, $card2->card ?? null];
        } else {
            $rand = rand(2, 13);
            $card2 = Card::where('card', $rand)
                ->orderBy('id', 'asc')
                ->first();
                
            $card1 = Card::where('card', $rand)
                ->orderBy('id', 'desc')
                ->first();
                
            $data = [$card1->card ?? null, $card2->card ?? null];
        }

        $resJson = json_encode($data);
        
        BetResult::create([
            'number' => $res,
            'games_no' => $period,
            'game_id' => $game_id,
            'status' => 1,
            'json' => $resJson,
        ]);

        $this->amountDistributionColors($game_id, $period, $res);
        
        Betlog::where('game_id', $game_id)
            ->update(['amount' => 0, 'games_no' => DB::raw('games_no + 1')]);

    } catch (\Exception $e) {
        Log::error('Error in dragonTiger function: ' . $e->getMessage());
    }
}

    private function amountdistributioncolors($game_id, $period, $res){
    //echo"$game_id,$period,$res";
    // Fetch the virtual games based on criteria
    $virtualGames = VirtualGame::where('actual_number', $res)
        ->where('game_id', $game_id)
        ->where(function ($query) {
            $query->where('type', '!=', 1)->where('multiplier', '!=', '1.5')
                  ->orWhere(function ($query) {
                      $query->where('type', 1)->where('multiplier', '1.5');
                  });
        })
        ->get();
    //dd($virtualGames);
    foreach ($virtualGames as $winAmount) {
        $multiple = $winAmount->multiplier;
        $number = $winAmount->number;

        if (!empty($number)) {
            // Update bet for result '0'
            //dd($number);
            if ($res == '0') {
                //dd("hii");
                $test= Bet::where('games_no', $period)
                    ->where('game_id', $game_id)
                    ->where('number', $res)
                    ->update(['win_amount' => DB::raw('trade_amount * 9'), 'win_number' => '0', 'status' => 1]);
                   //dd($test); 
            }
              //dd("hello");
            // Update bets based on multiplier
           $test1= Bet::where('games_no', $period)
                ->where('game_id', $game_id)
                ->where('number', $number)
                ->update(['win_amount' => DB::raw("trade_amount * $multiple"), 'win_number' => $res, 'status' => 1]);
        }
    }

    // Update users' wallets based on the winning amounts
    $winningBets = Bet::where('win_number', '>=', 0)
        ->where('games_no', $period)
        ->where('game_id', $game_id)
        ->get();

    foreach ($winningBets as $bet) {
        $amount = $bet->win_amount;
        $userId = $bet->userid;

      $amount = (float) $amount;

    User::where('id', $userId)
        ->update([
            'wallet' => DB::raw("wallet + {$amount}"), 
            'winning_wallet' => DB::raw("winning_wallet + {$amount}"),
            'updated_at' => now()
        ]); 
		///jilli///
		
		//$add_jili = jilli::add_in_jilli_wallet($userId,$amount);

		
		///end jilli////


    }

    // Update bets with no winning amount
    Bet::where('games_no', $period)
        ->where('game_id', $game_id)
        ->where('status', 0)
        ->where('win_amount', 0)
        ->update(['status' => 2, 'win_number' => $res]);
}


////// Mine Game Api ///////

    public function mine_bet(Request $request){
    $validator = Validator::make($request->all(), [
        'userid' => 'required',
        'game_id' => 'required',
        'amount' => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $userid = $request->userid;
    $gameid = $request->game_id;
    $amount = $request->amount;

    date_default_timezone_set('Asia/Kolkata');
    $datetime = now(); // Using Laravel's now() function
    $orderid = now()->format('YmdHis') . rand(11111, 99999);
    //dd($orderid);
    $tax = 0.00;
    $commission = $amount * $tax;
    $betAmount = $amount - $commission;

    $user = User::find($userid);
          
    if ($amount >= 10) {
        if ($user && $user->wallet >= $amount) {
            // Create the bet record
            MineGameBet::create([
                'amount' => $amount,
                'game_id' => $gameid,
                'userid' => $userid,
                'status' => 0,
                'created_at' => $datetime,
                'updated_at' => $datetime,
                'tax' => $tax,
                'after_tax' => $betAmount,
                'order_id' => $orderid   
            ]);

            // Update the user's wallet
            $user->decrement('wallet', $amount);

            return response()->json(['status' => 200, 'message' => 'Bet placed successfully'], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'Insufficient balance'], 400);
        }
    } else {
        return response()->json(['status' => 400, 'message' => 'Bet placed minimum 10 rupees'], 400);
    }
}

    public function mine_cashout(Request $request){
        $validator = Validator::make($request->all(), [
        'userid' => 'required|integer',
        'win_amount' => 'required|numeric',
        'multipler' => 'required|numeric',
        'status' => 'required|integer'
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
    }

    $userid = $request->userid;
    $win_amount = $request->win_amount;
    $status = $request->status;
    $multipler = $request->multipler;

    date_default_timezone_set('Asia/Kolkata');
    $datetime = now(); // Use Laravel's helper function for current timestamp

    $user = User::find($userid);
    if (!$user) {
        return response()->json(['status' => 400, 'message' => 'User does not exist'], 400);
    }

    $minegame_bet = MinegameBet::where('userid', $userid)
        ->where('Status', 0)
        ->orderBy('id', 'asc')
        ->first();

    if (!$minegame_bet) {
        return response()->json(['status' => 400, 'message' => 'No active minegame bet found for the user'], 400);
    }

    $minegame_bet->update([
        'Status' => $status,
        'multipler' => $multipler,
        'win_amount' => $win_amount
    ]);

    $user->increment('wallet', $win_amount); // This updates the wallet by adding the win_amount

    return response()->json([
        'status' => 200,
        'message' => 'CashOut successfully',
        'win_amount' => $win_amount
    ], 200);
}

    public function mine_result(Request $request){
    $validator = Validator::make($request->all(), [
        'userid' => 'required',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'message' => $validator->errors()->first()
        ], 400);
    }

    $userid = $request->userid;
    $limit = $request->limit ?? 0;
    $offset = $request->offset ?? 0;

    $query = MinegameBet::where('userid', $userid)
                        ->where(function ($query) {
                            $query->where('status', 1)
                                  ->orWhere('status', 2);
                        })
                        ->orderBy('id', 'DESC');

    // Apply pagination if limit is provided
    if ($limit > 0) {
        $data = $query->skip($offset)->take($limit)->get();
    } else {
        $data = $query->get();
    }

    $count = $query->count();

    if (!$data->isEmpty()) {  
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'count' => $count,
            'data' => $data
        ], 200);
    } else {
        return response()->json([
            'status' => 200,
            'message' => 'No data found'
        ], 200);
    }
}

    public function mine_multiplier()  {
    $multipliers = DB::table('mine_multipliers')
                ->select('id','name', 'multiplier')
                ->get(); // Use the Card model to fetch all records

    if ($multipliers->isNotEmpty()) { // Check if the collection is not empty
        $response['status'] = 200;
        $response['data'] = $multipliers;
    } else {
        $response['status'] = "400";
        $response['data'] = [];
    }

    return response()->json($response);
}

public function plinkoBet(Request $request)
{
    $validator = Validator::make($request->all(), [
        'userid' => 'required',
        'game_id' => 'required',
        'amount' => 'required|numeric|min:0',
        'type' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $userid = $request->userid;
	//$update_wallet = jilli::update_user_wallet($userid);
    $gameid = $request->game_id;
    $amount = $request->amount; 
    $type = $request->type; 
	date_default_timezone_set('Asia/Kolkata');
    $datetime = date('Y-m-d H:i:s');
    $orderid = date('YmdHis') . rand(11111, 99999);
    $tax = 0.00;
    $commission = $amount * $tax; // Calculate commission
    $betAmount = $amount - $commission;
    $userWallet = DB::table('users')->where('id', $userid)->value('wallet');
   if($amount >= 10){
       
    // DB::table('plinko_bet')->where('userid', $userid)->where('status', 0)->where('multipler', 0)->where('indexs', 0)->delete();
       
   $alreadyBet = DB::table('plinko_bets')->where('userid', $userid)->where('status', 0)->orderBy('id', 'DESC')->first();

    if (empty($alreadyBet)) {
        if ($userWallet >= $amount) {
           $plinkoBetId =  DB::table('plinko_bets')->insertGetId([
                'amount' => $amount,
                'game_id' => $gameid,
                'type' => $type,
                'userid' => $userid,
                'status' => 0,
                'created_at' => $datetime,
                'tax' => $tax,
                'after_tax' => $betAmount,
                'orderid' => $orderid
            ]);
            
            

            DB::update("UPDATE users SET wallet = wallet - $amount WHERE id = $userid");
			//$deduct_jili = jilli::deduct_from_wallet($userid,$amount);
			
           $plinkoBet = DB::table('plinko_bets')->where('id',$plinkoBetId)->first();
            return response()->json(['status' => 200, 'message' => 'Bet placed successfully', 'data'=>$plinkoBet ], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'Insufficient balance'], 400);
        }
    } else {
       
        return response()->json(['status' => 400, 'message' => 'Already Bet placed'], 400);
         
    }
} else {
    return response()->json(['status' => 400, 'message' => 'Bet placed minimum 10 rupees'], 400);
}

}	
	
public function plinko_index_list(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'type' => 'required',
    ]);

    $validator->stopOnFirstFailure();
    
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'message' => $validator->errors()->first()
        ], 400);
    }
    
    $type = $request->type;
    
    $data = DB::table('plinko_index_lists')
        ->where('type', $type)
        ->get();

    if (!$data->isEmpty()) {  
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        ], 200);
    } else {
        return response()->json([
            'status' => 400,
            'message' => 'No data found'
        ], 400);
    }
}

	public function plinko_multiplier(Request $request)
{
    
    $validator = Validator::make($request->all(), [
        'userid' => 'required|integer',
        'index' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
    }

    $userid = $request->userid;
    $index = $request->index;
	date_default_timezone_set('Asia/Kolkata');	
    $datetime = date('Y-m-d H:i:s');

    $plinko_bet = DB::table('plinko_bets')
        ->where('userid', $userid)
        ->where('Status', 0)
        ->orderBy('id', 'asc')
        ->first();

    if (!$plinko_bet) {
        return response()->json(['status' => 400, 'message' => 'No active plinko bet found for the user'], 400);
    }

    $bet_amount = $plinko_bet->amount;
    $type = $plinko_bet->type;


    $index_multiplier = DB::table('plinko_index_lists')
        ->where('type', $type)
        ->where('indexs', $index)
        ->first();


    if (empty($index_multiplier)) {
        DB::table('plinko_bets')
            ->where('id', $plinko_bet->id)
            ->update(['Status' => 1, 'indexs' => $index, 'multipler' => 'out', 'win_amount' => 0]);

        return response()->json([
            'status' => 200,
            'message' => 'Plinko result calculated successfully',
            'win_amount' => '0'
        ], 200);
    }
    $multipler=$index_multiplier->multiplier;
  
    $win_amount = $bet_amount * $multipler;

 
    DB::table('plinko_bets')
        ->where('id', $plinko_bet->id)
        ->update(['Status' => 1, 'indexs' => $index, 'multipler' => $multipler,'win_amount' => $win_amount]);

     DB::update("UPDATE users SET wallet = wallet + $win_amount  WHERE id = $userid");
		
		///jilli///
		
		//$add_jili = jilli::add_in_jilli_wallet($userid,$win_amount);

		
		///end jilli////
		
    return response()->json([
        'status' => 200,
        'message' => 'Plinko result calculated successfully',
        'win_amount' => $win_amount
    ],200);
} 

public function plinko_result(Request $request){
    $validator = Validator::make($request->all(), [
        'userid' => 'required',
    ]);
    $validator->stopOnFirstFailure();
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'message' => $validator->errors()->first()
        ], 400);
    }
    $userid = $request->userid;
    $limit = $request->limit??0;
	$offset = $request->offset ?? 0;
   if(empty($limit)) {
        $data = DB::table('plinko_bets')->where('userid', $userid)->where('status', 1)->orderBy('id', 'DESC')->get();
    } else {
        $data = DB::table('plinko_bets')->where('userid', $userid)->where('status', 1)->orderBy('id', 'DESC')->skip($offset)->take($limit)->get();
    }   
    if(!$data->isEmpty()) {  
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        ], 200);
    } else {
        return response()->json([
            'status' => 400,
            'message' => 'No data found'
        ], 400);
    }
}


}
