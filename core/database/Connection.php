<?php

require_once __DIR__ . '/DatabaseContract.php';

/**
 * Database Connection
 */
class Connection implements DatabaseContract
{
    /**
     * PDO instance
     * @var type
     **/
    private $pdo;
    private $database;
    /**
     * connect to the SQLite database
     **/
    public function __construct(String $database)
    {
        $this->database = $database;
    }
    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return PDO
     **/
    public function connect(): object
    {
        try {
            $this->pdo = new PDO($this->database);
        } catch (PDOException $e) {
            // handle the exception here
        }
        return $this->pdo;
    }
}
