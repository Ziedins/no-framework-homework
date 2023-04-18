<?php

class Controller
{
    public ?PDO $db = null;

    public ?Model $model = null;

    function __construct()
    {
        $this->openDatabaseConnection();
        $this->loadModel();
    }

    private function openDatabaseConnection(): void
    {

        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $this->db = new PDO(DB_TYPE . ':'. PATH_TO_DB, DB_USER, DB_PASS, $options);
    }

    public function loadModel(): void
    {
        require APP . 'model/model.php';
        $this->model = new Model($this->db);
    }
}
