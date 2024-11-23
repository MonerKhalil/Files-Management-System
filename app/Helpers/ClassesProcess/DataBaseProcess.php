<?php

namespace App\Helpers\ClassesProcess;

use App\Exceptions\MainException;
use Illuminate\Support\Facades\DB;

class DataBaseProcess
{
    /**
     * @param string $tableName
     * @param int|array $idsRecords
     * @throws MainException
     */
    public function checkTableIsAnyChild(string $tableName, int|array $idsRecords)
    {
        $tables = $this->getChildParentTable($tableName);
        if (sizeof($tables) > 0){
            foreach ($tables as $columnName => $table){
                $idsRecords = is_array($idsRecords) ? $idsRecords : [$idsRecords];
                if ( DB::table($table)->whereIn($columnName,$idsRecords)->exists() ){
                    $message = "the table " . $tableName . " contains records within these tables => " .implode(",",$tables);
                    throw new MainException($message);
                }
            }
        }
    }

    public function deleteAllChildParent(string $tableName, int|array $idsRecords){
        try {
            DB::beginTransaction();
            $tables = $this->getChildParentTable($tableName);
            if (sizeof($tables) > 0){
                foreach ($tables as $columnName => $table){
                    $idsRecords = is_array($idsRecords) ? $idsRecords : [$idsRecords];
                    DB::table($table)->whereIn($columnName,$idsRecords)->update(["deleted_at" => now()]);
                }
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw new MainException($e->getMessage());
        }
    }

    public function getChildParentTable($tableName): array
    {
        return DB::table('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->select(['TABLE_NAME','COLUMN_NAME'])
            ->where('CONSTRAINT_SCHEMA', env("DB_DATABASE"))
            ->where('REFERENCED_TABLE_NAME', $tableName)
            ->whereNot('TABLE_NAME',"LIKE", "%translations")
            ->pluck("TABLE_NAME" , "COLUMN_NAME")->toArray();
    }

    public function getFieldsUniqueInTable($tableName){
        if (preg_match('/^[a-zA-Z_]+$/', $tableName)){
            $uniqueFields = DB::select("SHOW COLUMNS FROM $tableName WHERE `Key` = 'UNI'");

            return array_column($uniqueFields, 'Field');
        }
        return [];
    }

    public function checkIsFieldsIsNullable($tableName,$column){
        $data = DB::selectOne("SHOW COLUMNS FROM $tableName WHERE Field = '$column'");
        return (($data->Null ?? null) == "YES");
    }
}
