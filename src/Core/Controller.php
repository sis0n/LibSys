<?php
namespace App\Core;

class Controller
{
    /**
     * Render a view with optional header/footer layout
     *
     * @param string $view  View file path (relative to Views folder, without .php)
     * @param array  $data  Data to extract into the view
     * @param bool   $withLayout Include header/footer (default: true)
     */
    public function view(string $view, array $data = [], bool $withLayout = true): void{
        extract($data, EXTR_SKIP);

        $basePath = __DIR__ . "/../Views/";
        $viewPath = $basePath . $view . ".php";

        $head = $basePath . "partials/head.php";
        $header = $basePath . "partials/header.php";
        $footer = $basePath . "partials/footer.php";

        if($withLayout && file_exists($head)){
            include $head;
        }

        if($withLayout && file_exists($header)){
            include $header;
        }

        if(file_exists($viewPath)){
            include $viewPath;
        } else {
            http_response_code(404);
            include $basePath . "errors/404.php";
        }

        if($withLayout && file_exists($footer)){
            include $footer;
        }
    }
}
