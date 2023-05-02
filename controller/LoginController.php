<?php

require_once _ROOT_CONTROLLER . 'handleSanitize.php';
require_once _ROOT_CONTROLLER . 'ViewRenderer.php';
require_once _ROOT_MODEL . 'conexion.php';

class LoginController extends handleSanitize
{
    private $view;
    public function __construct()
    {

        $this->view = new ViewRenderer();
    }
    public function showLoginForm()
    {
        if (isset($_COOKIE['user'])) {
            $username = $_COOKIE['user'];
            $conexion = new MySQLConnection();
            $sqlSentences = "SELECT user_name, estado, tipo_usuario from usuarios where user_name =  ? ";
            $arrayParams = [$username];
            $consulta  = $conexion->query($sqlSentences, $arrayParams, '', false);
            $ResultadoConsulta = $consulta->fetchAll();
            foreach ($ResultadoConsulta as $columna) {
                $estadoUsuario = $columna['estado'];
                $tipoUsuario = $columna['tipo_usuario'];
            }
            if ($estadoUsuario === '0') {
                setcookie("user", "", time() - 3600);
                $conexion->close();
                $this->view->render('login', '', false);
                exit;
            }
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['tipoUser'] = $tipoUsuario;
            $conexion->close();
            header('Location: /administrador/app');
        }
        $this->view->render('login', '', false);
        return;
    }

    public function processLoginForm()
    {
        $username = $this->SanitizeVar($_POST['username']) ?? '';
        $password = $this->SanitizeVar($_POST['password']) ?? '';

        $conexion = new MySQLConnection();
        $sqlSentences = "SELECT user_name, contrasena, tipo_usuario FROM usuarios WHERE user_name = ? ";
        $arrayParams = [$username];
        $consulta  = $conexion->query($sqlSentences, $arrayParams, '', false);
        $ResultadoConsulta = $consulta->fetchAll();

        if (count($ResultadoConsulta) == 0) {
            $response = array('status'=>'error', 'message' => 'Puede que no exista el usuario');
            echo json_encode($response);
        } else {
            foreach ($ResultadoConsulta as $columna) {
                $usernameToCompare = $columna['user_name'];
                $passwordToCompare = $columna['contrasena'];
                $userTipo = $columna['tipo_usuario'];
            }
            if (!empty($passwordToCompare) && password_verify($password, $passwordToCompare) &&  $username == $usernameToCompare) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['tipoUser'] = $userTipo;
                setcookie("user", $username, time() + (90 * 24 * 60), "/");
                $conexion->close();
                $response = array('status'=>'success', 'redirect' => '/administrador/app');
                echo(json_encode($response));
                exit;
            } else {
                $conexion->close();
                $response = array('status'=>'error', 'message' => 'Usuario o contraseña incorrectos');
                echo(json_encode($response));
            }
        }
    }
    
    protected function SanitizeVar(string $var)
    {
        $var = htmlspecialchars( $var,  ENT_QUOTES);
        $var = preg_replace('/[^a-zA-Z0-9.=+-_@^]/', 'a', $var);
        return $var;
    }
}