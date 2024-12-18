<?php

namespace App\Helpers\ClassesProcess;

use App\Exceptions\MainException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFileProcess
{
    public const DISK = "public";
    public const FOLDER_STORAGE = "uploads";

    public const EX_IMG = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
    public const Ex_FILE = ['pdf','xlsx','csv','docx'];
    public const FOLDER_IMAGES = "images";
    public const FOLDER_FILES = "files";
    #_Byte size
    public const Max_SIZE_IMG = 256000;
    public const Max_SIZE_FILE = 10000000;

    /**
     * @param $file
     * @param string $pathDir
     * @param string|null $disk
     * @param bool $isOutExtension
     * @param string|null $pathOutExtension
     * @return bool|string
     * @throws MainException
     * @author moner khalil
     */
    public function storeFile($file, string $pathDir = "", string $disk = null,bool $isOutExtension = false ,string $pathOutExtension = null): bool|string
    {
        $ext = $file->getClientOriginalExtension();
        $file_base_name = str_replace('.' . $ext, '', $file->getClientOriginalName());
        $fileNameFinal = strtolower(time() . Str::random(5) . '-' . Str::slug($file_base_name)) . '.' . $ext;
        $FolderType = $this->getFolderType($ext,$isOutExtension);
        if (!is_null($FolderType)){
            $path = Storage::disk(is_null($disk) ? self::DISK : $disk)
                ->putFileAs($pathDir . "/" . $FolderType , $file, $fileNameFinal);
            if (is_string($path)){
                return self::FOLDER_STORAGE . "/" . $path;
            }
        }
        if ($isOutExtension){
            if (is_null($pathOutExtension)){
                $pathOutExtension = Storage::disk(is_null($disk) ? self::DISK : $disk)->path("");
            }
            $file->move($pathOutExtension, $fileNameFinal);
            return $fileNameFinal . "." . $ext;
        }
        throw new MainException("you cant upload current file_ -OR- fix in upload file....");
    }

    /**
     * @param string $path
     * @param string|null $disk
     * @return bool
     * @author moner khalil
     */
    public function deleteFile(string $path, string $disk = null): bool
    {
        $disk = is_null($disk) ? self::DISK : $disk;
        return Storage::disk($disk)->delete($path);
    }

    /**
     * @param string $path
     * @param string|null $disk
     * @return BinaryFileResponse
     * @throws MainException
     * @author moner khalil
     */
    public function downloadFile(string $path, string $disk = null): BinaryFileResponse
    {
        $path = ltrim($path, self::FOLDER_STORAGE.'/');
        $path = Storage::disk(is_null($disk) ? self::DISK : $disk)->path($path);
        $file = file_exists($path) ? $path : null;
        if (!is_null($file)) {
            $file = response()->download($file);
            ob_end_clean();
            return $file;
        }
        throw new MainException("the path file {$path} is not exists");
    }

    /**
     * @param string $path
     * @param string|null $disk
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function responseFile(string $path, string $disk = null)
    {
        $path = ltrim($path, self::FOLDER_STORAGE.'/');
        $path = Storage::disk(is_null($disk) ? self::DISK : $disk)->path($path);
        $file = file_exists($path) ? $path : null;
        if (!is_null($file)) {
            return response()->file($file);
        }
        throw new MainException("the path file {$path} is not exists");
    }

    public function getSizeFiles(){
        return self::Max_SIZE_FILE;
    }

    public function getExFiles($is_array = false){
        return $is_array ? self::Ex_FILE : implode(",",self::Ex_FILE);
    }

    public function getSizeImages(): int
    {
        return self::Max_SIZE_IMG;
    }

    public function getExImages($is_array = false): array|string
    {
        return $is_array ? self::EX_IMG : implode(",",self::EX_IMG);
    }

    /**
     * @param $ext
     * @param $isOutExtension
     * @return string|null
     */
    private function getFolderType($ext, $isOutExtension): ?string
    {
        $FolderType = null;
        if (in_array($ext, $this->getExFiles(true)) && !$isOutExtension) {
            $FolderType = self::FOLDER_FILES;
        }
        if (in_array($ext, $this->getExImages(true)) && !$isOutExtension) {
            $FolderType = self::FOLDER_IMAGES;
        }
        return $FolderType;
    }
}
