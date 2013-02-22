<?php

namespace Citagora\Common\BackendAPI;

use Citagora\CitagoraTest, Mockery;
use Citagora\Common\DataSource\Mongo\Entity\User;
use Citagora\Common\BackendAPI\UserAPI;

/**
 * Abstract for classes that implement userInterface
 */
class UserAPITest extends UserInterfaceTest
{
    public function testSaveUserExpectsMongoEntityUserOrThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $obj = $this->getObject();

        $nonMongoUser = new \Citagora\Common\Model\User\User();
        $obj->saveUser($nonMongoUser);
    }

    // --------------------------------------------------------------

    protected function getObject()
    {
        //Mock Mongo User Collection
        $ucMock = Mockery::mock('Citagora\Common\DataSource\Mongo\EntityCollection\UserCollection');

        $vids = $this->validUserIds;
        $vems = $this->validUserEmails;
        $vpws = $this->validUserPasswords;

        //Mock find -- Returns User or false
        $ucMock->shouldReceive('find')->andReturnUsing(function($v) use ($vids) {
            return (in_array($v, $vids)) ? new User() : false;
        });

        //Mock getUserByEmail -- Returns User or false
        $ucMock->shouldReceive('getUserByEmail')->andReturnUsing(function($v) use ($vems) {
            return (in_array($v, $vems)) ? new User() : false;
        });

        //Mock checkCredentials -- Returns User or false
        $ucMock->shouldReceive('checkCredentials')->andReturnUsing(function($e, $p) use($vems, $vpws) {

            if (in_array($e, $vems)) {
                return ($p == $vpws[array_search($e, $vems)]) ? new User() : false;
            }
            else {
                return false;
            }
        });

        //Mock factory
        $ucMock->shouldReceive('factory')->andReturn(new User());

        //Mock save
        $ucMock->shouldReceive('save')->andReturn(null);

        //Return the object
        return new UserAPI($ucMock);
    }
}

/* EOF: UserAPITest.php */