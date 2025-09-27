<?php

namespace App\Core;

class Router
{
  protected array $routes = [];

  public function get(string $uri, string $controller, array $roles = []): void
  {
    $this->routes['GET'][$uri] = [
      'controller' => $controller,
      'roles' => $roles
    ];
  }

  public function post(string $uri, string $controller, array $roles = []): void
  {
    $this->routes['POST'][$uri] = [
      'controller' => $controller,
      'roles' => $roles
    ];
  }

  public function resolve(string $uri, string $method)
  {
    $uri = trim($uri, '/');

    if (!isset($this->routes[$method])) {
      http_response_code(404);
      include __DIR__ . '/../Views/errors/404.php';
      return;
    }

    foreach ($this->routes[$method] as $route => $info) {
      // Convert {param} into regex group
      $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
      $pattern = "#^" . trim($pattern, '/') . "$#";

      if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches); // remove full match

        $controller = $info['controller'];
        $allowedRoles = $info['roles'];

        // role check
        if (!empty($allowedRoles)) {
          $userRole = $_SESSION['role'] ?? null;
          if (!$userRole || !in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            include __DIR__ . '/../Views/errors/403.php';
            return;
          }
        }

        [$controllerName, $methodName] = explode('@', $controller);
        $controllerClass = "App\\Controllers\\$controllerName";

        if (!class_exists($controllerClass)) {
          throw new \Exception("Controller $controllerClass not found");
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $methodName)) {
          throw new \Exception("Method $methodName not found in $controllerClass");
        }

        // Pass dynamic params (like $id)
        return $controllerInstance->$methodName(...$matches);
      }
    }

    // No route matched
    http_response_code(404);
    include __DIR__ . '/../Views/errors/404.php';
  }
}
