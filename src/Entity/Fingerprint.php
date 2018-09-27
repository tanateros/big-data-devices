<?php

namespace High\Entity;

/**
 * Class Fingerprint
 *
 * @package High\Entity
 */
class Fingerprint extends AbstractEntity
{
    /** @var int $id */
    public $id;

    /** @var string $fingerprint */
    public $fingerprint;

    /**
     * @param array $data
     * @param bool  $isCreateRandom
     *
     * @return string
     */
    public function generateFingerprint(
        array $data, $isCreateRandom = false
    ): string {
        if ($isCreateRandom) {
            $data[] = uniqid();
        }

        return md5(join(';;', $data));
    }

    /**
     * @param string $fingerprint
     *
     * @return bool|int
     */
    public function getIdByFingerprint(string $fingerprint)
    {
        $stm = $this->pdo->prepare("SELECT id FROM fingerprints WHERE fingerprint = ?");
        $stm->execute([$fingerprint]);
        $data = $stm->fetchAll();

        return !empty($data) ? (int)$data[0]['id'] : false;
    }

    /**
     * @param array     $data
     * @param \PDO|null $pdo
     *
     * @return string
     */
    public function create(array $data, \PDO $pdo = null): string
    {
        if ($pdo) { // for support ACID (transaction part)
            $this->pdo = $pdo;
        }

        $stm = $this->pdo->prepare(
            'INSERT INTO fingerprints (fingerprint) VALUES (:fingerprint)'
        );
        $stm->execute([
            'fingerprint' => $data['fingerprint'],
        ]);

        return $this->pdo->lastInsertId();
    }
}
