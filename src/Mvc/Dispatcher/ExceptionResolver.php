<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Mvc\Dispatcher;

use Phalcon\Dispatcher;
use Vegas\Constants;
use Vegas\Exception;
use Vegas\Mvc\Dispatcher\Exception\CannotHandleErrorException;
use Vegas\Exception as VegasException;
use Vegas\Mvc\View;

/**
 * Class ExceptionResolver
 * @package Vegas\Mvc\Dispatcher
 */
class ExceptionResolver implements \Phalcon\DI\InjectionAwareInterface
{
    use \Vegas\DI\InjectionAwareTrait;

    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function resolve(\Exception $exception)
    {
        if (Constants::DEFAULT_ENV === $this->di->get('environment')) {
            $error = $this->prepareLiveEnvException($exception);
        } else {
            $error = $this->prepareDevEnvException($exception);
        }
        
        try {
            $rendered = $this->renderLayoutForError($error);

            $response = $this->di->getShared('response');
            $response->setStatusCode($error->getCode(), $error->getMessage());
        } catch (\Exception $ex) {
            throw new CannotHandleErrorException($ex->getMessage());
        }

        if (!$response->isSent()) {
            if (!$rendered) {
                echo $error->getCode().' '.$error->getMessage();
            }
            
            return $response->send();
        }

        return $response;
    }

    /**
     * @param \Exception $exception
     * @return VegasException
     */
    private function prepareLiveEnvException(\Exception $exception)
    {
        switch ($exception->getCode()) {
            case 403:
                return new VegasException('Access forbidden.', 403);
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
            case 404:
                return new VegasException('The page does not exist.', 404);
            case 0:
            case Dispatcher::EXCEPTION_NO_DI:
            case Dispatcher::EXCEPTION_INVALID_PARAMS:
            case Dispatcher::EXCEPTION_CYCLIC_ROUTING:
            case 500:
                return new VegasException('Application error.', 500);
            default:
                return new VegasException('Bad request.', 400);
       }
    }

    /**
     * @param \Exception $exception
     * @return VegasException
     */
    private function prepareDevEnvException(\Exception $exception)
    {
        switch ($exception->getCode()) {
            case 403:
                return new VegasException($exception->getMessage(), 403);
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
            case 404:
                return new VegasException($exception->getMessage(), 404);
            default:
                return new VegasException($exception->getMessage(), 500);
       }
    }

    /**
     * @param VegasException $error
     * @return bool
     */
    private function renderLayoutForError(VegasException $error)
    {
        $view = $this->di->getShared('view');
        $engines = $view->getRegisteredEngines();

        foreach ($engines As $ext => $engine) {
            if (file_exists($view->getLayoutsDir().'error'.$ext)) {
                $view->setLayout('error');
                $view->disableLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
                $view->error = $error;

                return true;
            }
        }

        return false;
    }
}
