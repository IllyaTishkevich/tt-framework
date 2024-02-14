<?php


namespace tt;


class ErrorHandler
{
    private $mode = 0;

    private $logger;

    private $typeError = [
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',
    ];

    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
        $config = getConfig('settings/mode');
        if($config === \tt\Core::DEPLOY_MODE_DEVELOPER) {
            $this->mode = 1;
        } else {
            $this->mode = 0;
        }

        $this->logger = new \tt\Logger();
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if($this->mode) {
            print_r(
                "<div style='background-color:#4a4a4a4a; width:max-content;padding: 10px'>
                                <code>{$this->typeError[$errno]}: {$errstr} in {$errfile}:{$errline}  line</code>
                           </div>"
            );
        }
        $this->logger->alert("{$this->typeError[$errno]}: {$errstr} in {$errfile}:{$errline}  line");

        return true;
    }

    function fatalErrorHandler()
    {
        if ($error = error_get_last() AND $error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR))
        {
            ob_end_clean();
            $err = [
                'message' => stristr($error['message'], 'Stack trace:', true),
                'code' => $error['type'],
                'file' => $error['file'],
                'line' => $error['line'],
                'trace' => explode('#',trim(stristr(stristr($error['message'], '#0'),'thrown',true)))
            ];
            $this->displayFatalError($err);
        } else {
            ob_end_flush();
        }
    }

    private function displayFatalError($error)
    {
        http_response_code(500);
        if($this->mode) {
            error_reporting(-1);
            require BASE_DIR . 'vendor/ttframework/views/err/devmode.php';
        } else {
            error_reporting(0);
            require BASE_DIR . 'vendor/ttframework/views/err/prodmode.php';
        }
        $this->logger->critical("{$error['message']} {$error['file']} : {$error['line']}");

        return true;
    }

    private function exceptionHandler(\Exception $e)
    {
        return $this->displayFatalError([
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace()
        ]);
    }

}