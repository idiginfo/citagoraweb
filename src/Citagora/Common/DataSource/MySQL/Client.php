<?php

namespace Citagora\Common\DataSource\MySQL;

use Doctrine\DBAL\Connection;

class Client
{
    /**
     * @var Doctrine\DBAL\Connection
     */
    private $conn;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Doctrine\DBAL\Connection
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    // --------------------------------------------------------------

    public function getDocument($id)
    {
        $stmt = $this->conn->prepare(Queries::GET_DOCUMENT_QUERY);
        $stmt->bindValue('id', (int) $id);
        $result = $stmt->execute();
    }
}

/* EOF: Client.php */