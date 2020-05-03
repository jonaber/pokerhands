<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Win;

class WinController extends Controller
{
    
    /**
     * Get the wins data from the database to be displayed in the result page
     * 
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getWins(){

        $player_1_id = Auth::id();
        $player_2_id = User::where('name','admin')->first()->id;

        $player_1_wins = Win::selectRaw('count(*) as player_1_wins')->where('user_id', $player_1_id)->get()[0]['player_1_wins'];
        $player_2_wins = Win::selectRaw('count(*) as player_2_wins')->where('user_id', $player_2_id)->get()[0]['player_2_wins'];
        
        $player_1_draws = Win::selectRaw('count(*) as player_1_draws')->where([
                                                                                ['user_id', $player_1_id],
                                                                                ['draw',1]
                                                                            ])->get()[0]['player_1_draws'];
        $player_2_draws = Win::selectRaw('count(*) as player_2_draws')->where([
                                                                                ['user_id', $player_2_id],
                                                                                ['draw',1]
                                                                            ])->get()[0]['player_2_draws'];
        
        $total_rounds = Win::selectRaw('count(*) as rounds')->get()[0]['rounds'];

        return view('result',[
                                'player_1_wins' => $player_1_wins,
                                'player_2_wins' => $player_2_wins,
                                'player_1_draws' => $player_1_draws,
                                'player_2_draws' => $player_2_draws,
                                'player_1_losses' => $total_rounds - $player_1_wins,
                                'player_2_losses' => $total_rounds - $player_2_wins,
                                'total_rounds' => $total_rounds
                            ]);
        
    }
}
