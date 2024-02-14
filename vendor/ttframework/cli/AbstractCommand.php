<?php


namespace tt\cli;


abstract class AbstractCommand extends \tt\AbstractObject
{
    /**
     * @var \tt\Logger
     */
    protected $logger;

    /**
     * @var \tt\cli\Output
     */
    protected $output;

    public function __construct()
    {
        $this->logger = new \tt\Logger();
        $this->output = new \tt\cli\Output();
    }

    /**
     * Default execute method.
     *
     * @return mixed
     */
    abstract public function execute();

    /**
     * The method returns a non-specific array of strings.
     * Each line will be displayed on a new line.
     * @return array
     */
    abstract public function getHelp();

    /**
     * whrite help text.
     */
    public function writeHelper()
    {
        $rows = $this->getHelp();
        $this->output->error($rows);
//        foreach ($rows as $string) {
//            $this->output->info($string);
//        }
    }
}