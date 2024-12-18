<?php

use App\Helpers\MyApp;
use Illuminate\Support\Arr;

$permissionProcess = MyApp::Classes()->permissionsMap;

$usersPermissions = $permissionProcess->createPermissionsMapTable("users");
$rolesPermissions = $permissionProcess->createPermissionsMapTable("roles");
$EmailConfigPermissions = $permissionProcess->createPermissionsMapTable("email_configurations");
$SocialMediaPermissions = $permissionProcess->createPermissionsMapTable("social_media");
$languagesPermissions = $permissionProcess->createPermissionsMapTable("languages");
$filesManagerPermissions = $permissionProcess->createPermissionsMapTable("file_managers");

$finalPermissions = [
    #custom permissions...
    #TODO...
    #group_file Actions...
    "show_files_in_group", "show_groups_in_file","add_groups_to_file", "add_files_to_group","remove_groups_from_file", "remove_files_from_group",
    #group_users Actions...
    "show_users_in_group", "add_users_to_group", "remove_users_from_group",
    #porcess in groups ...
    "process_files_in_group_managers",
    "process_users_in_group_managers",
    #general_settings
    "show_general_settings","edit_general_settings",
    #website_settings
    "show_website_settings","edit_website_settings",

];

foreach (Arr::except(get_defined_vars(),["permissionProcess","finalPermissions"]) as $permissions) {
    $finalPermissions = array_merge($finalPermissions,$permissions);
}
return $finalPermissions;
