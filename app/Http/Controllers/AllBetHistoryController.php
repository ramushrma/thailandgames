<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Bet,AviatorBet,PlinkoBet};
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AllBetHistoryController extends Controller
{
// 	public function all_bet_history(string $id){
// 		$perPage = 100;
// 		if($id==1||$id==2||$id==3||$id==4||$id==6||$id==7||$id==8||$id==9||$id==10){
// 			$color = DB::table('bets')
// 				->select('bets.*', 'users.name as username')
// 				->leftJoin('users', 'users.id', '=', 'bets.userid')
// 				->where('bets.game_id', $id)
// 				->orderByDesc('bets.id')
// 				->paginate($perPage);

			
// 			$color->getCollection()->transform(function ($item) {
// 				switch ($item->status) {
// 					case 1:
// 						$item->status = 'win';
// 						break;
// 					case 2:
// 						$item->status = 'loss';
// 						break;
// 					case 0:
// 						$item->status = 'pending';
// 						break;
// 				}
// 				return $item;
// 			});
// 			$color->getCollection()->transform(function ($val) {
// 				switch ($val->number) {
// 					case 10:
// 						$val->number = 'Green';
// 						break;
// 					case 20:
// 						$val->number = 'Voilet';
// 						break;
// 					case 30:
// 						$val->number = 'Red';
// 						break;
// 					case 40:
// 						$val->number = 'Big';
// 						break;
// 					case 50:
// 						$val->number = 'Small';
// 						break;
// 				}
// 				return $val;
// 			});
// 			$total_bet = DB::table('bets')->where('game_id',$id)->count('id');
// 			return view('All_bet_history.color')->with('bets',$color)->with('total_bet',$total_bet);   
// 		}elseif($id==5){
// 			$color = DB::table('aviator_bets')->select('aviator_bets.*','users.name as username')->leftJoin('users','users.id', '=', 'aviator_bets.uid')->where('aviator_bets.game_id',$id)->orderBydesc('aviator_bets.id')->paginate($perPage);
// 			$color->getCollection()->transform(function ($item) {
// 				switch ($item->status) {
// 					case 1:
// 						$item->status = 'win';
// 						break;
// 					case 2:
// 						$item->status = 'loss';
// 						break;
// 					case 0:
// 						$item->status = 'pending';
// 						break;
// 				}
// 				return $item;
// 			});
// 			$total_bet = DB::table('aviator_bets')->where('game_id',$id)->count('id');
// 			return view('All_bet_history.aviator')->with('bets',$color)->with('total_bet',$total_bet); 
// 		}elseif($id==11){
// 			$color = DB::table('plinko_bet')->where('game_id',$id)->orderBydesc('id')->paginate($perPage);
// 			$color->getCollection()->transform(function ($item) {
// 				switch ($item->type) {
// 					case 1:
// 						$item->type = 'Green';
// 						break;
// 					case 2:
// 						$item->type = 'yellow';
// 						break;
// 					case 3:
// 						$item->type = 'red';
// 						break;
// 				}
// 				return $item;
// 			});
// 			$total_bet = DB::table('plinko_bet')->where('game_id',$id)->count('id');
// 			return view('All_bet_history.plinko')->with('bets',$color)->with('total_bet',$total_bet);   
// 		}

// 	}
<<<<<<< HEAD
//   code jab permison lagaya gya 
// public function all_bet_history(string $id)
// {
    
//     $perPage = 100;
    
//     if (in_array($id, [1, 2, 3, 4, 6, 7, 8, 9, 10,13])) {
//         $bets = Bet::with('user:name,id')
//             ->where('game_id', $id)
//             ->orderByDesc('id')
//             ->paginate($perPage);

//         // Transform status and number fields
//         $bets->getCollection()->transform(function ($item) {
//             $item->status = match($item->status) {
//                 1 => 'win',
//                 2 => 'loss',
//                 0 => 'pending',
//                 default => $item->status
//             };
            
//             $item->number = match($item->number) {
//                 10 => 'Green',
//                 20 => 'Voilet',
//                 30 => 'Red',
//                 40 => 'Big',
//                 50 => 'Small',
//                 default => $item->number
//             };

//             return $item;
//         });

//         $total_bet = Bet::where('game_id', $id)->count();
//         return view('All_bet_history.color', compact('bets', 'total_bet'));
//     } elseif ($id == 5) {
//         $bets = AviatorBet::with('user:name,id')
//             ->where('game_id', $id)
//             ->orderByDesc('id')
//             ->paginate($perPage);

//         // Transform status field
//         $bets->getCollection()->transform(function ($item) {
//             $item->status = match($item->status) {
//                 1 => 'win',
//                 2 => 'loss',
//                 0 => 'pending',
//                 default => $item->status
//             };

//             return $item;
//         });

//         $total_bet = AviatorBet::where('game_id', $id)->count();
//         return view('All_bet_history.aviator', compact('bets', 'total_bet'));
//     } elseif ($id == 11) {
//         $bets = PlinkoBet::where('game_id', $id)
//             ->orderByDesc('id')
//             ->paginate($perPage);

//         // Transform type field
//         $bets->getCollection()->transform(function ($item) {
//             $item->type = match($item->type) {
//                 1 => 'Green',
//                 2 => 'yellow',
//                 3 => 'red',
//                 default => $item->type
//             };

//             return $item;
//         });

//         $total_bet = PlinkoBet::where('game_id', $id)->count();
//         return view('All_bet_history.plinko', compact('bets', 'total_bet'));
//     }
// }
public function all_bet_history(Request $request, string $id)
{
    $authuser = $request->session()->get('authuser');
    $auth_role = $authuser->role_id;
    $auth_uid = $authuser->id;
    
    $perPage = 100;

    $query = null;

    if (in_array($id, [1, 2, 3, 4, 6, 7, 8, 9, 10, 13])) {
        $query = Bet::with('user:name,id')->where('game_id', $id);
    } elseif ($id == 5) {
        $query = AviatorBet::with('user:name,id')->where('game_id', $id);
    } elseif ($id == 11) {
        $query = PlinkoBet::where('game_id', $id);
    }

    if ($query) {
        if ($auth_role == 2) {
            $query->whereHas('user', function ($q) use ($auth_uid) {
                $q->where('admin_id', $auth_uid)
                  ->orWhere('id', $auth_uid)
                  ->orWhereIn('vendor_id', function ($sub) use ($auth_uid) {
                      $sub->select('id')->from('users')->where('admin_id', $auth_uid);
                  });
            });
        } elseif ($auth_role == 3) {
            $query->whereHas('user', function ($q) use ($auth_uid) {
                $q->where('vendor_id', $auth_uid);
            });
        }

        $bets = $query->orderByDesc('id')->paginate($perPage);

=======

public function all_bet_history(string $id)
{
    $perPage = 100;
    
    if (in_array($id, [1, 2, 3, 4, 6, 7, 8, 9, 10])) {
        $bets = Bet::with('user:name,id')
            ->where('game_id', $id)
            ->orderByDesc('id')
            ->paginate($perPage);

        // Transform status and number fields
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        $bets->getCollection()->transform(function ($item) {
            $item->status = match($item->status) {
                1 => 'win',
                2 => 'loss',
                0 => 'pending',
                default => $item->status
            };

<<<<<<< HEAD
            if (isset($item->number)) {
                $item->number = match($item->number) {
                    10 => 'Green',
                    20 => 'Voilet',
                    30 => 'Red',
                    40 => 'Big',
                    50 => 'Small',
                    default => $item->number
                };
            }

            if (isset($item->type)) {
                $item->type = match($item->type) {
                    1 => 'Green',
                    2 => 'yellow',
                    3 => 'red',
                    default => $item->type
                };
            }
=======
            $item->number = match($item->number) {
                10 => 'Green',
                20 => 'Voilet',
                30 => 'Red',
                40 => 'Big',
                50 => 'Small',
                default => $item->number
            };
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263

            return $item;
        });

<<<<<<< HEAD
        $total_bet = $query->count();

        if (in_array($id, [1, 2, 3, 4, 6, 7, 8, 9, 10, 13])) {
            return view('All_bet_history.color', compact('bets', 'total_bet'));
        } elseif ($id == 5) {
            return view('All_bet_history.aviator', compact('bets', 'total_bet'));
        } elseif ($id == 11) {
            return view('All_bet_history.plinko', compact('bets', 'total_bet'));
        }
    }

    abort(404);
}
=======
        $total_bet = Bet::where('game_id', $id)->count();
        return view('All_bet_history.color', compact('bets', 'total_bet'));
    } elseif ($id == 5) {
        $bets = AviatorBet::with('user:name,id')
            ->where('game_id', $id)
            ->orderByDesc('id')
            ->paginate($perPage);

        // Transform status field
        $bets->getCollection()->transform(function ($item) {
            $item->status = match($item->status) {
                1 => 'win',
                2 => 'loss',
                0 => 'pending',
                default => $item->status
            };

            return $item;
        });

        $total_bet = AviatorBet::where('game_id', $id)->count();
        return view('All_bet_history.aviator', compact('bets', 'total_bet'));
    } elseif ($id == 11) {
        $bets = PlinkoBet::where('game_id', $id)
            ->orderByDesc('id')
            ->paginate($perPage);

        // Transform type field
        $bets->getCollection()->transform(function ($item) {
            $item->type = match($item->type) {
                1 => 'Green',
                2 => 'yellow',
                3 => 'red',
                default => $item->type
            };

            return $item;
        });

        $total_bet = PlinkoBet::where('game_id', $id)->count();
        return view('All_bet_history.plinko', compact('bets', 'total_bet'));
    }
}

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
	
}