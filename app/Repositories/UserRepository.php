<?php

namespace TaskFlow\Repositories;

use PDO;
use TaskFlow\Models\User;
use TaskFlow\Core\RedisService;

class UserRepository
{

    private $pdo;
    private $redis;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->redis = new RedisService();
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
        $cacheKey = "user_email:" . $email;


        $cachedUser = $this->redis->get($cacheKey);

        if ($cachedUser !== null) {
            $userData = json_decode($cachedUser, true);
            return new User($userData);
        }

        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        // return $row ? new User($row) : null;


        //store inside redis
        if ($row) {
            $this->redis->set($cacheKey, json_encode($row), 600);
            return new User($row);
        }

        return null;
    }
}
