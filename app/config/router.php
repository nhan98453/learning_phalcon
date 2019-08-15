<?php
use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router;

$router = $di->getRouter();
$router->removeExtraSlashes(true);

$user = new Group(['controller' => 'User']);
const ROUTE_USER_GROUPS = '/US';
$user->setPrefix(ROUTE_USER_GROUPS);

$user->addPost('/login', ['action' => 'login']);
$user->addget('/logout', ['action' => 'logout']);

$user->addGet('', ['action' => 'index']);
$user->addPut('/{username}', ['action' => 'edit']);
$user->addPost('', ['action' => 'create']);
$user->addDelete('/{username}',['action' => 'delete']);
$router->mount($user);

$product = new Group(['controller'=>'Product']);
$product->setPrefix('/product');

$product->addGet('', ['action' => 'index']);
$product->addPut('/{id}', ['action' => 'edit']);
$product->addPost('', ['action' => 'create']);
$product->addDelete('/{id}',['action' => 'delete']);
$router->mount($product);

$userGroup = new Group(['controller'=>'UserGroup']);
$userGroup->setPrefix('/USGroup');

$userGroup->addGet('', ['action' => 'index']);
$userGroup->addPut('/{id}', ['action' => 'edit']);
$userGroup->addPost('', ['action' => 'create']);
$userGroup->addDelete('/{id}',['action' => 'delete']);
$router->mount($userGroup);

$router->notFound(
    [
        'controller' => 'index',
        'action'     => 'route404',
    ]
);

$router->handle();

