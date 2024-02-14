<?php


namespace tt;


class Cli extends \tt\AbstractObject
{
    /**
     * @var \tt\Logger
     */
    protected $logger;

    /**
     * @var \tt\cli\Output
     */
    protected $output;

    /**
     * 'command' => 'controller'
     *
     * @var string[]
     */
    protected $data = [
        'maintenance' => '\tt\cli\controllers\Maintenance',
        'test' => '\tt\cli\controllers\Test'
    ];

    public function __construct()
    {
        $controllers = getConfig('cli');
        $this->data = array_merge($this->data, $controllers);

        $this->logger = new \tt\Logger();
        $this->output = new \tt\cli\Output();
    }

    /**
     * The method splits the argument at the ":" character.
     * controller: method: argument 1: argument 2: ..
     * and executes it
     */
    public function execute()
    {
        try {
            $params = explode(':', $_SERVER['argv'][1]);
            if ($this->hasData($params[0])) {
                $class = $this->getData($params[0]);
                unset($params[0]);
                if (isset($params[1])) {
                    $method = $params[1];
                    unset($params[1]);
                }

                if (in_array('tt\cli\AbstractCommand', class_parents($class))) {
                    $object = new $class;
                    try {
                        if (isset($method) && method_exists($object, $method)) {
                            if (isset($params)) {
                                $object->$method($params);
                            } else {
                                $object->$method();
                            }
                        } else {
                            $object->execute();
                        }
                    } catch (\Exception $e) {
                        $this->logger->error($e->getMessage());
                        $this->output->error($e->getMessage());
                    }
                } else {
                    throw new \Exception($class . ' not extend \tt\cli\AbstractCommand');
                }
            } else {
                $commands = $this->getData();
                $this->output->message('Maybe you need one of these commands:');
                foreach ($commands as $command => $controller) {
                    $this->output->info($command);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->output->error($e->getMessage());
        }
    }
}