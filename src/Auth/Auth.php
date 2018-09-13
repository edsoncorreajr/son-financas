<?php

namespace SONFin\Auth;

class Auth implements AuthInterface
{
    public function login(array $credentials): bool
    public function check(): bool
    public function logout(): void
}