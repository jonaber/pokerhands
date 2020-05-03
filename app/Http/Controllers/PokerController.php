<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PokerController extends Controller
{
    public $hand_cards_player_1;
    public $hand_cards_player_2;
    public $round_id;
    public $player_1_id;
    public $player_2_id;
    
    // card ranking 
    public $card_rank = array(
                            'T' => '10',
                            'J' => '11',
                            'Q' => '12',
                            'K' => '13',
                            'A' => '14'
                        );
    
    // hand ranking points
    public $points = array(
                            'royalflush' => 180,
                            'straightflush' => 160,
                            'fours' => 140,
                            'fullhouse' => 120,
                            'flush' => 100,
                            'straight' => 80,
                            'threes' => 60,
                            'twopairs' => 40,
                            'onepair' => 20
                        );
    

    public function __construct($cards_player_1, $cards_player_2, $round_id, $player_1_id, $player_2_id)
    {
        $card_player_1_arr = explode(',', $cards_player_1);
        $card_player_2_arr = explode(',', $cards_player_2);
        
        // rank cards accordingly
        $ranked_cards_player_1 = ($this->rankCards($card_player_1_arr));
        $ranked_cards_player_2 = ($this->rankCards($card_player_2_arr));
        
        // order cards form highest rank to lower rank
        $this->hand_cards_player_1 = $this->orderByRank($ranked_cards_player_1);
        $this->hand_cards_player_2 = $this->orderByRank($ranked_cards_player_2);
        $this->player_1_id = $player_1_id;
        $this->player_2_id = $player_2_id;
        $this->round_id = $round_id;
    }
    
    
    /**
     * This function check for a Flush poker hand.
     * If Flush is found returns array having points, highest card 
     * rank and consecutive high card. If no Flush is found it returns false.
     * 
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isFlush($poker_hand_ranked)
    {
        $ordered_cards_suit = $this->orderBySuit($poker_hand_ranked) ;
        if ($ordered_cards_suit[0]['suit'] == $ordered_cards_suit[4]['suit']){
            return [$this->points['flush'], $poker_hand_ranked[0]['rank'], $poker_hand_ranked[1]['rank']];
        }
        
        return false;
    }
    
    
    /**
     * This function check for a Straight poker hand.
     * If Straight is found returns array having points, highest card 
     * rank and consecutive high card. If no Straight is found it returns false.
     * This function takes care of the five-high straight flush.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isStraight($poker_hand_ranked)
    {
        $ordered_cards = $this->orderByRank($poker_hand_ranked);

        // take care of five-high straight flush
        if ($ordered_cards[0]['rank'] == 14 &&
            $ordered_cards[1]['rank'] == 5 &&
            $ordered_cards[2]['rank'] == 4 &&
            $ordered_cards[3]['rank'] == 3 &&
            $ordered_cards[4]['rank'] == 2){
                    // change value of Ace to 1
                    $array = $ordered_cards[0];
                    $array['rank'] = 1;
                    array_shift($ordered_cards);
                    array_push($ordered_cards, $array);
                }
        
        $rank = $ordered_cards[0]['rank'];
        foreach ($ordered_cards as $card){
            if ($rank != $card['rank']){
                return false;
            }
            $rank--;
        }
        return [$this->points['straight'], $ordered_cards[0]['rank'], $ordered_cards[1]['rank']];
    }
    
    
    /**
     * This function check for a Straight Flush poker hand.
     * If Straight Flush is found returns array having points, highest card
     * rank and consecutive high card. If no Straight Flush is found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isStraightFlush($poker_hand_ranked)
    {
        if (!empty($this->isFlush($poker_hand_ranked)) && !empty($this->isStraight($poker_hand_ranked))){
            return [$this->points['straightflush'], $poker_hand_ranked[0]['rank'], $poker_hand_ranked[1]['rank']];
        }
        return 0;
    }
    
    
    /**
     * This function check for a Royal Flush poker hand.
     * If Royal Flush is found returns array having points, highest card
     * rank and consecutive high card. If no Royal Flush is found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isRoyalFlush($poker_hand_ranked)
    {
        if (!empty($this->isFlush($poker_hand_ranked)) && 
                !empty($this->isStraight($poker_hand_ranked)) && 
                    $poker_hand_ranked[0]['rank'] == 14){
            return [$this->points['royalflush'], 14, 13];
        }
        
        return 0;
    }
    
    
    /**
     * This function check for a Four of a kind in a poker hand.
     * If Four of a kind are found returns array having points, highest card
     * rank and consecutive high card. If no Four of a kind are found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isFours($poker_hand_ranked)
    {
        $arr = $this->sameRankCards($poker_hand_ranked);
        // since four of a kind is 4 out of 5 cards there can only be one repetition array
        // with repetition = 4
        if (!empty($arr) && count($arr) == 1 && $arr[0]['repetition'] == 4){
            $highest_remaining = $this->getHighestRemaining($poker_hand_ranked, $arr[0]['rank']);
            return [$this->points['fours'], $arr[0]['rank'], $highest_remaining[0]];
        }
        return 0;
    }
    
    
    /**
     * This function check for a Full House poker hand.
     * If Full House is found returns array having points, highest card
     * rank and consecutive high card. If no Full House is found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isFullHouse($poker_hand_ranked)
    {
        $arr = $this->sameRankCards($poker_hand_ranked);
        if (!empty($arr) && count($arr) == 2){
            if (($arr[0]['repetition'] == 3 || $arr[1]['repetition'] == 3) &&
                    ($arr[0]['repetition'] == 2 || $arr[1]['repetition'] == 2)){
                        return [$this->points['fullhouse'], $arr[0]['rank'], $arr[1]['rank']];
            }
        }
        return 0;
    }
    
    
    /**
     * This function check for a Three of a kind in a poker hand.
     * If Three of a kind are found returns array having points, highest card
     * rank and consecutive high card. If no Three of a kind are found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isThrees($poker_hand_ranked)
    {
        $arr = $this->sameRankCards($poker_hand_ranked);
        foreach ($arr as $rep){
            if ($rep['repetition'] == 3){
                $highest_remaining = $this->getHighestRemaining($poker_hand_ranked, $arr[0]['rank']);
                return [$this->points['threes'], $arr[0]['rank'], $highest_remaining[0]];
            }
        }
        return 0;
    }
    
    
    /**
     * This function check for a Two Pairs in a poker hand.
     * If Two Pairs are found returns array having points, highest card
     * rank and consecutive high card. If no Two Pairs are found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isTwoPairs($poker_hand_ranked) 
    {
        $arr = $this->sameRankCards($poker_hand_ranked);
        // since 2 pairs, array must have 2 repetitions with repetition = 2 in it
        if (!empty($arr) && count($arr) == 2 && $arr[0]['repetition'] == 2 && $arr[0]['repetition'] == 2){
            return [$this->points['twopairs'], $arr[0]['rank'], $arr[1]['rank']];
        }
        return 0;
    }
    
    
    /**
     * This function check for a One Pair in a poker hand.
     * If One Pair is found returns array having points, highest card
     * rank and consecutive high card. If no One Pair is found it returns false.
     *
     * @param array $poker_hand_ranked
     * @return array|boolean
     */
    public function isOnePair($poker_hand_ranked) 
    {
        $arr = $this->sameRankCards($poker_hand_ranked);
        if (!empty($arr) && count($arr) == 1){
            foreach ($arr as $rep){
                if ($rep['repetition'] == 2){
                    $highest_remaining = $this->getHighestRemaining($poker_hand_ranked, $arr[0]['rank']);
                    return [$this->points['onepair'], $arr[0]['rank'], $highest_remaining[0]];
                }
            }
        }
        return 0;
    }
    
    
    /**
     * Gets the remaining highest ranks from a hand not 
     * including the winning cards, just in case there is a tie.
     * 
     * @param array $poker_hand_ranked
     * @param integer $skip_rank
     * @return array $highest_ranks_remaining 
     */
    public function getHighestRemaining($poker_hand_ranked, $skip_rank)
    {
        $highest_ranks_remaining = [];
        
        foreach ($poker_hand_ranked as $card){
            if ($skip_rank != $card['rank']){
                $highest_ranks_remaining[] = $card['rank'];
            }
        }
        sort($highest_ranks_remaining);
        return $highest_ranks_remaining;
    }


    /**
     * Order the cards by rank.
     * By default the order is from high to low.
     * 
     * @param $array $poker_hand_ranked
     * @param string $order
     * @return $array $poker_hand_ranked
     */
    public function orderByRank($poker_hand_ranked, $order = 'DESC')
    {
        $rank_arr = array_column($poker_hand_ranked, 'rank');
        if($order == 'DESC'){
            array_multisort($rank_arr, SORT_DESC, $poker_hand_ranked);
        } else {
            array_multisort($rank_arr, SORT_ASC, $poker_hand_ranked);
        }
        
        return $poker_hand_ranked;
    }
    
    
    /**
     * Order the cards by rank.
     * By default the order is from high to low.
     *
     * @param $array $poker_hand_ranked
     * @param string $order
     * @return $array $ordered_poker_hand_ranked
     */
    public function orderBySuit($poker_hand_ranked, $order = 'DESC')
    {
        $suit_array = array_column($poker_hand_ranked, 'suit');
        if($order == 'DESC'){
            array_multisort($suit_array, SORT_DESC, $poker_hand_ranked);
        } else {
            array_multisort($suit_array, SORT_ASC, $poker_hand_ranked);
        }
        
        return $poker_hand_ranked;    
    }
    
    /**
     * Rank the cards by giving the cards an
     * actual number, removing the symbols T,Q,J,K,A
     * 
     * @param array $cards_hand
     * @return string[][]
     */
    public function rankCards($cards_hand)
    {
        $ranked_hand_cards = [];
        
        foreach($cards_hand as $cards)
        {
            $number = substr($cards,0,1);
            $rank = $this->card_rank[$number] ?? $number;
            $suit = substr($cards,1,1);
            $ranked_hand_cards[] = array('rank' => $rank, 'suit' => $suit);
        }
        
        return $ranked_hand_cards;
    }
    
    
    /**
     * It checks how many cards have the same rank are repeated in a hand
     * and created an array with the card rank and the number of repetitions
     * for that particular rank
     * 
     * @param array $poker_hand_ranked
     * @return array 
     */
    public function sameRankCards($poker_hand_ranked)
    {
        $ordered_cards = $this->orderByRank($poker_hand_ranked) ;
        $rank = $ordered_cards[0]['rank'];
        $same = [];
        $cnt = 0;

        foreach ($ordered_cards as $card){
            if ($rank == $card['rank']){
                $rank = $card['rank'];
                $cnt++; 
            } else {
                if($cnt > 1){
                    // insert repetition data of previous card
                    $same[] = array('rank'=>$rank, 'repetition'=>$cnt);
                }
                $rank = $card['rank'];
                $cnt = 1;
            }
        }
        
        // need to insert data of the last card since if it is the 
        // same as the previous one it will not be inserted in the loop
        if($cnt > 1){
            $same[] = array('rank'=>$rank, 'repetition'=>$cnt);
        }
        
        return $same;
    }
    
    /**
     * This function takes care of playing the poker hand round
     * It uses the cards from both players, and round id 
     * set in the class by the constructor
     * 
     * @return array $winner
     */
    public function pokerPlayRound()
    {
        $player_1_result = $this->pokerHandCardsEvaluation($this->hand_cards_player_1);
        $player_2_result = $this->pokerHandCardsEvaluation($this->hand_cards_player_2);
        $round_id = $this->round_id;
        
        return $this->pokerPlayWinResult($player_1_result, $player_2_result, $round_id);
    }
    
    
    /**
     * This function takes care to check the hands, and sets points accordingly
     * It returns an array containing the points, highest card, and the consecutive highest card
     * 
     * @param array $poker_hand_ranked
     * @return array points
     */
    public function pokerHandCardsEvaluation($poker_hand_ranked)
    {
        if (!empty( $royal_flush_result = $this->isRoyalFlush($poker_hand_ranked))){
            return $royal_flush_result;
        } elseif (!empty($straight_flush_result = $this->isStraightFlush($poker_hand_ranked))){
            return $straight_flush_result;
        } elseif (!empty($fours_result = $this->isFours($poker_hand_ranked))){
            return $fours_result;
        } elseif (!empty($full_house_result = $this->isFullHouse($poker_hand_ranked))){
            return $full_house_result;
        } elseif (!empty($flush_result = $this->isFlush($poker_hand_ranked))){
            return $flush_result;
        } elseif (!empty($straight_result = $this->isStraight($poker_hand_ranked))){
            return $straight_result;
        } elseif (!empty($poker_hand_result = $this->isThrees($poker_hand_ranked))){
            return $poker_hand_result;
        } elseif (!empty($two_pairs_result = $this->isTwoPairs($poker_hand_ranked))){
            return $two_pairs_result;
        } elseif (!empty($one_pair_result = $this->isOnePair($poker_hand_ranked))){
            return $one_pair_result;
        } else {
            return [0, $poker_hand_ranked[0]['rank'], $poker_hand_ranked[1]['rank']];
        }   
    }
    
    
    /**
     * Check who has won from the result points of the two players.
     * In case there is a tie, it will continue to check the consecutive numbers
     * until there is a win.
     * The winner is inserted into the databse.
     * 
     * @param array $player_1_result
     * @param array $player_2_result
     * @return array $winner
     */
    public function pokerPlayWinResult($player_1_result, $player_2_result)
    {
        $round_id = $this->round_id;
        $player_1_id = $this->player_1_id;
        $player_2_id = $this->player_2_id;
        $winner = [];
        
        foreach($player_1_result as $result=>$value){
            if($value > $player_2_result[$result]){
                $winner[] = array(
                                    'player_id' => $player_1_id, 
                                    'round_id' => $round_id, 
                                    'draw' => 0            
                                );
                return $winner;
            } elseif ($value < $player_2_result[$result]) {
                $winner[] = array(
                                    'player_id' => $player_2_id,
                                    'round_id' => $round_id,
                                    'draw' => 0
                                );
                return $winner;
            } else {
                $draw = true;
            }
        }
   
        // Since draw insert both players as winners, with flag draw true
        $winner[] = array(
                            'player_id' => $player_1_id,
                            'round_id' => $round_id,
                            'draw' => $draw
                        );
        $winner[] = array(
                            'player_id' => $player_2_id,
                            'round_id' => $round_id,
                            'draw' => $draw
                        );
        return $winner; 
    }
    
    /**
     * Inserts Win in the database, in the win table
     * 
     * @param integer $player_id
     * @param integer $round_id
     * @param string $draw
     * @return boolean true
     */
    //public function insertWin($player_id, $round_id, $draw='0')
    public function insertWin($winner)
    {
        $table = DB::table('wins');
        $time = date('Y-m-d H:i:s');
        
        foreach ($winner as $win){
            $table->insert(
                [
                        'user_id' => $win['player_id'],
                        'round_id' => $win['round_id'],
                        'draw' => $win['draw'],
                        'inserted_at' => $time
                ]
            );
        }
        return true;
    }
    
}
