<?php declare(strict_types=1);

require_once (_ROOT_CONTROLLER . 'handleSanitize.php');
require_once (_ROOT_CONTROLLER . 'ViewRenderer.php');
require_once (_ROOT_CONTROLLER . 'AdministrarArchivos.php');
require_once (_ROOT_MODEL . 'conexion.php' );

class RegistroCapacitacion extends ViewRenderer 
{
    private $conexion; 
    private $handleLog;

    public function __construct()
    {
        $this->conexion = new MySQLConnection();
        $this->handleLog = new handleSanitize();        
    }

    public function showRegistro(): void 
    {
        $viewRender = new ViewRenderer();
        $data = [
            "imgFoto" => _ROOT_ASSETS . 'img/CARNET.png' , 
            "listCursos" => $this->listCursos(),
            "listOficina" => $this->listOficinas()
        ];
        $viewRender->render('RegistroCapacitacion', $data, false);
    }

    private function listCursos(): string
    {
        $sql = "SELECT * FROM cursos";
        $stmt = $this->conexion->query($sql, '', false);
        $resultado = $stmt->fetchAll();
        $listCursos = '';
        foreach ($resultado as $row) {
            $listCursos .= <<<Html
            <option value="{$row['id_curso']}">{$row['nombre']}</option>
            Html;
        }
        return $listCursos;
    }

    private function listOficinas (): string
    {
        $sql = "SELECT id, CONCAT(nombre, ' ', sigla ) as nombre From oficinas";
        $stmt = $this->conexion->query($sql, '', false);
        $resultado = $stmt->fetchAll();
        $listOficinas = '';
        foreach ($resultado as $row) {
            $listOficinas .= <<<Html
            <option value="{$row['id']}">{$row['nombre']}</option>
            Html;
        }
        return $listOficinas;
    }

    public function datosInscripcion(): void
    {
        $camposRequeridos = ['dni', 'nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo', 'celular', 'oficina', 'cargo', 'cursos'];
        foreach ($camposRequeridos as $campo) {
            if (empty ($_POST[$campo])) {
                $respuesta = array('status'=>'error', 'message'=>"El campo $campo es necesario. Vuelva a registrar");
                echo (json_encode($respuesta));
                return;
            }
        }

        if ( empty($_FILES['archivo'])) {
            $respuesta = array('status'=>'error', 'message'=>"Debe enviar una foto.");
            echo (json_encode($respuesta));
            return;
        }

        try {
            $gestorArchivo = new AdministrarArchivos($this->conexion, 'fotos/');
            $archivo = $_FILES['archivo'];
            if ($gestorArchivo->validarArchivo($archivo, ['png', 'jpg', 'gif', 'jfif', 'jpeg', 'webp', 'bmp']) == false) {
                return;
            }

            $declararCampos = ['dni', 'nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo', 'celular', 'oficina', 'cargo'];
            foreach ($declararCampos as $campo) {
                $$campo = $this->handleLog->SanitizeVarInput($_POST[$campo]);
            }
            $cursos = $_POST['cursos'];

            $nameFile = $gestorArchivo->guardarFichero($archivo, $dni);
            $this->comprobarRegistroPersona($nombre, $apellidoPaterno, $apellidoMaterno, $dni, $correo, $celular, $nameFile, $oficina, $cargo);
            foreach($cursos as $id_curso) {
                if ($this->comprobarInscripcion($dni, $id_curso) == true) {
                    $this->insertInscriptcion($dni, $id_curso);
                } else {
                    return;
                }
            }
            $gestorArchivo->redimencionarFoto ($nameFile);
            $respuesta = array ('status'=>'succes', 'message'=>'Registro guardado de manera exitosa.');
            echo (json_encode($respuesta));
        } catch (Throwable $e) {
            $this->handleLog->handlerError($e);
        }
        return;
    }

    private function comprobarRegistroPersona($nombre, $apellidoPaterno, $apellidoMaterno, $dni, $correo, $celular, $foto, $id_oficina, $cargo): void
    {
        $sql ="SELECT id_persona FROM personas WHERE dni= :dni";
        $params = [$dni];
        $stmt = $this->conexion->query($sql, $params, '', false);
        $respuesta = $stmt->fetchColumn();
        if ($respuesta == false) {
            $this->insertPersonal($nombre, $apellidoPaterno, $apellidoMaterno, $dni, $correo, $celular, $foto, $id_oficina, $cargo);
        }
        return;
    }

    private function insertPersonal($nombre, $apellidoPaterno, $apellidoMaterno, $dni, $correo, $celular, $foto, $id_oficina, $cargo): void
    {
        $sql = "INSERT INTO personas (
            nombre, 
            apellidoPaterno, 
            apellidoMaterno, 
            dni, 
            correo, 
            celular, 
            foto, 
            id_oficina, 
            cargo )  VALUES (?,?,?,?,?,?,?,?,?)";
        $params = [$nombre, $apellidoPaterno, $apellidoMaterno, $dni, $correo, $celular, $foto, $id_oficina, $cargo];
        $this->conexion->query($sql, $params, '',false);
        return;        
    }

    private function comprobarInscripcion($dni, $id_curso): bool
    {
        $sql= "SELECT id_inscripcion FROM inscripciones  WHERE dni = :dni AND id_curso = :id_curso";
        $params = [$dni, $id_curso];
        $stmt = $this->conexion->query($sql, $params, '', false);
        $resultado = $stmt->fetchColumn();
        if($resultado == false) {  
            return true;
        }
        $sql_curso = "SELECT nombre FROM Cursos WHERE id_curso = $id_curso ";
        $stmt_curso = $this->conexion->query($sql_curso, '', '', false);
        $resultado_curso = $stmt_curso->fetchColumn();
        $respuesta = array('status' => 'error', 'message'=>'Ya se encuentra inscrito en el curso '.$resultado_curso.'; desmarque para continuar.');
        echo (json_encode($respuesta));
        return false;
    }

    private function insertInscriptcion ($dni, $id_curso): void 
    {
        $fecha = date('Y-m-d');
        $sql_id_persona = "SELECT id_persona FROM personas WHERE dni = :dni";
        $param_id_persona = [$dni];
        $stmt = $this->conexion->query($sql_id_persona, $param_id_persona, '', false);
        $resultado = $stmt->fetchColumn();

        $sql = "INSERT INTO inscripciones (id_persona, id_curso, dni, fecha ) VALUES (?,?,?,?)";
        $params = [$resultado, $id_curso, $dni, $fecha];
        $this->conexion->query($sql, $params, '',false);
        return; 
    } 
}