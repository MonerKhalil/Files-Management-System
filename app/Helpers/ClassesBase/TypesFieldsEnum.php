<?php

namespace App\Helpers\ClassesBase;

use App\Helpers\Traits\TEnumToArray;

enum TypesFieldsEnum : string
{
    use TEnumToArray;

    case TEXT = "text";
    case NUMBER = "number";
    case FILE = "file";
    case IMAGE = "image";
    case EMAIL = "email";
    case BOOLEAN = "boolean";
    case PHONE = "phone";
    case DATE = "date";
    case EDITOR = "editor";
    case URL = "url";
    case PASSWORD = "password";
    case ENUM = "enum";
    case RELATION = "relation";
}
