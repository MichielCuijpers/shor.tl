<?php

use Shortl\Shortl\Contracts\Request;
use Shortl\Shortl\Contracts\Response;

use Shortl\Shortl\Infrastructure\ActionMapper;
use Shortl\Shortl\Infrastructure\ActionNotFound;
use Shortl\Shortl\Infrastructure\EntityNotFound;


/**
 * @param Response $response
 * @param string $type
 * @param int $status
 * @return string
 */
function not_found(Response $response, string $type, array $config, int $status = 302) : string
{
    return $response('', ['Location' => $config['redirect_url'], 'Type-Of-Missing' => $type, 'HTTP/1.1' => $status]);
}


/**
 * @param string $className
 * @return mixed
 */
function instantiate_class(string $className)
{
    return new $className();
}


/**
 * @param array $config
 * @param array $server
 * @param Request $parser
 * @param ActionMapper $mapper
 * @param Response $response
 * @return string
 */
function app(array $config, array $server, Request $parser, ActionMapper $mapper, Response $response) : string
{
    try {

        $request = $parser($server);
        $action = $mapper($request, $config);

        return $action($request, $response, $config);

    } catch (ActionNotFound $e) {

        return not_found($response, 'action', $config, 301);

    } catch (EntityNotFound $e) {

        return not_found($response, 'entity', $config);

    }
}
