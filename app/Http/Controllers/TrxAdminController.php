<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use DB;

class TrxAdminController extends Controller
{
   public function trx_create($gameid)
    {
	   //dd($gameid);
        $bets = DB::select("SELECT betlogs.*,game_settings.winning_percentage AS parsantage ,game_settings.id AS id FROM `betlogs` LEFT JOIN game_settings ON betlogs.game_id=game_settings.id where betlogs.game_id=$gameid Limit 10;");
  //dd($bets);
       return view('trx.index')->with('bets', $bets)->with('gameid', $gameid);
    }

    public function fetchData($gameid)
    {
        $bets = DB::select("SELECT betlogs.*,game_settings.winning_percentage AS parsantage ,game_settings.id AS id FROM `betlogs` LEFT JOIN game_settings ON betlogs.game_id=game_settings.id where betlogs.game_id=$gameid Limit 10;");

        return response()->json(['bets' => $bets, 'gameid' => $gameid]);
    }
	
	
	public function store(Request $request)
	{
		
	
	  $gamesno=$request->gamesno;
		
      $gameids=$request->game_id;
      $number=$request->number;
		$datetime=now();
		
        //  DB::insert("INSERT INTO `admin_winner_results`( `gamesno`, `gameId`, `number`, `status`,`created_at`,`updated_at`) VALUES ('$gamesno','$gameids','$number','1','$datetime', '$datetime')");
         DB::table('admin_winner_results')->insert([
        'gamesno' => $gamesno,
        'gameId' => $gameids,
        'number' => $number,
        'status' => 1,
        'created_at' => $datetime,
        'updated_at' => $datetime,
    ]);
         
   
             return redirect()->back()->with('success', 'Winner result saved successfully!'); 
	}

// public function store(Request $request)
// {
//     // Validate input data
//     $request->validate([
//         'game_no' => 'required|integer',
//         'game_id' => 'required|integer',
//         'number' => 'required|string',  // Assuming 'number' is a string; adjust the type as necessary
//     ]);

//     // Retrieve the data from the request
//     $gamesno = $request->game_no;
//     $gameids = $request->game_id;
//     $number = $request->number;
//     $datetime = now();

//     // Use query builder with parameter binding to avoid SQL injection
//     DB::table('admin_winner_results')->insert([
//         'gamesno' => $gamesno,
//         'gameId' => $gameids,
//         'number' => $number,
//         'status' => 1,
//         'created_at' => $datetime,
//         'updated_at' => $datetime,
//     ]);

//     // Redirect back with a success message (optional)
//     return redirect()->back()->with('success', 'Winner result saved successfully!');
// }

  
   public function update(Request $request)
      {
	   $gamid=$request->id;
	
        $parsantage=$request->parsantage;
               $data= DB::select("UPDATE `game_settings` SET `winning_percentage` = '$parsantage' WHERE `id` ='$gamid'");
	         
         
             return redirect()->back();
          
      }
   
      

}
