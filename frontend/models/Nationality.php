<?php

namespace frontend\models;

use Yii;

class Nationality {

    const STATE_ARGENTINA = "argentina";
    const STATE_AUSTRALIA = "australia";
    const STATE_AUSTRIA = "austria";
    const STATE_BELGIUM = "belgium";
    const STATE_BRAZIL = "brazil";
    const STATE_BULGARIA = "bulgaria";
    const STATE_CANADA = "canada";
    const STATE_CHILE = "chile";
    const STATE_CINA = "cina";
    const STATE_COLOMBIA = "colombia";
    const STATE_CROATIA = "croatia";
    const STATE_DENMARK = "denmark";
    const STATE_ENGLAND = "england";
    const STATE_FINLAND = "finland";
    const STATE_FRANCE = "france";
    const STATE_GERMANY = "germany";
    const STATE_GREECE = "greece";
    const STATE_HUNGARY = "hungary";
    const STATE_INDIA = "india";
    const STATE_IRELAND = "ireland";
    const STATE_ISREAL = "israel";
    const STATE_ITALY = "italy";
    const STATE_JAPAN = "japan";
    const STATE_MALTA = "malta";
    const STATE_MEXICO = "mexico";
    const STATE_MONGOLIA = "mongolia";
    const STATE_NETHERLANDS = "netherlands";
    const STATE_NEW_ZEALAND = "new_zeland";
    const STATE_NORWAY = "norway";
    const STATE_PARAGUAI = "paraguai";
    const STATE_PERU = "peru";
    const STATE_POLAND = "poland";
    const STATE_PORTUGAL = "portugal";
    const STATE_RUSSIA = "russia";
    const STATE_SENEGAL = "senegal";
    const STATE_SLOVAKIA = "slovakia";
    const STATE_SPAIN = "spain";
    const STATE_SWEDEN = "sweden";
    const STATE_SWITZERLAND = "switzerland";
    const STATE_TAIWAN = "taiwan";
    const STATE_THAILAND = "thailand";
    const STATE_TURKEY = "turkey";
    const STATE_UK = "uk";
    const STATE_US = "us";
    const STATE_URUGUAY = "uruguay";
    const STATE_VENEZUELA = "venezuela";

    public static function nationalityLabels() {
        return [
            self::STATE_ARGENTINA => "Argentina",
            self::STATE_AUSTRALIA => "Australian",
            self::STATE_AUSTRIA => "österreichisch",
            self::STATE_BELGIUM => "Belge",
            self::STATE_BRAZIL => "Brasileiro",
            self::STATE_BULGARIA => "български",
            self::STATE_CANADA => "Canadian",
            self::STATE_CHILE => "Chileno",
            self::STATE_CINA => "中文",
            self::STATE_COLOMBIA => "Colombiana",
            self::STATE_CROATIA => "Hrvatski",
            self::STATE_DENMARK => "Dansk",
            self::STATE_ENGLAND => "English",
            self::STATE_FINLAND => "Suomalainen",
            self::STATE_FRANCE => "Français",
            self::STATE_GERMANY => "Deutsche",
            self::STATE_GREECE => "Ελληνικά",
            self::STATE_HUNGARY => "Magyar",
            self::STATE_INDIA => "इंडियाना",
            self::STATE_IRELAND => "Gaeilge",
            self::STATE_ISREAL => "יִשׂרְאֵלִי",
            self::STATE_ITALY => "Italiana",
            self::STATE_JAPAN => "日本人",
            self::STATE_MALTA => "Maltese",
            self::STATE_MEXICO => "Mexicana",
            self::STATE_MONGOLIA => "Монгол үндэстэн",
            self::STATE_NETHERLANDS => "Nederlands",
            self::STATE_NEW_ZEALAND => "new Zealander",
            self::STATE_NORWAY => "Norsk",
            self::STATE_PARAGUAI => "Paraguaya",
            self::STATE_PERU => "Peruano",
            self::STATE_POLAND => "Polskie",
            self::STATE_PORTUGAL => "Português",
            self::STATE_RUSSIA => "русский",
            self::STATE_SENEGAL => "Sénégalais",
            self::STATE_SLOVAKIA => "Slovák",
            self::STATE_SPAIN => "Española",
            self::STATE_SWEDEN => "Svenska",
            self::STATE_SWITZERLAND => "Schweiz",
            self::STATE_TAIWAN => "台湾",
            self::STATE_THAILAND => "ไทย",
            self::STATE_TURKEY => "Türk",
            self::STATE_UK => "English",
            self::STATE_US => "American",
            self::STATE_URUGUAY => "Uruguayo",
            self::STATE_VENEZUELA => "Venezolano"
        ];
    }

}
