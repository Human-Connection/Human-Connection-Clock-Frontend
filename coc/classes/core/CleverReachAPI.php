<?php
/*
 * @copyright Copyright 2021 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

namespace coc\core;


class CleverReachAPI
{
    /**
     * @var string
     */
    const BASE_URL = 'https://rest.cleverreach.com';

    /**
     * @var string
     */
    const ENDPOINT_REFRESH_ACCESS_TOKEN = '/oauth/token.php';

    /**
     * @var string
     */
    const ENDPOINT_CREATE_RECEIVER = '/v3/groups.json/{group_id}/receivers';

}
