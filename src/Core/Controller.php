<?php
namespace App\Core;

class Controller
{
    /**
     * Render a view with optional header/footer layout
     *
     * @param string $view  View file path (relative to Views folder, without .php)
     * @param array  $data  Data to extract into the view
     * @param void   $withLayout Include header/footer (default: true)
     */
    public function view(string $view, array $data = [], bool $withLayout = true): void
    {
        extract($data, EXTR_SKIP);

        $basePath = __DIR__ . "/../Views/";

        $head = $basePath . "partials/head.php";
        $sidebar = $basePath . "partials/sidebar.php";
        $header = $basePath . "partials/header.php";
        $footer = $basePath . "partials/footer.php";
        $viewPath = $basePath . $view . ".php";


        if ($withLayout && file_exists($head)) {
            include $head;
        }
        if ($withLayout) {
            echo '<body class="bg-gray-50 font-sans min-h-screen flex">'; // full screen height
            echo '<div class="flex min-h-screen w-full">';
            
            // Sidebar
            if (file_exists($sidebar)) {
                include $sidebar;
            }

            // Right side (header + content + footer)
            echo '<div class="flex-1 flex flex-col">';

            // Header
            if (file_exists($header)) {
                include $header;
            }

            // Main Content (expandable)
            echo '<main class="flex-1 p-6">';
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                http_response_code(404);
                include $basePath . "errors/404.php";
            }
            echo '</main>';

            // Footer (push to bottom)
            if (file_exists($footer)) {
                echo '<div class="mt-auto">';
                include $footer;
                echo '</div>';
            }

            echo '</div>'; // close right side
            echo '</div>'; // close flex wrapper
            echo '</body>';
        }

         else {
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                http_response_code(404);
                include $basePath . "errors/404.php";
            }
        }
    }
}