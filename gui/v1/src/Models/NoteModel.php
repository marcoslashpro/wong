<?php

namespace App\Models;

use Exception;
use PDO;

class NoteModel
{
    private PDO $db;

    function __construct(mixed $db)
    {
        $this->db = $db;
    }

    function create(string $title, ?string $content)
    {
        $query = $this->db->prepare(
            "INSERT INTO note (id, updated_at, title, content) VALUES (?, ?, ?, ?)"
        );
        $id = uniqid();
        $query->execute(
            [
                $id,
                date('Y-F-d'),
                $title,
                $content ? $content : ""
            ]
        );
        return $this->fetchOne($id);
    }

    function update(string $id, string $title, string $content) {
        $query = $this->db->prepare(
            "UPDATE note SET title = ?, content = ? WHERE id = ?"
        );
        $query->execute([$title, $content, $id]);
    }

    function delete(string $id) {
        $query = $this->db->prepare("DELETE FROM note WHERE id = ?");
        $query->execute([$id]);
    }

    function fetchOne(string $id) {
        $query = $this->db->prepare("SELECT * FROM note WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_DEFAULT);
    }

    function fetchAll() {
        $query = $this->db->prepare("SELECT * FROM note");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_DEFAULT);
    }
}
