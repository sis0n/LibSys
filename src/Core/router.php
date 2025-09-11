<?php
namespace App\Core;

class Router {
  protected array $routes = [];

  public function get(string $uri, string $controller, array $roles = []): void{
    $this->routes['GET'][$uri] = [
        'controller' => $controller,
        'roles' => $roles
    ];
  }

  public function post(string $uri, string $controller, array $roles = []): void{
    $this->routes['POST'][$uri] = [
        'controller' => $controller,
        'roles' => $roles
    ];
  }

  public function resolve(string $uri, string $method){
    $uri = trim($uri, '/');

    // hanapin lang kung may route
    if (isset($this->routes[$method][$uri])) {
      $route = $this->routes[$method][$uri];
      $controller = $route['controller'];
      $allowedRoles = $route['roles'];

      // role base access pero di pa nagana haha
      if(!empty($allowedRoles)){
        session_start();
        $userRole = $_SESSION['role'] ?? null;

        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            include __DIR__ . '/../Views/errors/403.php';
            return;
        }
      }

      //tawagin lang yung controller
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

    // pag walang tumama na route
    http_response_code(404);
    include __DIR__ . '/../Views/errors/404.php';
  }
}
