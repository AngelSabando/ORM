<?php

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    $password = $_POST['password'] ?? ''; 
    
    $lastname = trim($_POST['lastname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $ruc = trim($_POST['ruc'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $response['message'] = 'Por favor, rellena los campos obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'El formato del email no es válido.';
    } else {
        try {
            $existingUser = User::where('email', $email)->first();
            
            if ($existingUser) {
                $response['message'] = 'Este email ya está registrado.';
            } else {
                $user = new User;
                $user->name = $name;
                $user->email = $email;
                $user->password = $password;
                $user->lastname = $lastname;
                $user->username = $username;
                $user->ruc = $ruc;

                if ($user->save()) {
                    $response['status'] = 'success';
                    $response['message'] = '¡Usuario registrado correctamente!';
                } else {
                    $response['message'] = 'Error al guardar en la base de datos.';
                }
            }
        } catch (\Exception $e) {
            $response['message'] = 'Error del servidor: ' . $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>