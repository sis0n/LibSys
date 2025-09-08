<?php
namespace App\Config;

class Router{
    protected $routes = [];

    public function get($uri, $controller, $roles = []){
        $this->routes['GET'][$uri] = [
            'controller' => $controller,
            'roles' => $roles
        ];
    }

    public function post($uri, $controller, $roles = []){
        $this->routes['POST'][$uri] = [
            'controller' => $controller,
            'roles' => $roles
        ];
    }

    public function resolve($uri, $method){
        $uri = trim($uri, '/');

        if(isset($this->routes[$method][$uri])){
            $route = $this->routes[$method][$uri];
            $controller = $route['controller'];
            $allowedRoles = $route['roles'];

            if(!empty($allowedRoles)){
                session_start();
                $userRole = $_SESSION['role'] ?? null;

                if(!$userRole || !in_array($userRole, $allowedRoles)){
                    http_response_code(403);
                    include __DIR__ . '/../Views/errors/403.php';
                    return;
                }
            }

            [$controllerName, $methodName] = explode('@', $controller);

            $controllerClass = "App\\Controllers\\$controllerName";

            if(!class_exists($controllerClass)){
                throw new \Exception("Controller $controllerClass not found");
            }

            $controllerInstance = new $controllerClass();

            if(!method_exists($controllerInstance, $methodName)){
                throw new \Exception("Method $methodName not found in $controllerClass");
            }

            return $controllerInstance->$methodName();
        }

        http_response_code(404);
        include __DIR__ . '/../Views/errors/404.php';
    }
}
