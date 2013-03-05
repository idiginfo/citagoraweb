<?php

namespace Citagora\Common\DataSource\MySQL;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use PDO;

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

    /**
     * Get a single document
     *
     * @return boolean|array  Return array if found, false if not found
     */
    public function getDocument($id)
    {
        $stmt = $this->conn->prepare(Queries::GET_DOCUMENT_QUERY);
        $stmt->bindValue('id', (int) $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // --------------------------------------------------------------

    /**
     * Get multiple documents
     *
     * @return array
     */
    public function getDocuments(array $ids)
    {
        //Ensure all ids are integers
        $ids = array_map(function($v) { return (int) $v; }, $ids);

        //Build and run the query (must be done in one step.  See: http://goo.gl/RdlaC)
        $stmt = $this->conn->executeQuery(Queries::GET_DOCUMENTS_QUERY,
            array($ids),
            array(Connection::PARAM_INT_ARRAY)
        );

        //Fetch all into memory
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------------

    /**
     * Get recent documents
     *
     * @param int $limit         Limit (default 100)
     * @param int $offset        Offset (default 0)
     */
    public function getRecentDocuments($limit = 100, $offset = 0)
    {
        $stmt = $this->conn->prepare(Queries::GET_RECENT_DOCUMENTS_QUERY);
        $stmt->bindValue('limit',  (int) $limit,  PDO::PARAM_INT);
        $stmt->bindValue('offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

/* EOF: Client.php */