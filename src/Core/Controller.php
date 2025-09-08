<?php
namespace App\Core;

class Controller{
    /**
     * 
     *
     * @param string 
     * @param array  
     */
    public function view($view, $data = []){
        extract($data);

        $viewPath = __DIR__ . "/../Views/" . $view . ".php";
        $header   = __DIR__ . "/../Views/partials/header.php";
        $footer   = __DIR__ . "/../Views/partials/footer.php";

        if(file_exists($header)) include $header;
        
        if(file_exists($viewPath)){
            include $viewPath;
        } else {
            echo "<h1>View '{$view}' not found.</h1>";
        }

        if(file_exists($footer)) include $footer;
    }
}
