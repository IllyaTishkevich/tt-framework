<?php


namespace tt\lib;


class PDO
{
    /**
     * @var bool|\PDO
     */
    protected $singlePdoObject = false;

    /**
     * @var bool|\PDO
     */
    protected static $instance = false;

    /**
     * PDO options.
     *
     * @var array
     */
    protected $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ];

    protected function __construct(array $env) {
        if(isset($env['dns'],$env['user'],$env['password'])) {
            $this->singlePdoObject = new \PDO($env['dns'], $env['user'], $env['password'], $this->options);
        } else {
            throw new \Exception('Error getting connection parameters.');
        }
    }

    /**
     * get pdo instance.
     *
     * @return bool|\PDO|PDO
     * @throws \Exception
     */
    public static function getConnect()
    {
        if(self::$instance === false) {
            self::$instance = new self(self::getEnv());
        }

        return self::$instance;
    }

    /**
     * refactor config data.
     *
     * @return array
     * @throws \Exception
     */
    protected static function getEnv()
    {
        $env = getConfig('db');
        return [
            'dns' => $env['source'].':host='.$env['host'].';port='.$env['port'].';dbname='.$env['database'],
            'user' => $env['user'],
            'password' => $env['password']
        ];
    }

    /**
     * execute query.
     *
     * @param string $string : query
     * @return bool|\PDOStatement
     */
    protected function execute(string $string)
    {
        $result = $this->singlePdoObject->prepare($string);
        return $result;
    }

    /**
     * execute query with params.
     *
     * @param string $string : query
     * @param array $param : query param|array
     * @return bool
     */
    public function query(string $string, array $param = [])
    {
        $result = $this->execute($string);
        return $result->execute($param);
    }

    /**
     * execute query with param.
     * return data.
     *
     * @param string $string : query
     * @param array $param : query param|array
     * @return array
     */
    public function queryFetch(string $string, array $param = [])
    {
        $result = $this->execute($string);
        $result->execute($param);
        return $result->fetchAll();
    }
}