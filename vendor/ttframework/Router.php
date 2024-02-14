<?php


namespace tt;


class Router
{
    /**
     * The property contains an array of routes
     * from the configuration file 'config/routes.php'
     *
     * @var string[]
     */
    protected $routes = [
        '404' => 'core\controllers\NotFound'
    ];

    /**
     * Property contains the current route.
     *
     * @var array
     */
    protected $route = [];


    public function __construct()
    {
        $routes = getConfig('routes');
        foreach ($routes as $match => $route) {
            $this->addRoute($match, $route);
        }
    }

    /**
     * The method adds a new entry to the routes array.
     *
     * @param string $regexp
     * @param string $route
     */
    public function addRoute(string $regexp, string $route)
    {
        $this->routes[$regexp] = $route;
    }

    /**
     * @return string[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * return current route.
     *
     * @return array
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set current route.
     *
     * @param string $route
     */
    protected function setRoute(string $route)
    {
        $this->route = $route;
    }

    /**
     * The method determines the current route by url
     *
     * @return bool
     */
    public function determineRoute()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $params = parse_url($uri);
        $match = trim($params['path'], '/');

        $routes = $this->getRoutes();
        if(isset($routes[$match])) {
            $this->setRoute($routes[$match]);
            return true;
        }

        $match = strtolower($match);
        $arrayMatches = explode('/', $match);
        end( $arrayMatches );
        $key = key($arrayMatches);
        $arrayMatches[$key] = ucfirst($arrayMatches[$key]);
        $match = implode('/',$arrayMatches);
        $path = BASE_DIR.'app/controllers/'.$match.'.php';
        if(file_exists($path)) {
            $this->setRoute('app\controllers\\'.$match);
            return true;
        }

        $this->setRoute($this->routes['404']);
        return true;
    }
}