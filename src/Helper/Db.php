<?php

namespace High\Helper;

class Db
{
    /** @var \PDO $pdo */
    protected $pdo;

    /**
     * Db constructor.
     *
     * @param string $host
     * @param string $db
     * @param string $user
     * @param string $pass
     * @param string $charset
     */
    public function __construct(
        string $host,
        string $db,
        string $user,
        string $pass,
        string $charset = 'utf8'
    ) {
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $this->pdo = new \PDO($dsn, $user, $pass);
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return \Exception|mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->pdo, $name)) {
            return call_user_func_array($this->pdo->{$name}, $arguments);
        } else {
            return new \Exception("Method $name not found.");
        }
    }
}
