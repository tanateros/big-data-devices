<?php

namespace High\Entity;
use High\Helper\Db;

/**
 * Class AbstractEntity
 *
 * @package High\Entity
 */
abstract class AbstractEntity
{
    /** @var \PDO $pdo */
    protected $pdo;

    /**
     * AbstractEntity constructor.
     *
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->pdo = $db->getPdo();
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
