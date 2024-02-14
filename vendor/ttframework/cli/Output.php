<?php


namespace tt\cli;


class Output extends \tt\AbstractObject
{
    /**
     * @var int
     */
    protected $processCharNumber = 0;

    /**
     * using in render process string.
     *
     * @var string[]
     */
    protected $processChars = ['.','..','...','....'];

    /**
     * write string in console.
     * text color - green, using tab.
     *
     * @param $string
     */
    public function info($string)
    {
        if(is_string($string)) {
            fwrite(STDOUT,"\t" . "\e[1m\e[032m" . $string . "\e[0m" . PHP_EOL);
        } elseif(is_array($string)) {
            foreach ($string as $row) {
                $this->info($row);
            }
        }
    }

    /**
     * write string in console.
     * text color - white.
     *
     * @param $string
     */
    public function message($string)
    {
        if(is_string($string)) {
            fwrite(STDOUT,"\e[1m\e[0m".$string."\e[0m".PHP_EOL);
        } elseif(is_array($string)) {
            foreach ($string as $row) {
                $this->message($row);
            }
        }
    }

    /**
     * write string in console.
     * text color - white, background color - red.
     * using tab. border.
     *
     * @param $string
     */
    public function error($string)
    {
        if(is_string($string)) {
            $strlen = strlen($string);
            fwrite(STDOUT,"\t\033[41m" . str_pad('', $strlen + 2) . "\033[0m" . PHP_EOL);
            fwrite(STDOUT,"\t\033[41m " .$string . " \033[0m" . PHP_EOL);
            fwrite(STDOUT,"\t\033[41m" . str_pad('', $strlen +2 ) . "\033[0m" . PHP_EOL);
        } elseif(is_array($string))
        {
            $maxstring = '';
            foreach ($string as $row)
            {
                if(strlen($row) > strlen($maxstring)) {
                    $maxstring = $row;
                }
            }
            $strlen = strlen($maxstring);
            fwrite( STDOUT, "\t\033[41m" . str_pad('', $strlen + 2) . "\033[0m" . PHP_EOL);
            foreach ($string as $row)
            {
                fwrite(STDOUT,"\t\033[41m " .str_pad($row, $strlen + 1) . "\033[0m" . PHP_EOL);
            }
            fwrite(STDOUT, "\t\033[41m" . str_pad('', $strlen + 2) . "\033[0m" . PHP_EOL);
        }
    }

    /**
     * set message. draw first step process.
     *
     * @param $message
     */
    public function initProcess($message)
    {
        $this->setMessage($message);
        $this->updateProcess();
    }

    /**
     * draw next step.
     */
    public function updateProcess()
    {
        $string = "Processing".str_pad($this->processChars[$this->processCharNumber++],5).$this->getMessage()."\r";
        fwrite(STDOUT, $string);
        if ($this->processCharNumber > count($this->processChars) - 1) $this->processCharNumber = 0;
    }

    /**
     * clean process string.
     */
    public function closeProcess()
    {
        $string = str_pad('',15 + strlen($this->getMessage()))."\r";
        fwrite(STDOUT, $string);
    }

    /**
     * set message.
     * draw bar with 1% progress.
     *
     * @param $message
     */
    public function initBar($message)
    {
        $this->setMessage($message);
        $this->updateBar(1);
    }

    /**
     * draw process bar with N% progress.
     * @param int $process
     */
    public function updateBar(int $process)
    {
        $process = $process == 0 ? 1 : $process;
        $pr = ($process/ 3.33333);
        $procent = $process < 10 ? " $process%" : "$process%";
        $string = "[".str_pad('',$pr,'#').str_pad('',30 - $pr,' ')."] ".$procent." ".$this->getMessage()."\r";
        fwrite(STDOUT, $string);
        if ($this->processCharNumber > count($this->processChars) - 1) $this->processCharNumber = 0;
    }

    /**
     * clear process bar string.
     */
    public function closeBar()
    {
        $string = str_pad('',38 + strlen($this->getMessage()))."\r";
        fwrite(STDOUT, $string);
    }
}