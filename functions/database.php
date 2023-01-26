<?php

/**
 * Vanaf php 8.2 kan je #[\SensitiveParameter] gebruiken bij paswoord
 * @param string $user
 * @param string $pass
 * @param string $db
 * @param string $host
 * @return PDO
 */
function dbConnect(string $user, string $pass, string $db, string $host = 'localhost'): PDO
{
    $connection = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);

    return $connection;
}

function addTodo(PDO $db, string $text): void
{
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    $res = $db->prepare('INSERT INTO todos (text) VALUES (:text)');
    $res->bindParam('text', $text);
    $res->execute();
}

function getTodos(PDO $db, bool $withTrashed = false): array
{
    if($withTrashed === false)
    {
        $res = $db->query('SELECT * FROM todos WHERE deleted_at IS NULL');
    }
    else
    {
        $res = $db->query('SELECT * FROM todos');
    }



    return $res->fetchAll();
}

function getTodoCount(PDO $db, int $done = null): int
{
    if($done === null)
    {
        $res = $db->prepare('SELECT count(*) FROM todos WHERE deleted_at IS NULL');
    }
    else
    {
        $res = $db->prepare('SELECT count(*) FROM todos WHERE done = :done and deleted_at IS NULL');
        $res->bindParam('done', $done);
    }
    $res->execute();


    return $res->fetchColumn();
}

function deleteTodo(PDO $db, int $id): void
{
    // $res = $db->prepare('DELETE FROM todos WHERE id = :id');
    // $res->bindParam('id', $id);
    // $res->execute();

    $now = date('Y-m-d H:i:s');

    $res = $db->prepare('UPDATE todos SET deleted_at = :now WHERE id = :id');
    $res->bindParam('id', $id);
    $res->bindParam('now', $now);
    $res->execute();
}

function checkTodo(PDO $db, int $id): void
{
    $now = date('Y-m-d H:i:s');

    $res = $db->prepare('UPDATE todos SET done = 1, updated_at = :now WHERE id = :id');
    $res->bindParam('id', $id);
    $res->bindParam('now', $now);
    $res->execute();
}

function unCheckTodo(PDO $db, int $id): void
{
    $now = date('Y-m-d H:i:s');

    $res = $db->prepare('UPDATE todos SET done = 0, updated_at = :now WHERE id = :id');
    $res->bindParam('id', $id);
    $res->bindParam('now', $now);
    $res->execute();
}

function getDoneTodos(PDO $db): int
{
    $res = $db->query('SELECT count(*) FROM todos WHERE done = 1 AND deleted_at IS NULL');

    return $res->fetchColumn();
}

function getNotDoneTodos(PDO $db): int
{
    $res = $db->query('SELECT count(*) FROM todos WHERE done = 0 AND deleted_at IS NULL');

    return $res->fetchColumn();
}
