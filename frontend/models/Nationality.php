<?php

namespace frontend\models;

use Yii;

class Nationality {

    const STATE_ITALY = "italy";
    const STATE_SPAIN = "spain";
    const STATE_ENGLAND = "england";
    const STATE_DENMARK = "denmark";
    const STATE_ISREAL = "israel";
    const STATE_PARAGUAI = "paraguai";
    const STATE_INDIA = "india";
    const STATE_MONGOLIA = "mongolia";
    const STATE_MEXICO = "mexico";

    public static function nationalityLabels() {
        return [
            self::STATE_ITALY => "Italiana",
            self::STATE_SPAIN => "Española",
            self::STATE_ENGLAND => "English",
            self::STATE_DENMARK => "Dansk",
            self::STATE_ISREAL => "לאום ישראלי",
            self::STATE_PARAGUAI => "Paraguaya",
            self::STATE_INDIA => "भारतीय राष्ट्रीयता",
            self::STATE_MONGOLIA => "Монгол үндэстэн",
            self::STATE_MEXICO => "Mexicana"
        ];
    }

}
