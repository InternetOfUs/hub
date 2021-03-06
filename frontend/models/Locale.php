<?php

namespace frontend\models;

use Yii;

class Locale {

    const LOCALE_AFRIKAANS = "af"; // "Afrikaans",
    const LOCALE_ALBANIAN = "sq"; // "Albanian",
    const LOCALE_BASQUE = "eu"; // "Basque",
    const LOCALE_BELARUSIAN = "be"; // "Belarusian",
    const LOCALE_BULGARIAN = "bg"; // "Bulgarian",
    const LOCALE_CATALAN = "ca"; // "Catalan",
    const LOCALE_CHINESE_CHINA = "zh_CN"; // "Chinese - China",
    const LOCALE_CHINESE_HONG_KONG = "zh_HK"; // "Chinese - Hong Kong SAR",
    const LOCALE_CHINESE_MACAU = "zh_MO"; // "Chinese - Macau SAR",
    const LOCALE_CHINESE_SINGAPORE = "zh_SG"; // "Chinese - Singapore",
    const LOCALE_CHINESE_TAIWAN = "zh_TW"; // "Chinese - Taiwan",
    const LOCALE_CROATIAN = "hr"; // "Croatian",
    const LOCALE_CZECH = "cs"; // "Czech",
    const LOCALE_DANISH = "da"; // "Danish",
    const LOCALE_DUTCH_BELGIUM = "nl_BE"; // "Dutch - Belgium",
    const LOCALE_DUTCH_NETHERLANDS = "nl_NL"; // "Dutch - Netherlands",
    const LOCALE_ENGLISH_AUSTRALIA = "en_AU"; // "English - Australia",
    const LOCALE_ENGLISH_BELIZE = "en_BZ"; // "English - Belize",
    const LOCALE_ENGLISH_CANADA = "en_CA"; // "English - Canada",
    const LOCALE_ENGLISH_CARRIBEAN = "en_CB"; // "English - Caribbean",
    const LOCALE_ENGLISH_GB = "en_GB"; // "English - Great Britain",
    const LOCALE_ENGLISH_INDIA = "en_IN"; // "English - India",
    const LOCALE_ENGLISH_IRELAND = "en_IE"; // "English - Ireland",
    const LOCALE_ENGLISH_JAMAICA = "en_JM"; // "English - Jamaica",
    const LOCALE_ENGLISH_NZ = "en_NZ"; // "English - New Zealand",
    const LOCALE_ENGLISH_PHILLIPPINES = "en_PH"; // "English - Phillippines",
    const LOCALE_ENGLISH_SOUTHERN_AFRICA = "en_ZA"; // "English - Southern Africa",
    const LOCALE_ENGLISH_TRINIDAD = "en_TT"; // "English - Trinidad",
    const LOCALE_ENGLISH_US = "en_US"; // "English - United States",
    const LOCALE_ESTONIAN = "et"; // "Estonian",
    const LOCALE_FARSI = "fa"; // "Farsi - Persian",
    const LOCALE_FINNISH = "fi"; // "Finnish",
    const LOCALE_FRENCH_BELGIUM = "fr_BE"; // "French - Belgium",
    const LOCALE_FRENCH_CANADA = "fr_CA"; // "French - Canada",
    const LOCALE_FRENCH_FRANCE = "fr_FR"; // "French - France",
    const LOCALE_FRENCH_LUXEMBOURG = "fr_LU"; // "French - Luxembourg",
    const LOCALE_FRENCH_SWITZERLAND = "fr_CH"; // "French - Switzerland",
    const LOCALE_GAELIC_IRELAND = "gd_IE"; // "Gaelic - Ireland",
    const LOCALE_GAELIC_SCOTLAND = "gd"; // "Gaelic - Scotland",
    const LOCALE_GERMAN_AUSTRIA = "de_AT"; // "German - Austria",
    const LOCALE_GERMAN_GERMANY = "de_DE"; // "German - Germany",
    const LOCALE_GERMAN_LICHTENSTEIN = "de_LI"; // "German - Liechtenstein",
    const LOCALE_GERMAN_LUXEMBOURG = "de_LU"; // "German - Luxembourg",
    const LOCALE_GERMAN_SWITZERLAND = "de_CH"; // "German - Switzerland",
    const LOCALE_GREEK = "el"; // "Greek",
    const LOCALE_GUARANI = "gn"; // "Guarani - Paraguay",
    const LOCALE_HEBREW = "he"; // "Hebrew",
    const LOCALE_HINDI = "hi"; // "Hindi",
    const LOCALE_HUNGARIAN = "hu"; // "Hungarian",
    const LOCALE_ICELANDIC = "is"; // "Icelandic",
    const LOCALE_INDONESIAN = "id"; // "Indonesian",
    const LOCALE_ITALIAN_ITALY = "it_IT"; // "Italian - Italy",
    const LOCALE_ITALIAN_SWITZERLAND = "it_CH"; // "Italian - Switzerland",
    const LOCALE_JAPANESE = "ja"; // "Japanese",
    const LOCALE_KOREAN = "ko"; // "Korean",
    const LOCALE_LATVIAN = "lv"; // "Latvian",
    const LOCALE_MALTESE = "mt"; // "Maltese",
    const LOCALE_MOGOLIAN = "mn"; // "Mongolian",
    const LOCALE_NORWEGIAN = "no_NO"; // "Norwegian - Bokml",
    const LOCALE_POLISH = "pl"; // "Polish",
    const LOCALE_PORTUGUESE_BRAZIL = "pt_BR"; // "Portuguese - Brazil",
    const LOCALE_PORTUGUESE_PORTUGAL = "pt_PT"; // "Portuguese - Portugal",
    const LOCALE_PUNJABI = "pa"; // "Punjabi",
    const LOCALE_ROMANIAN_MOLDOVA = "ro_MO"; // "Romanian - Moldova",
    const LOCALE_ROMANIAN_ROMANIA = "ro"; // "Romanian - Romania",
    const LOCALE_RUSSIAN = "ru"; // "Russian",
    const LOCALE_RUSSIAN_MOLDOVA = "ru_MO"; // "Russian - Moldova",
    const LOCALE_SERBIAN = "sr_SP"; // "Serbian - Cyrillic",
    const LOCALE_SLOVAK = "sk"; // "Slovak",
    const LOCALE_SLOVENIAN = "sl"; // "Slovenian",
    const LOCALE_SOMALI = "so"; // "Somali",
    const LOCALE_SPANISH_ARGENTINA = "es_AR"; // "Spanish - Argentina",
    const LOCALE_SPANISH_BOLIVIA = "es_BO"; // "Spanish - Bolivia",
    const LOCALE_SPANISH_CHILE = "es_CL"; // "Spanish - Chile",
    const LOCALE_SPANISH_COLOMBIA = "es_CO"; // "Spanish - Colombia",
    const LOCALE_SPANISH_COSTA_RICA = "es_CR"; // "Spanish - Costa Rica",
    const LOCALE_SPANISH_DOMINICAN_REP = "es_DO"; // "Spanish - Dominican Republic",
    const LOCALE_SPANISH_ECUADOR = "es_EC"; // "Spanish - Ecuador",
    const LOCALE_SPANISH_EL_SALVADOR = "es_SV"; // "Spanish - El Salvador",
    const LOCALE_SPANISH_GUATEMALA = "es_GT"; // "Spanish - Guatemala",
    const LOCALE_SPANISH_HONDURAS = "es_HN"; // "Spanish - Honduras",
    const LOCALE_SPANISH_MEXICO = "es_MX"; // "Spanish - Mexico",
    const LOCALE_SPANISH_NICARAGUA = "es_NI"; // "Spanish - Nicaragua",
    const LOCALE_SPANISH_PANAMA = "es_PA"; // "Spanish - Panama",
    const LOCALE_SPANISH_PARAGUAY = "es_PY"; // "Spanish - Paraguay",
    const LOCALE_SPANISH_PERU = "es_PE"; // "Spanish - Peru",
    const LOCALE_SPANISH_PUERTO_RICO = "es_PR"; // "Spanish - Puerto Rico",
    const LOCALE_SPANISH_SPAIN = "es_ES"; // "Spanish - Spain (Traditional)",
    const LOCALE_SPANISH_URUGUAY = "es_UY"; // "Spanish - Uruguay",
    const LOCALE_SPANISH_VENEZUELA = "es_VE"; // "Spanish - Venezuela",
    const LOCALE_SWEDISH_FINLAND = "sv_FI"; // "Swedish - Finland",
    const LOCALE_SWEDISH_SWEDEN = "sv_SE"; // "Swedish - Sweden",
    const LOCALE_THAI = "th"; // "Thai",
    const LOCALE_TIBETAN = "bo"; // "Tibetan",
    const LOCALE_TURKISH = "tr"; // "Turkish",
    const LOCALE_UKRAINIAN = "uk"; // "Ukrainian",
    const LOCALE_URDU = "ur"; // "Urdu",
    const LOCALE_UZBEK = "uz_UZ"; // "Uzbek - Cyrillic",
    const LOCALE_VIETNAMESE = "vi"; // "Vietnamese",
    const LOCALE_WELSH = "cy"; // "Welsh",
    const LOCALE_XHOSA = "xh"; // "Xhosa",
    const LOCALE_YIDDISH = "yi"; // "Yiddish",
    const LOCALE_ZULU = "zu"; // "Zulu"

