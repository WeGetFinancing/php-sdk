<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Command;

use Exception;
use WeGetFinancing\SDK\Entity\Request\AbstractRequestEntity;

/**
 * CommandInterface describe the purpose of a command class
 *
 * In our client SDK, a command is an action that execute a call to WeGetFinancing API, therefore it will have
 * an execute method with a RequestEntityInterface as parameter.
 *
 * The commands will be then imported into the client allowing it to be expandable or easily to substitute.
 */
interface CommandInterface
{
    /**
     * The return can be composed to data or void, any unsuccessfully situation should be covered with appropriate
     * Exceptions management.
     *
     * @param AbstractRequestEntity $requestEntity
     * @throws Exception
     * @return mixed
     */
    public function execute(AbstractRequestEntity $requestEntity): mixed;
}
