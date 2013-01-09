<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\OAuthTwo\StateStoreInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * State store for OAuth Credentials
 *
 * For now, does nothing, but we'll figure it out...
 */
class StateStore implements StateStoreInterface
{
    private $session;

    // --------------------------------------------------------------

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    // --------------------------------------------------------------

    /**
     * Get the state from storage.
     *
     * @return string
     */
    public function getState()
    {
        return $this->session->get('oauth_state', '');
    }

    // --------------------------------------------------------------

    /**
     * Set the state in storage.
     *
     * @param  string  $state
     * @return void
     */
    public function setState($state)
    {
        $this->session->set('oauth_state', $state);
    }
}

/* EOF: StateStore.php */