<?php

namespace App\Controllers;

use PDO;
use Slim\Views\PhpRenderer;

class BaseController {
    protected $db;
    protected $renderer;

    function __construct()
    {
        $this->db = new PDO("sqlite:notes.db");
        $this->renderer = new PhpRenderer("./templates");
    }
}