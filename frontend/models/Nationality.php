<?php

namespace frontend\models;

use Yii;

class Nationality {

    const STATE_ITALY = "Italiana";
    const STATE_SPAIN = "Española";
    const STATE_ENGLAND = "English";
    const STATE_DENMARK = "Dansk";
    const STATE_ISREAL = "לאום ישראלי";
    const STATE_PARAGUAI = "Paraguaya";
    const STATE_INDIA = "भारतीय राष्ट्रीयता";
    const STATE_MONGOLIA = "Монгол үндэстэн";
    const STATE_MEXICO = "Mexicana";

    public static function nationalityLabels() {
        return [
            self::STATE_ITALY => self::STATE_ITALY,
            self::STATE_SPAIN => self::STATE_SPAIN,
            self::STATE_ENGLAND => self::STATE_ENGLAND,
            self::STATE_DENMARK => self::STATE_DENMARK,
            self::STATE_ISREAL => self::STATE_ISREAL,
            self::STATE_PARAGUAI => self::STATE_PARAGUAI,
            self::STATE_INDIA => self::STATE_INDIA,
            self::STATE_MONGOLIA => self::STATE_MONGOLIA,
            self::STATE_MEXICO => self::STATE_MEXICO
        ];
    }

}
