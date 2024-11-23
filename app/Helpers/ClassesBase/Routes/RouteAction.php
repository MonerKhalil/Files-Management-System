<?php

namespace App\Helpers\ClassesBase\Routes;

class RouteAction
{
    private bool $isActive = true;

    public function __construct(
        private string $url,
        private string $name,
        private string $action,
        private string $method,
        private array $permissions = [],
        private array $middlewares = [],
        private ?string $keySend = null)
    {
    }

    public function getUrl(){
        return $this->url;
    }

    public function setUrl(string $url){
        $this->url = $url;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName(string $name){
        $this->name = $name;
        return $this;
    }

    public function getAction(){
        return $this->action;
    }

    public function setAction(string $action){
        $this->action = $action;
        return $this;
    }

    public function getMethod(){
        return $this->method;
    }

    public function setMethod(string $method){
        $this->method = $method;
        return $this;
    }

    public function getPermissions(){
        return $this->permissions;
    }

    public function setPermissions(array $permissions){
        $this->permissions = $permissions;
    }

    public function addPermission(string $permission){
        $this->permissions[] = $permission;
        return $this;
    }

    public function getMiddlewares(){
        return $this->middlewares;
    }

    public function addMiddleware(string $middleware){
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function checkIsActive(){
        return $this->isActive;
    }

    public function setActive(bool $value){
        $this->isActive = $value;
        return $this;
    }

    public function getKeySendInUrl(){
        return $this->keySend;
    }

    public function setKeySendInUrl(string $key){
        $this->keySend = $key;
        return $this;
    }
}
