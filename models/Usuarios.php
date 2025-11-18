<?php
// Esta es la plantilla para crear un objeto "Jugador".
class Usuarios
{
    private $id;
    private $username;
    private $avatar;
    private $total_games;
    private $wins;
    private $losses;
    private $created_at;

    // Cuando creamos un nuevo jugador, le damos un número, un nombre y una foto.
    public function __construct($id, $username, $avatar)
    {
        $this->id = $id;
        $this->username = $username;
        $this->avatar = $avatar;
        $this->total_games = 0;
        $this->wins = 0;
        $this->losses = 0;
        $this->created_at = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getTotalGames()
    {
        return $this->total_games;
    }

    public function setTotalGames($total_games)
    {
        $this->total_games = $total_games;
    }

    public function getWins()
    {
        return $this->wins;
    }

    public function setWins($wins)
    {
        $this->wins = $wins;
    }

    public function getLosses()
    {
        return $this->losses;
    }

    public function setLosses($losses)
    {
        $this->losses = $losses;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Si intentamos usar el jugador como texto, simplemente mostramos su nombre.
     */
    public function __toString()
    {
        return $this->getUsername();
    }
}
