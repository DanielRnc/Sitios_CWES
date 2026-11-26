<?php
    require_once __DIR__ . '/../app/Core/DB.php';
    require_once __DIR__ . '/../app/Controllers/PostsController.php';

    define('VIEWS_PATH', __DIR__ . '/../app/Views');
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch($uri){
        case '/www/MVC_2/public/index.php':
            $controller = new PostsController();
            $controller->index();
            break;
        case '/www/MVC_2/public/index.php/show';
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $controller = new PostsController();
            $controller->show($id);
            break;
        default:
            http_response_code(404);
            echo "Página no encontrada";
            break;
    }
?>
