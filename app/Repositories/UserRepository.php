<?php

namespace TaskFlow\Repositories;

use PDO;
use TaskFlow\Models\User;

class UserRepository
{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }



    //sign up or register
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO users (name, email, password)
        VALUES (:name, :email, :password)
    ");

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }



    //login
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? new User($row) : null;


    }
}
