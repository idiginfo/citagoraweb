<?php

namespace Citagora\Common\BackendAPI;

use Citagora\CitagoraTest, Mockery;
use Citagora\Common\Model\User\User;

/**
 * Abstract for classes that implement userInterface
 */
abstract class UserInterfaceTest extends CitagoraTest
{
    /**
     * @var array  Array of three 'valid' user ids
     */
    protected $validUserIds = array(1, 2, 3);

    /**
     * @var array  Array of three 'valid' emails
     */
    protected $validUserEmails = array('bob@example.com', 'jim@example.com', 'sally@example.com');

    /**
     * @var array  Array of three 'valid' passwords
     */
    protected $validUserPasswords = array('pass1234', 'pass4321', 'pass2468');

    /**
     * @var int  One 'invalid' user identifier
     */
    protected $invalidUserId = 5;

    /**
     * @var string One 'invalid' user email
     */
    protected $invalidUserEmail = 'should-not-work@example.com';

    /**
     * @var String One 'invalid' user password
     */
    protected $invalidUserPassword = 'shouldNotWork';

    // --------------------------------------------------------------

    /**
     * Return the object to be tested
     *
     * @return UserInterface
     */
    abstract protected function getObject();

    // --------------------------------------------------------------

    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('Citagora\Common\BackendAPI\UserInterface', $obj);
    }

    // --------------------------------------------------------------

    /**
     * Valid user ID will be '1' for our tests
     */
    public function testGetUserReturnsUserObjectForValidUser()
    {
        $obj  = $this->getObject();
        $user = $obj->getUser($this->validUserIds[0]);

        $this->assertInstanceOf('Citagora\Common\Model\User\User', $user);
    }

    public function testGetUserReturnsFalseForInvalidUser()
    {
        $obj  = $this->getObject();
        $user = $obj->getUser($this->invalidUserId);

        $this->assertFalse($user);
    }

    public function testGetUsersReturnsAnArrayOfUserObjectsWithIDsAsKeys()
    {
        $obj   = $this->getObject();
        $users = $obj->getUsers($this->validUserIds);

        foreach($users as $user) {
            $this->assertInstanceOf('Citagora\Common\Model\User\User', $user);
        }
    }

    public function testGetUsersIncludesFalseForInvalidUserIds()
    {
        $obj   = $this->getObject();
        $users = $obj->getUsers(array($this->validUserIds[0], $this->invalidUserId));

        $this->assertInstanceOf('Citagora\Common\Model\User\User', $users[$this->validUserIds[0]]);
        $this->assertFalse($users[$this->invalidUserId]);
    }

    public function testCheckCredentialsReturnsUserObjectForValidCredentials()
    {
        $obj = $this->getObject();

        $un  = $this->validUserEmails[0];
        $pw  = $this->validUserPasswords[0];

        $user = $obj->checkCredentials($un, $pw);

        $this->assertInstanceOf('Citagora\Common\Model\User\User', $user);
    }

    public function testCheckCredentialsReturnsNullForUserNotFound()
    {
        $obj = $this->getObject();

        $un  = $this->invalidUserEmail;
        $pw  = $this->invalidUserPassword;

        $user = $obj->checkCredentials($un, $pw);

        $this->assertNull($user);
    }

    public function testCheckCredentialsReturnsFalseForBadPassword()
    {
        $obj = $this->getObject();

        $un  = $this->validUserEmails[0];
        $pw  = $this->invalidUserPassword;

        $user = $obj->checkCredentials($un, $pw);

        $this->assertFalse($user);
    }

    public function testCreateNewUserReturnsUserObject()
    {
        $obj  = $this->getObject();
        $user = $obj->createNewUser();

        $this->assertInstanceOf('Citagora\Common\Model\User\User', $user);
    }

    public function testSaveUserReturnsVoid()
    {
        $obj  = $this->getObject();
        $user = $obj->createNewUser();

        $this->assertNull($obj->saveUser($user));
    }
}

/* EOF: UserInterfaceTest.php */