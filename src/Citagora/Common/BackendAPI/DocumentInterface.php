<?php

namespace Citagora\Common\BackendAPI;

interface DocumentInterface
{
    function getRecord($id);

    function getRecordsByIds($ids);
}

/* EOF: DocumentInterface.php */