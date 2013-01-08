<?php

namespace Citagora\Web\Library;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Notices Library
 *
 * Uses Symfony session to manage notices
 */
class Notices
{
    const ALL = 0;

    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    // --------------------------------------------------------------

    /**
     * Uses the Symfony session library
     *
     * @param Symfony\Component\HttpFoundation\Session\Session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    // --------------------------------------------------------------

    /**
     * Add a notice to the session
     *
     * @param string $message  Any message
     * @param string $type     Any short string
     * @param string $scope    An optional scope
     */
    public function add($message, $type = 'info', $scope = 'global')
    {
        //Append to notices array
        $arr = $this->session->get('notices', array());
        $arr[] = (object) array('message' => $message, 'type' => $type, 'scope' => $scope);

        //Update the session
        $this->session->set('notices', $arr);
    }

    // --------------------------------------------------------------

    /**
     * Get notices
     *
     * @param string|int $type   If self::ALL, then get all notices
     * @param string|int $scope  If self::ALL, then get all notices of that scope
     * @return array
     */
    public function get($type = self::ALL, $scope = self::ALL)
    {
        $arr = $this->session->get('notices', array());

        //Unset any that are of the wrong type
        if ($type != self::ALL OR $scope != self::ALL) {
            foreach($arr as $k => $msg) {

                if ($type != self::ALL && $msg->type != $type) {
                    unset($arr[$k]);
                }

                if ($scope != self::ALL && $msg->scope != $scope) {
                    unset($arr[$k]);
                }
            }
        }

        return $arr;
    }

    // --------------------------------------------------------------

    /**
     * Get notices and remove them from the session
     *
     * @param string|int $type   If self::ALL, then get all notices
     * @param string|int $scope  If self::ALL, then get all notices of that scope
     * @return array
     */
    public function flush($type = self::ALL, $scope = self::ALL)
    {
        //Get the notices
        $dispArr = $this->get($type, $scope);
        $allArr  = $this->get(self::ALL, self::ALL);

        //Unset all notices
        $this->session->remove('notices');

        //Reset any notices that aren't being displayed here
        $diff = array_diff(
            array_map(function($v) { return json_encode($v); }, $allArr),
            array_map(function($v) { return json_encode($v); }, $dispArr)
        );
        foreach($diff as $n) {
            $this->add($n->message. $n->type, $n->scope);
        }

        //Return the msgs
        return $dispArr;
    }
}

/* EOF: Notices.php */