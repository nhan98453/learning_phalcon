<?php
use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router;

$router = $di->getRouter();

$userGroup = new Group(['controller' => 'xxx']);
const ROUTE_USER_GROUPS = '/user-groups';

$userGroup->setPrefix(ROUTE_USER_GROUPS);
$userGroup->addGet('', ['action' => 'get']);
$userGroup->addPut('/{id}', ['action' => 'update']);
$userGroup->addPost('', ['action' => 'post']);
$userGroup->addDelete('/{id}', ['action' => 'delete']);

$router->mount($userGroup);


$router->notFound(
    [
        'controller' => 'index',
        'action'     => 'route404',
    ]
);

$router->handle();

