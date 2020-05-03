<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\PokerController;

class PokerHandValidationTest extends TestCase
{
    // Test cards for player 1
    public $testCards_p1 = array (
                'royal_flush' => 'TH,JH,QH,KH,AH',
                'straight_flush' => '5C,6C,7C,8C,9C',
                'fours' => 'KH,6H,KC,KS,KD',
                'full_house' => 'TH,JC,JD,TC,TD',
                'flush' => '2D,7D,5D,JD,KD',
                'straight' => '5D,6H,4H,7C,8S',
                'threes' => '5D,3H,9S,5C,5H',
                'two_pairs' => '7H,7S,4S,9H,4S',
                'one_pair' => 'TD,7S,5H,2H,5D',
                'nothing' => '4H,TS,7S,AH,5C',
                'high_five_straight' => '5H,4C,2S,3H,AH'
    );
    
    // Test cards for player 2
    public $testCards_p2 = array (
                'royal_flush' => 'TH,JH,QH,KH,AH',
                'straight_flush' => '5C,6C,7C,8C,9C',
                'fours' => 'KH,6H,KC,KS,KD',
                'full_house' => 'TH,JC,JD,TC,TD',
                'flush' => '2D,7D,5D,JD,KD',
                'straight' => '5D,6H,4H,7C,8S',
                'threes' => '5D,3H,9S,5C,5H',
                'two_pairs' => '7H,7S,4S,9H,4S',
                'one_pair' => 'TD,7S,5H,2H,5D',
                'nothing' => '4H,9S,7S,AH,5C',
                'high_five_straight' => '5H,4C,2S,3H,AH'
    );
    
    
    // Fake ids used only for testing to create constructor
    public $player_1_id = 1;
    public $player_2_id = 2;
    public $round_id = 1;
    
    
   
    /**
     * Test Royal flush poker hand points.
     *
     * @return void
     */
    public function testIsRoyalFlush()
    {
        $cards = $this->testCards_p1['royal_flush'];      
        $poker = new PokerController(
                        $cards,
                        $cards,
                        $this->round_id,
                        $this->player_1_id,
                        $this->player_2_id
                );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);    
        $this->assertContains(180, $result);
    }
    
    
    
    /**
     * Test straight flush poker hand points.
     *
     * @return void
     */
    public function testIsStraightFlush()
    {
        $cards = $this->testCards_p1['straight_flush'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(160, $result);
    }
    
    
    /**
     * Test four of a kind points.
     *
     * @return void
     */
    public function testIsFours()
    {
        $cards = $this->testCards_p1['fours'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(140, $result);
    }
    
    
    /**
     * Test full house poker hand points.
     *
     * @return void
     */
    public function testIsFullHouse()
    {
        $cards = $this->testCards_p1['full_house'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(120, $result);
    }
    
    /**
     * Test flush poker hand points.
     *
     * @return void
     */
    public function testIsFlush()
    {
        $cards = $this->testCards_p1['flush'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(100, $result);
    }
    
    /**
     * Test straight poker hand points.
     *
     * @return void
     */
    public function testIsStraight()
    {
        $cards = $this->testCards_p1['straight'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(80, $result);
    }
    
    /**
     * Test Three of a kind points.
     *
     * @return void
     */
    public function testIsThrees()
    {
        $cards = $this->testCards_p1['threes'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(60, $result);
    }
    
    /**
     * Test two pairs points.
     *
     * @return void
     */
    public function testIsTwoPairs()
    {
        $cards = $this->testCards_p1['two_pairs'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(40, $result);
    }
    
    /**
     * Test one pair points.
     *
     * @return void
     */
    public function testIsOnePair()
    {
        $cards = $this->testCards_p1['one_pair'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(20, $result);
    }
    
    
    
    /**
     * Test no particular hand.
     *
     * @return void
     */
    public function testIsNothing()
    {
        $cards = $this->testCards_p1['nothing'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(0, $result);
    }
    
    
    /**
     * Test High Five Straight points.
     *
     * @return void
     */
    public function testIsHighFiveStraight()
    {
        $cards = $this->testCards_p1['high_five_straight'];
        $poker = new PokerController(
            $cards,
            $cards,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerHandCardsEvaluation($poker->hand_cards_player_1);
        $this->assertContains(80, $result);
    }
    
    
    /**
     * Test Player 1 wins.
     *
     * @return void
     */
    public function testPlayGamePlayer1Wins()
    {
        $cards_1 = $this->testCards_p1['straight_flush'];
        $cards_2 = $this->testCards_p1['full_house'];
        $poker = new PokerController(
            $cards_1,
            $cards_2,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerPlayRound();
        $this->assertEquals([[
            'player_id' => 1,
            'round_id' => 1,
            'draw' => 0
        ]], $result);
    }
    
    
    /**
     * Test Player 2 wins.
     *
     * @return void
     */
    public function testPlayGamePlayer2Wins()
    {
        $cards_1 = $this->testCards_p1['high_five_straight'];
        $cards_2 = $this->testCards_p2['straight'];
        $poker = new PokerController(
            $cards_1,
            $cards_2,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerPlayRound();  
        $this->assertEquals([[
                                'player_id' => 2,
                                'round_id' => 1,
                                'draw' => 0
                            ]], $result);
    }
    
    
    /**
     * Test Player 1 wins after draw.
     *
     * @return void
     */
    public function testPlayGamePlayerDrawWin()
    {
        $cards_1 = $this->testCards_p1['nothing'];
        $cards_2 = $this->testCards_p2['nothing'];
        $poker = new PokerController(
            $cards_1,
            $cards_2,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerPlayRound();
        $this->assertEquals([[
                                'player_id' => 1,
                                'round_id' => 1,
                                'draw' => 0
                            ]], $result);
    }
        
    
    /**
     * Test Player 2 wins after draw.
     *
     * @return void
     */
    public function testPlayGamePlayerDraw()
    {
        $cards_1 = $this->testCards_p1['high_five_straight'];
        $cards_2 = $this->testCards_p2['high_five_straight'];
        $poker = new PokerController(
            $cards_1,
            $cards_2,
            $this->round_id,
            $this->player_1_id,
            $this->player_2_id
            );
        
        $result = $poker->pokerPlayRound();
        $this->assertEquals([[
                                'player_id' => 1,
                                'round_id' => 1,
                                'draw' => 1
                            ], [
                                'player_id' => 2,
                                'round_id' => 1,
                                'draw' => 1
                            ]], $result);
    }
    

    

 
}
