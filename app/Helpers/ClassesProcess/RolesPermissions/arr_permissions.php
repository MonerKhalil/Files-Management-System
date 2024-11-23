<?php

use App\Helpers\MyApp;
use Illuminate\Support\Arr;

$permissionProcess = MyApp::Classes()->permissionsMap;

$usersPermissions = $permissionProcess->createPermissionsMapTable("users");
$rolesPermissions = $permissionProcess->createPermissionsMapTable("roles");

$finalPermissions = [
    #custom permissions...
];

foreach (Arr::except(get_defined_vars(),["permissionProcess","finalPermissions"]) as $permissions) {
    $finalPermissions = array_merge($finalPermissions,$permissions);
}
return $finalPermissions;
