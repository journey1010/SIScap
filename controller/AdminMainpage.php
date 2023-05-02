<?php

require_once (_ROOT_VIEWS . 'constrctviewadmin.php');

class AdminMainpage{

    public function show( $contenidoPage =  '')
    {  
        session_start();
        if(isset($_SESSION['username']) && isset($_SESSION['tipoUser']) ){
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' ) {
                //renderizar contenido de manera dinamica 
                $this->renderContentPage($_SESSION['username'] ,$_SESSION['tipoUser'], $contenidoPage);                
            } else {
                $this->renderView('admin/plantillaAdmin', $_SESSION['username'], $_SESSION['tipoUser'], $contenidoPage);
            }
        }else{

            header('Location: /administrador');
            exit;
        }
    }

    public function showContentPage($contenidoPage)
    {
        session_start();
        //verifica si hay variables session disponibles
        if (isset($_SESSION['username']) && isset($_SESSION['tipoUser']) ){
            //verifica si se ha enviado una solicitud ajax
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' ) {
                //renderizar contenido de manera dinamica 
                $this->renderContentPage($_SESSION['username'] ,$_SESSION['tipoUser'], $contenidoPage);                
            } else {
                $this->renderView('admin/plantillaAdmin', $_SESSION['username'], $_SESSION['tipoUser'], $contenidoPage);
            }
            
        }else{
            header('Location: /administrador');
            exit;
        }
    }

    private function renderContentPage($userName, $tipoUser, $contenidoPage)
    {
        $contenido = new viewConstruct($userName, $tipoUser, $contenidoPage);
        ob_clean();
        echo $contenido->buildContentPage();
        ob_end_flush();
        return;
    }

    private function renderView(string $viewName, $userName, $tipoUser, $contenidoPage)
    {
        $viewName = ($viewName == 'ErrorView') ? 'ErrorView' : $viewName;

        $fullpath = _ROOT_VIEWS .  $viewName . '.php';

        try {

            if(!file_exists($fullpath)){
                throw new Exception ('Vista no encontrada' . $viewName);
            }
            if(pathinfo($fullpath, PATHINFO_EXTENSION)!== 'php'){
                throw new Exception ('Archivo de tipo no permitido' . $viewName);
            }
            ob_start();
            include_once $fullpath;
            echo ob_get_clean();
            return;

        }catch (Exception $e){
            $this->errorLog($e);
            return;
        }
    }

    protected function errorLog(Throwable $e){
        $errorMessage = date("Y-m-d H:i:s") . " : " . $e->getMessage() .  "\n";
        error_log($errorMessage, 3, _ROOT_PATH . '/log/error.log'  );
    }
}