<?php

namespace tt;


class Logger
{
    /**
     * @var \tt\lib\storage\TextFileManager
     */
    protected $fileManager;

    /**
     * Path to save logs. Defined in the class constructor.
     *
     * @var string
     */
    protected $filePath;

    public function __construct()
    {
        $this->filePath = BASE_DIR.getConfig('settings/logDir').'/';
        $this->fileManager = new \tt\lib\storage\TextFileManager();
    }

    /**
     * Syste not work
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $message = '[{time}] Emergency: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'emergency.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     * Immediate action required.
     *
     * For example: the website is disabled, the database is unavailable, etc.
     * Such a log should encourage you to take immediate action.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $message = '[{time}] Alert: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'alert.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     * Critical situation.
     *
     *Example: application component unavailable, unexpected Exception.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $message = '[{time}] Message: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'critical.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     * An error at the execution stage that does not require immediate action,
     * but requires to be secured and further studied.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = array())
    {
        $message = '[{time}] Error: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'error.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     * Exceptions that are not errors.
     *
     * Example: using deprecated APIs, misusing APIs, other unwanted effects
     * Such cases do not always indicate that the application is not working properly.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $message = '[{time}] Warning: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'warning.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     *Normal events in the application, but significant and require logging.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $message = '[{time}] Notice: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'notice.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     *Useful significant events.
     *
     * Example: user logs, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = array())
    {
        $message = '[{time}] Info: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'info.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     * Detailed debugging information.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $message = '[{time}] Debug: '.$message.PHP_EOL;
        $context['time'] = date("Y-m-d H:i:s");
        $message = $this->interpolate($message, $context);

        $filePath = $this->filePath.'debug.log';

        return $this->fileManager->add($filePath, $message);
    }

    /**
     * Logs with an arbitrary level.
     * The required log level is passed to the $ log variable.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        if(method_exists($this, $level) && $level != 'log') {
            return call_user_func_array(['\tt\Logger',$level], [$message, $context]);
        }

        return false;
    }

    /**
     * Message constructor.
     * Substitute the variables from the $context array into the string
     *
     * @param $message
     * @param array $context
     * @return string
     */
    protected function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }
}