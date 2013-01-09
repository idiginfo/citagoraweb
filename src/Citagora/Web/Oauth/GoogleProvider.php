<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\OAuthTwo\AccessToken;
use Illuminate\Socialite\OAuthTwo\GoogleProvider as OriginalGoogleProvider;

class GoogleProvider extends OriginalGoogleProvider implements ProviderInterface
{
    /**
     * Get normalized user data
     */
    function getNormalizedUserData(AccessToken $token)
    {
        $userData = $this->getUserData($token);

        return array(
            'id'        => $userData->get('id'),
            'firstName' => $userData->get('given_name'),
            'lastName'  => $userData->get('family_name'),
            'email'     => $userData->get('email')
        );
    }
}
/* EOF: GoogleProvider.php */