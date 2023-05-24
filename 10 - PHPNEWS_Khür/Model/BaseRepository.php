<?php

class BaseRepository
{
    protected $dbConn;

    public function __construct(Database $db)
    {
        $this->dbConn = $db;
    }
}

