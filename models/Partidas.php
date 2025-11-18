<?php

class Partidas{
    private $id;
    private $player_x_id;
    private $player_o_id;
    private $winner_id;
    private $board_history;
    private $start_time	;
    private $end_time;
    private $game_result;


    public function __construct($id,$player_x_id,$player_o_id,$winner_id,$board_history,$start_time,$end_time,$game_result){
        $this->id = $id;
        $this->player_x_id = $player_x_id;
        $this->player_o_id = $player_o_id;
        $this->winner_id = $winner_id;
        $this->board_history = $board_history;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->game_result = $game_result;
    }

    public function getId(){
        return $this->id;
    }

    public function getPlayerXId(){
        return $this->player_x_id;
    }
    
    public function getPlayerOId(){
        return $this->player_o_id;
    }

    public function getWinnerId(){
        return $this->winner_id;
    }

    public function getBoardHistory(){
        return $this->board_history;
    }

    public function getStartTime(){
        return $this->start_time;
    }

    public function getEndTime(){
        return $this->end_time;
    }

    public function getGameResult(){
        return $this->game_result;
    }
}