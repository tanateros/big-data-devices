<?php

namespace High\Entity;

class DeviceMessage extends AbstractEntity
{
    public function create(int $deviceId, string $message)
    {
        $this->pdo->beginTransaction();

        $currentTime = date("Y-m-d H:i:s");

        $stmMessage = $this->pdo->prepare(
            'INSERT INTO messages (device_id, message, is_sent, created_at)
            VALUES (:device_id, :message, :is_sent, :created_at)'
        );
        $stmMessage->execute([
            'device_id' => $deviceId,
            'message' => $message,
            'is_sent' => 0,
            'created_at' => $currentTime,
        ]);
        $id = $this->pdo->lastInsertId();

        $this->pdo->commit();
        return $id;
    }
}
