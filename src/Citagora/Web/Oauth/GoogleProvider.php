<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\OAuthTwo\AccessToken;
use Illuminate\Socialite\OAuthTwo\GoogleProvider as OriginalGoogleProvider;

class GoogleProvider extends OriginalGoogleProvider implements ProviderInterface
{
    public function getUserInfo(AccessToken $token)
    {
        $mappings = array(
            'firstName' => 'given_name',
            'lastName'  => 'family_name',
            'email'     => 'email',
            'id'        => 'id'
        );

        return new UserInfo($this->getUserData($token), $mappings);
    }
}
/* EOF: GoogleProvider.php */