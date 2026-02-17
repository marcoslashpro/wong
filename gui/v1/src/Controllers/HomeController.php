<?php

namespace App\Controllers;

use App\Models\NoteModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends BaseController
{
    function index(Request $req, Response $res)
    {
        $id = $req->getQueryParams()['note_id'];
        $note_model = new NoteModel($this->db);
        $notes = $note_model->fetchAll();
        if ($id) {
            $note = $note_model->fetchOne($id);
        } else {
            $note = $note_model->create("", "");
        }
        return $this->renderer->render($res, 'home.php', [
            "notes" => $notes,
            "note" => $note
        ]);
    }
}
