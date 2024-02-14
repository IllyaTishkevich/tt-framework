<?php


namespace tt;


class Core
{
    const DEPLOY_MODE_DEVELOPER = 'developer';
    const DEPLOY_MODE_PRODUCTION = 'production';

    /**
     * @var tt\Router
     */
    protected $router;

    /**
     * The method creates a router class.
     * The router determines which controller to start.
     * Then the controller class is created and runs the 'execute' method.
     */
    public function execute()
    {
        if($this->maintenanceIsEnable()) {
            http_response_code(504);
            include BASE_DIR.'vendor/ttframework/views/maintenance.php';
        } else {
            $this->Route();
            $namespace = $this->router->getRoute();
            $controller = new $namespace;
            if ($controller->getTemplate() === '') {
                $template = strtolower(str_replace('app\controllers\\', '', $namespace));
                $controller->setTemplate($template);
            }
            $content = $controller->execute();

            if (is_string($content)) {
                echo $content;
            }
        }
    }

    /**
     * Determinate current route.
     */
    protected function Route()
    {
        $this->router = new \tt\Router();
        $this->router->determineRoute();
    }

    /**
     * Determines if the site is in maintenance mode
     * and if your ip is excluded.
     *
     * @return bool
     */
    protected function maintenanceIsEnable()
    {
        $ipList = getConfig('maintenance/ip');
        if($this->checkFile()) {
            $ip = $this->getIp();
            if(isset($ipList) && in_array($ip, $ipList)) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Defines the client's ip.
     *
     * @return string
     */
    protected function getIp() {
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $array = explode(',', $_SERVER[$key]);
                $ip = trim(end($array));
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }

    protected function checkFile()
    {
        return file_exists(BASE_DIR.'maintenance');
    }
}