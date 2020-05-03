<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\CardHand;

class PokerGameController extends Controller
{
    /**
     * Starts a poker round
     * It redirect to the win page
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function play()
    {      
        $player_1_id = Auth::id();
        $player_2_id = User::where('name','admin')->first()->id;
        
        $card_hand_player_1 = CardHand::where('user_id', $player_1_id)->get();
        $card_hand_player_2 = CardHand::where('user_id', $player_2_id)->get();
        
        foreach ($card_hand_player_1 as $round => $value){   
            $poker = new PokerController(
                            $card_hand_player_1[$round]->cards, 
                            $card_hand_player_2[$round]->cards, 
                            $card_hand_player_1[$round]->round_id, 
                            $player_1_id, 
                            $player_2_id
                        );
            $winner = $poker->pokerPlayRound();
            $poker->insertWin($winner);
        }
  
        return Redirect::to('win');
    }
    
    
    

    
    
}
