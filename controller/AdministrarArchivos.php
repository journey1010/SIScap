<?php 
class AdministrarArchivos {

    private $conexion;
    private $ruta;

    public function __construct(MySQLConnection $conexion, $ruta) 
    {
        $this->conexion = $conexion;
        $this->ruta = _ROOT_FILES . $ruta;
    }

    public function validarArchivo ($archivo , array $extensionesPermitidas): bool
    {
        if (empty ($archivo)) {
            return false; 
        }
        $archivoNombre = $archivo['name'];
        $extension = strtolower(pathinfo($archivoNombre , PATHINFO_EXTENSION));

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            $respuesta = array ("status"=>"error", "message" => "Error al subir el archivo");
            print_r(json_encode($respuesta));
            return false;
        }

        if (!in_array($extension, $extensionesPermitidas)){
            $extensionMessage = implode(', ', $extensionesPermitidas);
            $respuesta = array ("status"=>"error", "message"=> "Extensión de archivo no permitida. Solo se permiten archivos de extensión: $extensionMessage");
            print_r(json_encode($respuesta));
            return false;
        }
        return true; 
    }

    public function guardarFichero ($archivo, $titulo)
    {
        $rutaArchivo = $this->crearRuta();

        $archivotemp = $archivo['tmp_name'];
        $extension = strtolower(pathinfo($archivo["name"] , PATHINFO_EXTENSION));
        $nuevoNombre = $titulo . '-'. date("H-i-s-m-d-Y.") . $extension;
        $pathFullFile = $rutaArchivo . $nuevoNombre;

        if (!move_uploaded_file($archivotemp, $pathFullFile)) {
            $respuesta = array ("status"=>"error", "message" => "No se pudo guardar el archivo para actualizar el registro.");
            print_r(json_encode($respuesta));
            return;
        } 
        return $nuevoNombre;
    }
    
    private function crearRuta (): string
    {       
        $pathForFile = $this->ruta ;
        if (!file_exists($pathForFile)) {
            mkdir($pathForFile, 0777, true);
        }
        return $pathForFile;
    }

    public function borrarArchivo ($sql, $params = null)
    {
        $stmt = $this->conexion->query($sql, $params, '', false);
        $resultado = $stmt->fetchColumn();
        $file_to_delete = $this->ruta .  $resultado;
        if (!unlink($file_to_delete)) {
            $respuesta = array("status"=>"error", "message" => "No se puede actualizar el archivo. Inténtelo más tarde o contacte con el soporte de la página.");
            print_r(json_encode($respuesta)); 
            throw new Exception("No se pudo reemplazar el archivo. Controlador de actualizacion, funcion reemplazar archivo");
        }
    }

    public function setRuta ($ruta) 
    {
        $this->ruta = $ruta;
    }
}