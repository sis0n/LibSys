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
      $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
      $pattern = "#^" . trim($pattern, '/') . "$#";

      if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches);

        $controller = $info['controller'];
        $allowedAccess = $info['roles'];

        if (!empty($allowedAccess)) {
          $userRole = strtolower($_SESSION['role'] ?? '');
          $userId = $_SESSION['user_id'] ?? null;

          if (!$userId) {
            http_response_code(403);
            include __DIR__ . '/../Views/errors/403.php';
            return;
          }

          $hasAccess = false;

          $allowedAccessNormalized = array_map('strtolower', $allowedAccess);

          if (in_array($userRole, $allowedAccessNormalized)) {
            $hasAccess = true;
          }

          if (in_array($userRole, ['admin', 'librarian'])) {
            $userPermissions = $_SESSION['user_permissions'] ?? [];

            $normalizedUserPermissions = array_map('strtolower', $userPermissions);

            $matches = array_intersect($normalizedUserPermissions, $allowedAccessNormalized);

            if (count($matches) > 0) {
              $hasAccess = true;
            } else {
              $hasAccess = false; 
            }
          }

          if (!$hasAccess) {
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

        return $controllerInstance->$methodName(...$matches);
      }
    }

    http_response_code(404);
    include __DIR__ . '/../Views/errors/404.php';
  }
}
