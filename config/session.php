<?php
session_start();

class SessionManager {
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function login($user_id, $email) {
        self::startSession();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
    }
    
    public static function logout() {
        self::startSession();
        session_destroy();
        session_unset();
    }
    
    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login.php');
            exit();
        }
    }
    
    public static function getUserId() {
        self::startSession();
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    public static function getEmail() {
        self::startSession();
        return isset($_SESSION['email']) ? $_SESSION['email'] : null;
    }
}
?>
