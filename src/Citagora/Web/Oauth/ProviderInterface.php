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
     * Get normalized UserInfo
     *
     * @return Citagora\Web\Oauth\UserInfo
     */
    function getUserInfo(AccessToken $token);
}