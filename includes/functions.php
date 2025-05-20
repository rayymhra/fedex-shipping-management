<?php
function startSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function loginUser(int $id, string $role): void
{
    $_SESSION['user_id'] = $id;
    $_SESSION['role']    = $role;
}

function logoutUser(): void
{
    $_SESSION = [];
    session_destroy();
}

function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: auth.php');
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: ../login.php');
        exit;
    }
}
