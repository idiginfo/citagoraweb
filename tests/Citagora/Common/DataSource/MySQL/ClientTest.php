<?php

namespace Citagora\Common\DataSource\MySQL;
use Citagora\CitagoraTest, Mockery;
use Citagora\TestFixture\DummyModel;

class ClientTest extends CitagoraTest
{
    //TEMPORARY TEMPORARY
    //For now, let's actually try out the class with the actual database

    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('Citagora\Common\DataSource\MySQL\Client', $obj);
    }

    // --------------------------------------------------------------

    public function testGetDocumentReturnsArrayForExistingDocument()
    {
        $obj = $this->getObject();
        $doc = $obj->getDocument(2);

        $this->assertInternalType('array', $doc);
        $this->assertArrayHasKey('MYID', $doc);

    }

    // --------------------------------------------------------------

    public function testGetDocumentReturnsFalseForNonExistingDocument()
    {
        $obj = $this->getObject();

        $doc = $obj->getDocument(1);
        $this->assertFalse($doc);
    }

    // --------------------------------------------------------------

    public function testGetDocumentsReturnsArrayContainingExistingDocuments()
    {
        $obj = $this->getObject();

        //2 and 11 exist, 3 does not
        $docs = $obj->getDocuments(array(2, 3, 11));
        $this->assertEquals(2, count($docs));
    }

    // --------------------------------------------------------------

    public function testGetDocumentsReturnsEmptyArrayForNoDocumentsFound()
    {
        $obj = $this->getObject();

        //Neither 3 or 4 exist
        $docs = $obj->getDocuments(array(3, 4));

        $this->assertEquals(array(), $docs, "The returning array should be empty");
    }

    // --------------------------------------------------------------

    public function testGetRecentDocumentsReturnsArray()
    {
        $obj = $this->getObject();

        $docs = $obj->getRecentDocuments(10, 0, 'asc');

        $this->assertEquals(10, count($docs));
        $this->assertArrayHasKey('MYID', $docs[0]);
    }

    // --------------------------------------------------------------

    protected function getObject()
    {
        // Creates an actual connection to the database
        //
        // //Read config
        // $config = new \Configula\Config(__DIR__ . '/../../../../../config');

        // //Get a Connection object
        // $doctrineConfig = new \Doctrine\DBAL\Configuration();
        // $doctrineParams = array(
        //     'dbname'   => $config->mysql['dbname'],
        //     'user'     => $config->mysql['user'],
        //     'password' => $config->mysql['pass'],
        //     'host'     => $config->mysql['host'],
        //     'port'     => isset($config->mysql['port']) ?: 3306,
        //     'driver'   => 'pdo_mysql',
        //     'charset'  => 'utf8'
        // );
        // $conn = \Doctrine\DBAL\DriverManager::getConnection($doctrineParams, $doctrineConfig);

        

        return new Client($conn);
    }
}

/* EOF: ClientTest.php */