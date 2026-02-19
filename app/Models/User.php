<?php

namespace TaskFlow\Models;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public ?string $created_at;

    public function __construct(array $data)
    {
        $this->id = (int) ($data['id'] ?? 0);
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
    }
}
