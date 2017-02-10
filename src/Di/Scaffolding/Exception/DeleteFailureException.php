<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Di\Scaffolding\Exception;

use Vegas\Di\Scaffolding\Exception;

/**
 * Class DeleteFailureException
 * @package Vegas\Di\Scaffolding\Exception
 */
class DeleteFailureException extends Exception
{
    /**
     * Exception default message
     */
    protected $message = 'Unable to delete record.';   
}