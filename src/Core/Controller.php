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
    public function view($view, $data = [], $withLayout = true)
    {
        extract($data);

        $viewPath = __DIR__ . "/../Views/" . $view . ".php";
        $header = __DIR__ . "/../Views/partials/header.php";
        $footer = __DIR__ . "/../Views/partials/footer.php";

        // Include header if with layout
        if ($withLayout && file_exists($header))
            include $header;

        // Main content
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "<h1>View '{$view}' not found.</h1>";
        }

        // Include footer if with layout
        if ($withLayout && file_exists($footer))
            include $footer;
    }
}
