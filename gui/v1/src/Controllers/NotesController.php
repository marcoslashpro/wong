<?php


namespace App\Controllers;

use App\Models\NoteModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class NotesController extends BaseController
{
    function store(Request $req, Response $res)
    {
        $note_model = new NoteModel($this->db);
        $new_note = $note_model->create("", "");

        return $res
            ->withHeader(
                'Location',
                "http://" . $_SERVER['SERVER_NAME']  . ":8000" . "/?note_id=" . $new_note['id']
            )->withStatus(302);
    }

    function update(Request $req, Response $res, array $args)
    {
        $id = $args['id'];
        $body = $req->getParsedBody();
        $note_model = new NoteModel($this->db);
        $note_model->update($id, $body['title'], $body['content']);
        return $res
            ->withHeader(
                'Location',
                "http://" . $_SERVER['SERVER_NAME']  . ":8000" . "/?note_id=" . $id
            )->withStatus(302);
    }

    function delete(Request $req, Response $res, array $args)
    {
        $id = $args['id'];
        $note_model = new NoteModel($this->db);
        $note_model->delete($id);
        return $res
            ->withHeader('HX-Push-Url', '/')
            ->withStatus(200);
    }
}
