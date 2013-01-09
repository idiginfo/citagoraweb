<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\OAuthTwo\AccessToken;
use Illuminate\Socialite\OAuthTwo\FacebookProvider as OriginalFacebookProvider;

class FacebookProvider extends OriginalFacebookProvider implements ProviderInterface
{
    /**
     * Get normalized user data
     */
    function getNormalizedUserData(AccessToken $token)
    {
        $userData = $this->getUserData($token);

        return array(
            'id'        => $userData->get('id'),
            'firstName' => $userData->get('first_name'),
            'lastName'  => $userData->get('last_name'),
            'email'     => $userData->get('email')
        );
    }
}
/* EOF: GoogleProvider.php */