    public static function locales(){
        return array_keys(self::localeLabels());
    }

    public static function localeLabels() {
        return [
            self::LOCALE_AFRIKAANS => "Afrikaans",
            self::LOCALE_ALBANIAN => "Albanian",
            self::LOCALE_BASQUE => "Basque",
            self::LOCALE_BELARUSIAN => "Belarusian",
            self::LOCALE_BULGARIAN => "Bulgarian",
            self::LOCALE_CATALAN => "Catalan",
            self::LOCALE_CHINESE_CHINA => "Chinese - China",
            self::LOCALE_CHINESE_HONG_KONG => "Chinese - Hong Kong SAR",
            self::LOCALE_CHINESE_MACAU => "Chinese - Macau SAR",
            self::LOCALE_CHINESE_SINGAPORE => "Chinese - Singapore",
            self::LOCALE_CHINESE_TAIWAN => "Chinese - Taiwan",
            self::LOCALE_CROATIAN => "Croatian",
            self::LOCALE_CZECH => "Czech",
            self::LOCALE_DANISH => "Danish",
            self::LOCALE_DUTCH_BELGIUM => "Dutch - Belgium",
            self::LOCALE_DUTCH_NETHERLANDS => "Dutch - Netherlands",
            self::LOCALE_ENGLISH_AUSTRALIA => "English - Australia",
            self::LOCALE_ENGLISH_BELIZE => "English - Belize",
            self::LOCALE_ENGLISH_CANADA => "English - Canada",
            self::LOCALE_ENGLISH_CARRIBEAN => "English - Caribbean",
            self::LOCALE_ENGLISH_GB => "English - Great Britain",
            self::LOCALE_ENGLISH_INDIA => "English - India",
            self::LOCALE_ENGLISH_IRELAND => "English - Ireland",
            self::LOCALE_ENGLISH_JAMAICA => "English - Jamaica",
            self::LOCALE_ENGLISH_NZ => "English - New Zealand",
            self::LOCALE_ENGLISH_PHILLIPPINES => "English - Phillippines",
            self::LOCALE_ENGLISH_SOUTHERN_AFRICA => "English - Southern Africa",
            self::LOCALE_ENGLISH_TRINIDAD => "English - Trinidad",
            self::LOCALE_ENGLISH_US => "English - United States",
            self::LOCALE_ESTONIAN => "Estonian",
            self::LOCALE_FARSI => "Farsi - Persian",
            self::LOCALE_FINNISH => "Finnish",
            self::LOCALE_FRENCH_BELGIUM => "French - Belgium",
            self::LOCALE_FRENCH_CANADA => "French - Canada",
            self::LOCALE_FRENCH_FRANCE => "French - France",
            self::LOCALE_FRENCH_LUXEMBOURG => "French - Luxembourg",
            self::LOCALE_FRENCH_SWITZERLAND => "French - Switzerland",
            self::LOCALE_GAELIC_IRELAND => "Gaelic - Ireland",
            self::LOCALE_GAELIC_SCOTLAND => "Gaelic - Scotland",
            self::LOCALE_GERMAN_AUSTRIA => "German - Austria",
            self::LOCALE_GERMAN_GERMANY => "German - Germany",
            self::LOCALE_GERMAN_LICHTENSTEIN => "German - Liechtenstein",
            self::LOCALE_GERMAN_LUXEMBOURG => "German - Luxembourg",
            self::LOCALE_GERMAN_SWITZERLAND => "German - Switzerland",
            self::LOCALE_GREEK => "Greek",
            self::LOCALE_GUARANI => "Guarani - Paraguay",
            self::LOCALE_HEBREW => "Hebrew",
            self::LOCALE_HINDI => "Hindi",
            self::LOCALE_HUNGARIAN => "Hungarian",
            self::LOCALE_ICELANDIC => "Icelandic",
            self::LOCALE_INDONESIAN => "Indonesian",
            self::LOCALE_ITALIAN_ITALY => "Italian - Italy",
            self::LOCALE_ITALIAN_SWITZERLAND => "Italian - Switzerland",
            self::LOCALE_JAPANESE => "Japanese",
            self::LOCALE_KOREAN => "Korean",
            self::LOCALE_LATVIAN => "Latvian",
            self::LOCALE_MALTESE => "Maltese",
            self::LOCALE_MOGOLIAN => "Mongolian",
            self::LOCALE_NORWEGIAN => "Norwegian - Bokml",
            self::LOCALE_POLISH => "Polish",
            self::LOCALE_PORTUGUESE_BRAZIL => "Portuguese - Brazil",
            self::LOCALE_PORTUGUESE_PORTUGAL => "Portuguese - Portugal",
            self::LOCALE_PUNJABI => "Punjabi",
            self::LOCALE_ROMANIAN_MOLDOVA => "Romanian - Moldova",
            self::LOCALE_ROMANIAN_ROMANIA => "Romanian - Romania",
            self::LOCALE_RUSSIAN => "Russian",
            self::LOCALE_RUSSIAN_MOLDOVA => "Russian - Moldova",
            self::LOCALE_SERBIAN => "Serbian - Cyrillic",
            self::LOCALE_SLOVAK => "Slovak",
            self::LOCALE_SLOVENIAN => "Slovenian",
            self::LOCALE_SOMALI => "Somali",
            self::LOCALE_SPANISH_ARGENTINA => "Spanish - Argentina",
            self::LOCALE_SPANISH_BOLIVIA => "Spanish - Bolivia",
            self::LOCALE_SPANISH_CHILE => "Spanish - Chile",
            self::LOCALE_SPANISH_COLOMBIA => "Spanish - Colombia",
            self::LOCALE_SPANISH_COSTA_RICA => "Spanish - Costa Rica",
            self::LOCALE_SPANISH_DOMINICAN_REP => "Spanish - Dominican Republic",
            self::LOCALE_SPANISH_ECUADOR => "Spanish - Ecuador",
            self::LOCALE_SPANISH_EL_SALVADOR => "Spanish - El Salvador",
            self::LOCALE_SPANISH_GUATEMALA => "Spanish - Guatemala",
            self::LOCALE_SPANISH_HONDURAS => "Spanish - Honduras",
            self::LOCALE_SPANISH_MEXICO => "Spanish - Mexico",
            self::LOCALE_SPANISH_NICARAGUA => "Spanish - Nicaragua",
            self::LOCALE_SPANISH_PANAMA => "Spanish - Panama",
            self::LOCALE_SPANISH_PARAGUAY => "Spanish - Paraguay",
            self::LOCALE_SPANISH_PERU => "Spanish - Peru",
            self::LOCALE_SPANISH_PUERTO_RICO => "Spanish - Puerto Rico",
            self::LOCALE_SPANISH_SPAIN => "Spanish - Spain (Traditional)",
            self::LOCALE_SPANISH_URUGUAY => "Spanish - Uruguay",
            self::LOCALE_SPANISH_VENEZUELA => "Spanish - Venezuela",
            self::LOCALE_SWEDISH_FINLAND => "Swedish - Finland",
            self::LOCALE_SWEDISH_SWEDEN => "Swedish - Sweden",
            self::LOCALE_THAI => "Thai",
            self::LOCALE_TIBETAN => "Tibetan",
            self::LOCALE_TURKISH => "Turkish",
            self::LOCALE_UKRAINIAN => "Ukrainian",
            self::LOCALE_URDU => "Urdu",
            self::LOCALE_UZBEK => "Uzbek - Cyrillic",
            self::LOCALE_VIETNAMESE => "Vietnamese",
            self::LOCALE_WELSH => "Welsh",
            self::LOCALE_XHOSA => "Xhosa",
            self::LOCALE_YIDDISH => "Yiddish",
            self::LOCALE_ZULU => "Zulu"
        ];
    }

}
