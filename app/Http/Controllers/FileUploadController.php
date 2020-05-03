<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class FileUploadController extends Controller
{
    /**
     * Takes care of saving the file into app/uploads folder,
     * and calls the parser for the file to parsed and inserted into the 
     * database.
     * 
     * TODO Extra file validations
     * 
     * @return string
     */
    public function upload()
    {
        request()->file('cardhand')->storeAs('uploads/', 'cardhands.txt');
        $this->fileParser();

        return back()
        ->withSuccess('The file has been uploaded and now you can start to play.');
    }    


    /**
     * Parses the file to split into two hands and inserts each hand to the
     * respective player.
     * 
     * TODO Extra parsing validations to check that file can be used correctly 
     * 
     * @return boolean
     */
    public function fileParser()
    {
        $txt_file = File::get(base_path() . '\storage\app\uploads\cardhands.txt');
        $lines = explode("\n", $txt_file);
        $hands_player_1 = [];
        $hands_player_2 = [];
        
        foreach($lines as $line)
        {
            if(!empty($line)){
                $row_data = explode(' ', $line); 
                $hands_player_1[] = "$row_data[0],$row_data[1],$row_data[2],$row_data[3],$row_data[4]";
                $hands_player_2[] = "$row_data[5],$row_data[6],$row_data[7],$row_data[8],$row_data[9]";
            }
        }
       
        $user_1_id = Auth::id();
        $user_2_id = User::where('name','admin')->first()->id;

        $this->insertToDatabase($hands_player_1, $user_1_id, $hands_player_2, $user_2_id);
        
        return true;
        
    }
    

    /**
     * Inserts the cards for each player in the card_hands table
     * 
     * @param array $hands_player_1
     * @param integer $user_1_id
     * @param array $hands_player_2
     * @param integer $user_2_id
     * @return boolean
     */
    public function insertToDatabase($hands_player_1, $user_1_id, $hands_player_2, $user_2_id)
    {
        $round_id = 0;
        $table = DB::table('card_hands');
        $time = date('Y-m-d H:i:s');
        
        foreach ($hands_player_1 as $hand=>$value){
            $round_id++;
            
            $table->insert(
                [
                    'user_id' => $user_1_id,
                    'cards' => $value,
                    'round_id' => $round_id,
                    'inserted_at' => $time,
                ]
            );
            
            $table->insert(
                [
                    'user_id' => $user_2_id,
                    'cards' => $hands_player_2[$hand],
                    'round_id' => $round_id,
                    'inserted_at' => $time,
                ]
            );
        }
        
        return true;
    }

}

