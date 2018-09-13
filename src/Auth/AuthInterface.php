<?php

namespace SONFin\Auth;

interface AuthInterface
{
    public function login(array $credentials): bool;
    public function check(): bool;
    public function logout(): void;
}