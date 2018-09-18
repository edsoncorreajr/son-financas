<?php
declare(strict_types=1);

namespace SONFin\Models;

use SONFin\Models\UserInterface;
use Jasny\Auth\User as UserJasny;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements UserJasny, UserInterface
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    public function getId(): int
    {
        return (int) $this->id;
    }
    
    public function getUsername(): string
    {
        return $this->email;
    }

    public function getFullname(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getEmail(): string
    {
        return $this-email;
    }

    public function gePassword(): string
    {
        return $this->password;
    }

    public function getHashedPassword(): string
    {
        return $this->password;
    }

    public function onLogin()
    {
    }

    public function onLogout()
    {
    }
}
