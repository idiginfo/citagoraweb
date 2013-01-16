<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\OAuthTwo\AccessToken;
use Illuminate\Socialite\OAuthTwo\FacebookProvider as OriginalFacebookProvider;

class FacebookProvider extends OriginalFacebookProvider implements ProviderInterface
{
    public function getUserInfo(AccessToken $token)
    {
        $mappings = array(
            'firstName' => 'first_name',
            'lastName'  => 'last_name',
            'email'     => 'email',
            'id'        => 'id'
        );

        return new UserInfo($this->getUserData($token), $mappings);
    }
}

/* EOF: FacebookProvider.php */