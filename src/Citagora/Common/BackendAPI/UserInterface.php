<?php

interface UserInterface
{
    const ANY = null;

    function registerUser(User $user);

    function loginUser(User $user);

    function retrieveUser($email, $password = self::ANY)

}

/* EOF: UserInterface.php */