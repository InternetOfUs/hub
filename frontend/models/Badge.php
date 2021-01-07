<?php
namespace frontend\models;

/**
 * A Badge that can be won by users while interacting with and using the WeNet applications. 
 */
class Badge {

    public $name;
    public $description;
    public $criteria;
    public $imageUrl;
    public $createdAtDt;

    function __construct($name, $description, $criteria, $imageUrl, $createdAtDt) {
        $this->name = $name;
        $this->desc = $description;
        $this->criteria = $criteria;
        $this->imageUrl = $imageUrl;
        $this->createdAtDt = $createdAtDt;
    }

    /**
     * Parse a datetime formatted string into a \DateTime object.
     *
     * @param  string $dtString The datetime formatted string to parse
     * @param  string $timezone The date time that should be applied duting parsing (optional)
     * @return \DateTime           The \DateTime resulting from the parsing
     */
    private static function parseIsoDateIntoDatetime($dtString, $timezone=null) {
        $formats = [
            "Y-m-d\TH:i:sP",
            "Y-m-d H:i:s",
            "Y-m-d\TH:i:s",
            "Y-m-d\TH:i:s.u",
            "Y-m-d\TH:i:s.uP",
            "Y-m-d\TH:i:sP",
        ];
        $results = [];
        foreach ($formats as $format) {
            if ($timezone) {
                $option = \DateTime::createFromFormat($format, $dtString, new \DateTimeZone($timezone));
            } else {
                $option = \DateTime::createFromFormat($format, $dtString);
            }

            if ($option) {
                $results[] = $option;
                break;
            }
        }

        $success = array_values(array_filter($results));
        if(count($success) > 0) {
            return $success[0];
        } else {
            Yii::warning('Could not parse date time string ['.$dtString.'] into Datetime', 'carborem.dateUtils');
        }
        return null;
    }

    /**
     * Create Badge instance from array representation
     *
     * @param  array $rawData The array badge representation
     * @return Badge          The Badge instance
     */
    public static function fromRepr($rawData) {
        $createdAtDt = Badge::parseIsoDateIntoDatetime($rawData['createdAt']);
        return new Badge(
            $rawData['name'],
            $rawData['description'],
            $rawData['criteriaNarrative'],
            $rawData['image'],
            $createdAtDt
        );
    }

}
