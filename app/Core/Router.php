<?php
/**
 * Router Class
 * Handle URL routing and dispatch to controllers
 */

class Router
{
    private $routes = [];
    
    /**
     * Add GET route
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }
    
    /**
     * Add POST route
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }
    
    /**
     * Add PATCH route
     */
    public function patch($path, $callback)
    {
        $this->addRoute('PATCH', $path, $callback);
    }
    
    /**
     * Add DELETE route
     */
    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
    }
    
    /**
     * Add route to routes array
     */
    private function addRoute($method, $path, $callback)
    {
        // Convert path with parameters to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }
    
    /**
     * Handle incoming request
     */
    public function handle($uri, $method, $database)
    {
        // Handle method override for PATCH/DELETE requests
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                // Extract parameters from path
                $params = $this->extractParams($route['path'], $matches);
                
                return $this->dispatch($route['callback'], $params, $database);
            }
        }
        
        // 404 Not Found
        $this->show404();
    }
    
    /**
     * Extract parameters from route path
     */
    private function extractParams($path, $matches)
    {
        preg_match_all('/\{([^}]+)\}/', $path, $paramNames);
        $params = [];
        
        foreach ($paramNames[1] as $index => $name) {
            $params[$name] = $matches[$index] ?? null;
        }
        
        return $params;
    }
    
    /**
     * Dispatch to controller
     */
    private function dispatch($callback, $params, $database)
    {
        if (is_string($callback)) {
            [$controller, $method] = explode('@', $callback);
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller($database);
                
                if (method_exists($controllerInstance, $method)) {
                    return call_user_func_array([$controllerInstance, $method], [$params]);
                }
            }
        }
        
        $this->show404();
    }
    
    /**
     * Show 404 error page
     */
    private function show404()
    {
        http_response_code(404);
        include VIEWS_PATH . '/errors/404.php';
        exit;
    }
}