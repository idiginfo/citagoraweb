<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\OAuthTwo\AccessToken;
use Illuminate\Socialite\OAuthTwo\StateStoreInterface;
/**
 * Provider interface for getting basic information about the user
 */
interface ProviderInterface
{
    /**
     * Return an array with the following keys:
     *  - id
     *  - firstName
     *  - lastName
     *  - email
     */
    function getNormalizedUserData(AccessToken $token);
}