<?php


namespace tt\controllers;


abstract class AbstractController extends \tt\AbstractObject
{
    protected $data = [];

    public function __construct()
    {
        $pageDefaultSettings = getConfig('page');
        $this->data = array_merge($pageDefaultSettings, $this->data);
    }

    /**
     * Returns the path to the template file.
     *
     * @return string
     */
    public function getTemplate()
    {
        if($template = $this->getData('template')) {
            return $template;
        }

        return '';
    }

    /**
     * returns the path to the layout file.
     *
     * @return string
     */
    public function getLayout()
    {
        if($layout = $this->getData('layout')) {
            return $layout;
        }

        return '';
    }

    /**
     * Set template filepath.
     *
     * @param string $filePath
     */
    public function setTemplate(string $filePath)
    {
        $this->setData('template', $filePath);
    }

    /**
     * Set layout filepath.
     *
     * @param string $filePath
     */
    public function setLayout(string $filePath)
    {
        $this->setData('layout', $filePath);
    }


    /**
     * The method returns $this->data['title']. T
     * he main purpose is to get the page title
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * Set page titile.
     *
     * @param $title
     */
    public function setTitle($title)
    {
        $this->setData('title', $title);
    }

    /**
     * The method connects a template. The path for the template is taken either from the variable Data ['template']
     * (can be set using the setTempate('string') method, or by the setData('template', 'string' method).
     * Otherwise, the method will try to connect the template from the pub/views/*route* directory.
     * For servicing api, it is recommended to override the method for outputting data.
     */
    public function getContent()
    {
        if(file_exists(BASE_DIR.'pub/views/'.$this->getTemplate().'.php')) {
            require BASE_DIR . 'pub/views/' . $this->getTemplate() . '.php';
        } elseif(file_exists(BASE_DIR.$this->getTemplate().'.php')) {
            require BASE_DIR.$this->getTemplate().'.php';
        } else {
            throw new \Exception('Layout file `pub/views/' . $this->getTemplate() . '.php`'.' not found.');
        }
    }

    /**
     * The main method of rendering the page.
     * If the parameter html_doc = true then inside the html template of the document.
     * Inside of which there is a layout call. otherwise,
     * the getContent() method is called inside (the main purpose is to separate html documents from api).
     */
    public function render()
    {
        ob_start();
        if($this->getData('html_doc')) {
            require BASE_DIR . 'vendor/ttframework/layout/htmldoc.php';
        } else {
            $this->getContent();
        }
        return ob_get_clean();
    }

    /**
     * Include file layout.
     * Inside the $ this->getContent() method should be called.
     */
    public function renderLayout()
    {
        if($this->getLayout() === '') {
            return $this->getContent();
        }

        if(file_exists(BASE_DIR.'pub/layout/'.$this->getLayout().'.php')) {
            require BASE_DIR . 'pub/layout/' . $this->getLayout() . '.php';
        } elseif(file_exists(BASE_DIR.$this->getLayout().'.php')) {
            require BASE_DIR.$this->getLayout().'.php';
        } else {
            throw new \Exception('Layout file `pub/layout/' . $this->getLayout() . '.php`'.' not found.');
        }
    }

    /**
     * Сreates an array of pluggable resource strings.
     *
     * @return array
     */
    protected function getSource()
    {
        $arraySources = [];

        if($this->hasData('source')) {
            $sources = $this->getData('source');
            if(isset($sources['js'])) {
                foreach ($sources['js'] as $js) {
                    $arraySources[] = '<script  type="text/javascript"  src="'.$js.'"></script>'.PHP_EOL;
                }
            }

            if(isset($sources['css'])) {
                foreach ($sources['css'] as $css) {
                    $arraySources[] = '<link  rel="stylesheet" type="text/css"  media="all" href="'.$css.'" />'.PHP_EOL;
                }
            }

            if(isset($sources['strings'])) {
                foreach ($sources['strings'] as $string) {
                    $arraySources[] = $string.PHP_EOL;
                }
            }
        }

        return $arraySources;
    }

    /**
     * Redirect function.
     *
     * @param string $url contains query string.
     * @param array $params get params ['param' => 'value']
     * @param bool $permanent code true = 301, false = 302
     */
    public function redirect(string $url, array $params = [], bool $permanent = false)
    {
        $baseUrl = 'http://'.$_SERVER['HTTP_HOST'];
        $string = '';
        if(isset($params)) {
            $array = [];
            foreach ($params as $var => $param) {
                $array[] = $var . '=' . $param;
            }
            $string = '?' . implode('&', $array);
        }

        header('Location: ' . $baseUrl.'/'.$url.$string, true, $permanent ? 301 : 302);

        exit();
    }

    /**
     * The main method for the controller. Called below the stack from the /tt/Core object.
     */
    abstract function execute();


}