<?php

namespace App\Helpers\ClassesProcess;

use DateTime;
use Illuminate\Support\Carbon;

class StringProcess
{
    /**
     * @param string $strValue
     * @param $queryModel
     * @param string $column
     * @param null $ignoreId
     * @return string
     * @author moner khalil
     */
    function uniqueColumn(string $strValue, $queryModel, string $column = 'slug',$ignoreId = null): string
    {
        $slug = $strValue;

        $string = mb_strtolower($slug, "UTF-8");;
        $string = preg_replace("/[\/.]/", " ", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $slug = preg_replace("/[\s_]/", '-', $string);

        //get unique slug...
        $nSlug = $slug;
        $i = 0;

        if (!is_null($ignoreId)){
            $queryModel = $queryModel->whereNot("id",$ignoreId);
        }
        $queryModel = $queryModel->withoutGlobalScopes()->select([$column])->get();
        while (($queryModel->where($column, '=', $nSlug)->count()) > 0) {
            $nSlug = $slug . '-' . ++$i;
        }

        return ($i > 0) ? substr($nSlug, 0, strlen($slug)) . '-' . $i : $slug;
    }

    /**
     * @description code Generate Unique in table
     * @param $firstCode
     * @param $queryModel
     * @param $columnCode
     * @return string
     * @author moner khalil
     */
    public function codeGenerateUnique($firstCode, $queryModel, $columnCode){
        $idMax = $queryModel->withoutGlobalScopes()->max("id") + 1;
        $code = $firstCode . "-" . $idMax;
        return $this->uniqueColumn($code,$queryModel,$columnCode);
    }

    /**
     * @description Check String is Date and Convert to YYYY-MM-DD
     * @param string $inputString
     * @param bool $withTimeStamp
     * @param bool $isEndDay
     * @return false|string
     * @author moner khalil
     */
    public function dateFormat(string $inputString,bool $withTimeStamp = false , bool $isEndDay = false): bool|string
    {
        $formats = [
            'Y-m-d',
            'd-m-Y',
            'd/m/Y',
            'm/d/Y',
        ];
        $isValid = false;
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $inputString);
            if ($date !== false && $date->format($format) === $inputString) {
                $isValid = true;
                $inputString = Carbon::createFromFormat($format, $inputString);
                if ($withTimeStamp && $isEndDay){
                    $inputString = $inputString->endOfDay();
                }
                $finalFormat = $withTimeStamp ? 'Y-m-d H:i:s' : 'Y-m-d';
                $inputString = $inputString->format($finalFormat);
                break;
            }
        }
        return $isValid ? $inputString : false;
    }

    /**
     * @param $strValue
     * @return array|string|null
     */
    public function xssString($strValue): array|string|null
    {
        $pattern = '/<a[^>]*href\s*=\s*["\']?\s*javascript\s*:\s*[^>"\']*["\']?[^>]*>|<form[^>]*action\s*=\s*["\']?\s*javascript\s*:\s*[^>"\']*["\']?[^>]*>|<img[^>]*src\s*=\s*["\']?\s*javascript\s*:\s*[^>"\']*["\']?[^>]*>/i';
        $strValue = preg_replace($pattern,'',$strValue);
        $strValue = preg_replace('/<script\b[^>]*>(.*?)<\/script>/i', '', $strValue);
        $strValue = preg_replace('/script\b[^>]*(.*?)\/script/i', '', $strValue);
        $strValue = preg_replace('/<code\b[^>]*>(.*?)<\/code>/i', '', $strValue);
        $strValue = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $strValue);

        // Remove javascript: and vbscript: protocols
        $strValue = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $strValue);
        $strValue = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $strValue);
        $strValue = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $strValue);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $strValue = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $strValue);
        $strValue = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $strValue);
        return preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $strValue);
    }

    /**
     * @param string $strValue
     * @return string
     */
    public function camelCase(string $strValue): string
    {
        $i = array("-", "_");
        $strValue = preg_replace('/([a-z])([A-Z])/', "\\1 \\2", $strValue);
        $strValue = preg_replace('@[^a-zA-Z0-9\-_ ]+@', '', $strValue);
        $strValue = str_replace($i, ' ', $strValue);
        $strValue = str_replace(' ', '', ucwords(strtolower($strValue)));
        $strValue = strtolower(substr($strValue, 0, 1)) . substr($strValue, 1);
        return ucfirst($strValue);
    }

    /**
     * @param $strValue
     * @return array|bool|string
     */
    public function strEncrypt($strValue): array|bool|string
    {
        $strValue = $strValue . 'abc_xyz';

        $ciphering = "AES-128-CTR";

        $options = 0;

        $encryption_iv = '__abc_xyz_14587965214585899858541256978541_abc_xyz__';

        $encryption_key = "__abc_xyz_MonerKhalilKeyEncryption_abc_xyz__";

        $encrypted = openssl_encrypt($strValue, $ciphering, $encryption_key, $options, $encryption_iv);

        return str_replace("/", "__", $encrypted);
    }

    /**
     * @param $encryption
     * @return array|bool|string
     */
    public function strDecrypt($encryption): array|bool|string
    {
        $encryption = str_replace("__", "/", $encryption);

        $ciphering = "AES-128-CTR";

        $options = 0;

        $decryption_iv = '__abc_xyz_14587965214585899858541256978541_abc_xyz__';

        $decryption_key = "__abc_xyz_MonerKhalilKeyEncryption_abc_xyz__";

        $decrypt = openssl_decrypt($encryption, $ciphering, $decryption_key, $options, $decryption_iv);

        return str_replace('abc_xyz', '', $decrypt);
    }
}
