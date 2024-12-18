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
            'English'=>'en',
            'Afar'=>'aa',
            'Abkhazian'=>'ab',
            'Afrikaans'=>'af',
            'Amharic'=>'am',
            'Arabic'=>'ar',
            'Assamese'=>'as',
            'Aymara'=>'ay',
            'Azerbaijani'=>'az',
            'Bashkir'=>'ba',
            'Byelorussian'=>'be',
            'Bulgarian'=>'bg',
            'Bihari'=>'bh',
            'Bislama'=>'bi',
            'Bengali'=>'bn',
            'Breton'=>'br',
            'Catalan'=>'ca',
            'Corsican'=>'co',
            'Czech'=>'cs',
            'Welsh'=>'cy',
            'Danish'=>'da',
            'German'=>'de',
            'Bhutani'=>'dz',
            'Greek'=>'el',
            'Spanish'=>'es',
            'Estonian'=>'et',
            'Basque'=>'eu',
            'Persian'=>'fa',
            'Finnish'=>'fi',
            'Fiji'=>'fj',
            'Faeroese'=>'fo',
            'French'=>'fr',
            'Frisian'=>'fy',
            'Irish'=>'ga',
            'Scots/Gaelic'=>'gd',
            'Guarani'=>'gn',
            'Gujarati'=>'gu',
            'Hausa'=>'ha',
            'Hindi'=>'hi',
            'Croatian'=>'hr',
            'Hungarian'=>'hu',
            'Armenian'=>'hy',
            'Indonesian'=>'in',
            'Icelandic'=>'is',
            'Italian'=>'it',
            'Hebrew'=>'iw',
            'Japanese'=>'ja',
            'Georgian'=>'ka',
            'Kazakh'=>'kk',
            'Greenlandic'=>'kl',
            'Cambodian'=>'km',
            'Kannada'=>'kn',
            'Korean'=>'ko',
            'Kurdish'=>'ku',
            'Kirghiz'=>'ky',
            'Latin'=>'la',
            'Lingala'=>'ln',
            'Lithuanian'=>'lt',
            'Latvian/Lettish'=>'lv',
            'Malagasy'=>'mg',
            'Maori'=>'mi',
            'Macedonian'=>'mk',
            'Malayalam'=>'ml',
            'Mongolian'=>'mn',
            'Moldavian'=>'mo',
            'Marathi'=>'mr',
            'Malay'=>'ms',
            'Maltese'=>'mt',
            'Burmese'=>'my',
            'Nepali'=>'ne',
            'Dutch'=>'nl',
            'Norwegian'=>'no',
            'Occitan'=>'oc',
            '(Afan)/Oromoor/Oriya'=>'om',
            'Punjabi'=>'pa',
            'Polish'=>'pl',
            'Pashto/Pushto'=>'ps',
            'Portuguese'=>'pt',
            'Rhaeto-Romance'=>'rm',
            'Kirundi'=>'rn',
            'Romanian'=>'ro',
            'Russian'=>'ru',
            'Kinyarwanda'=>'rw',
            'Sindhi'=>'sd',
            'Sangro'=>'sg',
            'Serbo-Croatian'=>'sh',
            'Singhalese'=>'si',
            'Slovak'=>'sk',
            'Slovenian'=>'sl',
            'Samoan'=>'sm',
            'Shona'=>'sn',
            'Somali'=>'so',
            'Albanian'=>'sq',
            'Serbian'=>'sr',
            'Siswati'=>'ss',
            'Sesotho'=>'st',
            'Sundanese'=>'su',
            'Swedish'=>'sv',
            'Swahili'=>'sw',
            'Tamil'=>'ta',
            'Tegulu'=>'te',
            'Tajik'=>'tg',
            'Thai'=>'th',
            'Tigrinya'=>'ti',
            'Turkmen'=>'tk',
            'Tagalog'=>'tl',
            'Setswana'=>'tn',
            'Tonga'=>'to',
            'Turkish'=>'tr',
            'Twi'=>'tw',
            'Ukrainian'=>'uk',
            'Urdu'=>'ur',
            'Uzbek'=>'uz',
            'Vietnamese'=>'vi',
            'Wolof'=>'wo',
            'Xhosa'=>'xh',
            'Yoruba'=>'yo',
            'Chinese'=>'zh',
            'Zulu'=>'zu',
        ];
    }
}
