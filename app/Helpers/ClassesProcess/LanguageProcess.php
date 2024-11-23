<?php

namespace App\Helpers\ClassesProcess;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LanguageProcess
{
    const CACHE_LANG_NAME = "__languages__";
    const LANG_HEADER_REQUEST_NAME = "language_local";
    #TYPE DATA
    const TYPE_DATA_RELATION_TRANS_HEADER_REQUEST = "type_data_translation";
    const DATA_DIRECT_TRANSLATION = "data_direct_translation";
    const DATA_ALL_TRANSLATIONS_WITH_DEFAULT = "data_all_translations_with_default";

    private mixed $langLocal = null;

    private mixed $langDefault = null;

    private mixed $allLanguages = null;

    public function __construct()
    {
        #all-languages
        $this->allLanguages = Cache::remember(self::CACHE_LANG_NAME,86400,function (){
            try {
                return DB::table("languages")->whereNull("deleted_at")->get();
            }catch (\Exception $exception){
                return collect([]);
            }
        });
        #default-language
        $this->langDefault = $this->allLanguages->where("default",1)->first();
        if (is_null($this->langDefault)){
            $this->langDefault = $this->allLanguages->first();
        }

        #current-language
        $this->langLocal = $this->allLanguages->where("code",app()->getLocale())->first();
        if (is_null($this->langLocal)){
            $this->langLocal = $this->langDefault;
        }
    }

    public function setLanguageLocal($code){
        $this->langLocal = $this->getLanguageByCode($code);
        if (is_null($this->langLocal)){
            $this->langLocal = $this->langDefault;
        }
    }

    public function getLanguageLocal(){
        return $this->langLocal;
    }

    public function getLanguageDefault(){
        return $this->langDefault;
    }

    public function getLanguageByCode(string $code){
        return $this->allLanguages?->where("code",$code)->first();
    }

    public function getAllLanguages(){
        return $this->allLanguages;
    }

    public function getFkMainTableInTranslationTable(): string
    {
        return "row_main_id";
    }

    public function getFkLanguageInTranslationTable():string{
        return "language_id";
    }

    public function getAllCodeLanguages(): array
    {
        return [
            'en' => 'English',
            'aa' => 'Afar',
            'ab' => 'Abkhazian',
            'af' => 'Afrikaans',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Byelorussian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bn' => 'Bengali',
            'br' => 'Breton',
            'ca' => 'Catalan',
            'co' => 'Corsican',
            'cs' => 'Czech',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dz' => 'Bhutani',
            'el' => 'Greek',
            'es' => 'Spanish',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'fi' => 'Finnish',
            'fj' => 'Fiji',
            'fo' => 'Faeroese',
            'fr' => 'French',
            'fy' => 'Frisian',
            'ga' => 'Irish',
            'gd' => 'Scots/Gaelic',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'ha' => 'Hausa',
            'hi' => 'Hindi',
            'hr' => 'Croatian',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'in' => 'Indonesian',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iw' => 'Hebrew',
            'ja' => 'Japanese',
            'ka' => 'Georgian',
            'kk' => 'Kazakh',
            'kl' => 'Greenlandic',
            'km' => 'Cambodian',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'ku' => 'Kurdish',
            'ky' => 'Kirghiz',
            'la' => 'Latin',
            'ln' => 'Lingala',
            'lt' => 'Lithuanian',
            'lv' => 'Latvian/Lettish',
            'mg' => 'Malagasy',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mo' => 'Moldavian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'ne' => 'Nepali',
            'nl' => 'Dutch',
            'no' => 'Norwegian',
            'oc' => 'Occitan',
            'om' => '(Afan)/Oromoor/Oriya',
            'pa' => 'Punjabi',
            'pl' => 'Polish',
            'ps' => 'Pashto/Pushto',
            'pt' => 'Portuguese',
            'rm' => 'Rhaeto-Romance',
            'rn' => 'Kirundi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sd' => 'Sindhi',
            'sg' => 'Sangro',
            'sh' => 'Serbo-Croatian',
            'si' => 'Singhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Siswati',
            'st' => 'Sesotho',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Tegulu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Setswana',
            'to' => 'Tonga',
            'tr' => 'Turkish',
            'tw' => 'Twi',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            'vi' => 'Vietnamese',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yo' => 'Yoruba',
            'zh' => 'Chinese',
            'zu' => 'Zulu'
        ];
    }
}
