<?php
//1. PROBANDO si funciona el index.php
    //echo "hola";

//2. PROBANDO si funciona la database.php
    /*require_once 'config/database.php';
    $db = (new Database())->getConnection();
    var_dump($db);*/

    session_start();
    require_once 'config/database.php';

    $action = $_GET['action'] ?? 'home';

    switch ($action) {
        case 'home':
            echo 'portada';
            break;
        
        case 'login':
            echo 'formulario de login';
            break;
        
        case 'register':
            echo 'formulario de registro';
            break;
        
        case 'procesarRegistro';
            echo 'procesar registro';
            break;
        
        case 'logout';
            echo 'cerrar sesion';
            break;
        
        //parte de TareaController

        case 'misTareas':
            echo 'mis tareas';
            break;

        case 'guardarTarea':
            echo 'guardar tarea';
            break;
        
        case 'editarTarea':
            echo 'editar tarea';
            break;
        
        case 'actualizarTarea':
            echo 'actualizar tarea';
            break;
        
        case 'eliminarTarea':
            echo 'eliminar Tarea';
            break;
            
        default:
            echo 'pagina no encontrada';
            break;
    }
?>