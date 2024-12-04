<?php

// global $connection;
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/function.php");

if (isset($_POST["login"])) {
    $notification = login($_POST);
    if ($notification) {
        header("Location: /pages/sign-in.php?notification=" . urlencode($notification));
        exit;
    }
}

if (($_GET['page']) === 'logout') {
    logout();
}

function login($data)
{
    session_start();
    $username = $data["username"];
    $password = $data["password"];

    $result = query("SELECT users.*, roles.name as role_name 
        FROM users 
        JOIN roles 
        ON users.role_id = roles.id
        WHERE username = '$username'
    ");

    // Check if user exists
    if (count($result) > 0) {

        // Verify password
        if (password_verify($password, $result[0]["password"])) {
            // Set session data
            $_SESSION["login"] = true;
            $_SESSION["name"] = $result[0]["name"];
            $_SESSION["user_id"] = $result[0]["id"];
            $_SESSION["username"] = $result[0]["username"];
            $_SESSION["role_name"] = $result[0]["role_name"];


            // Redirect to the admin page with a success notification
            return "<script>
                Swal.fire({
                    title: 'Success',
                    text: 'Login successful!',
                    icon: 'success'
                }).then((result) => {
                    window.location.href = '/pages/dashboard.php';
                });
            </script>";
        } else {
            // Incorrect password
            return "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Incorrect password',
                    icon: 'error'
                }).then((result) => {
                    window.location.href = '/pages/sign-in.php';
                });
            </script>";
        }
    } else {
        // User not found
        return "<script>
            Swal.fire({
                title: 'Error',
                text: 'User not found',
                icon: 'error'
            }).then((result) => {
                window.location.href = '/pages/sign-in.php';
            });
        </script>";
    }

    return null; // No error if login is successful
}

function logout()
{
    // Clear session and cookies
    session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();

    setcookie('id', '', time() - 3600);
    setcookie('key', '', time() - 3600);

    // Redirect to login page
    header("Location: /pages/sign-in.php");
    exit;
}
