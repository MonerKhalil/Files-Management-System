<?php


use App\Helpers\ClassesProcess\RolesPermissions\Roles;

return [
    Roles::ADMIN => [
        "all_users",
        "all_roles",
        "all_languages",
        "all_file_managers",
        #group_file permissions...
        "show_files_in_group", "show_groups_in_file",
        "add_groups_to_file", "add_files_to_group",
        "remove_groups_from_file", "remove_files_from_group",
        #group_users Actions...
        "show_users_in_group", "add_users_to_group", "remove_users_from_group",
        #settings
        "show_general_settings","edit_general_settings","show_website_settings","edit_website_settings",
    ],
    Roles::USER => [

    ],
];
