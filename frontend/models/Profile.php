<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Profile extends Model {

    const DEFAULT_LANGUAGE = 'en-US';

    public $userId;

    public $first_name;
    public $middle_name;
    public $last_name;
    public $prefix_name;
    public $suffix_name;

    public $birthdate;

    public $gender;
    public $locale;
    public $nationality;

    public $phone_number;

    const GENDER_M = 'M';
    const GENDER_F = 'F';
    const GENDER_O = 'O';

    const LANG_EN = 'en';

    /**
    * {@inheritdoc}
    */
    public function rules() {
        return [
            [['first_name', 'last_name', 'locale', 'gender'], 'required'],
            [['first_name', 'middle_name', 'last_name', 'prefix_name', 'suffix_name', 'gender', 'nationality', 'locale', 'birthdate','phone_number'], 'string'],
            [['phone_number'], 'phoneNumberValidation'],
        ];
    }

    public function phoneNumberValidation(){
        $pn = str_replace(' ', '', $this->phone_number);
        $re = '/^\+?[1-9]\d{1,14}$/';
        preg_match($re, $pn, $matches, PREG_OFFSET_CAPTURE);

        if(count($matches) < 1){
            $this->addError('phone_number', Yii::t('profile', 'Phone number format is not correct. Example: +393401234567'));
            return false;
        }
        return true;
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels() {
        return [
            'id' => Yii::t('profile', 'User ID'),
            'first_name' => Yii::t('profile', 'First name'),
            'middle_name' => Yii::t('profile', 'Middle name'),
            'last_name' => Yii::t('profile', 'Last name'),
            'prefix_name' => Yii::t('profile', 'Prefix name'),
            'suffix_name' => Yii::t('profile', 'Suffix name'),
            'birthdate' => Yii::t('profile', 'Birthdate'),
            'gender' => Yii::t('profile', 'Gender'),
            'nationality' => Yii::t('profile', 'Nationality'),
            'locale' => Yii::t('profile', 'Language'),
            'phone_number' => Yii::t('profile', 'Phone number'),
        ];
    }

    public static function genderLabels() {
        return [
    		self::GENDER_M => Yii::t('common', 'Male'),
    		self::GENDER_F => Yii::t('common', 'Female'),
    		self::GENDER_O => Yii::t('common', 'Other')
    	];
    }

    /**
     * Check if the profile contains all required fields.
     *
     * @return boolean Whether the profile is complete or not.
     */
    public function isComplete() {
        $profileFieldsToCheck = [
            'first_name',
            'last_name',
            'locale',
            'gender'
        ];

        foreach ($profileFieldsToCheck as $profileFieldToCheck) {
            if($this->{$profileFieldToCheck} == '' || $this->{$profileFieldToCheck} == null ){
                return false;
            }
        }
        return true;
    }

    public function toRepr() {
        $date = \DateTime::createFromFormat('d-m-Y', $this->birthdate);
        $db = [
            'year' => null,
            'month' => null,
            'day' => null,
        ];
        if ($date) {
            $db['year'] = intval($date->format('Y'));
            $db['month'] = intval($date->format('n'));
            $db['day'] = intval($date->format('j'));
        }

        $pn = str_replace(' ', '', $this->phone_number);

        return [
            'id' => $this->userId,
            'name' => [
                'first' => $this->first_name,
                'middle' => $this->middle_name,
                'last' => $this->last_name,
                'prefix' => $this->prefix_name,
                'suffix' => $this->suffix_name,
            ],
            'dateOfBirth' => $db,
            'gender' => $this->gender,
            'email' => Yii::$app->user->getIdentity()->email,
            'phoneNumber' => $pn,
            'locale' => $this->locale,
            'nationality' => $this->nationality,
            'avatar' => null,
            'occupation' => null,
        ];
    }

    public static function fromRepr($repr) {
        $model = new self();

        $model->userId = $repr['id'];

        $model->first_name = $repr['name']['first'];
        $model->middle_name = $repr['name']['middle'];
        $model->last_name = $repr['name']['last'];
        $model->prefix_name = $repr['name']['prefix'];
        $model->suffix_name = $repr['name']['suffix'];

        $day = isset($repr['dateOfBirth']['day']) ? $repr['dateOfBirth']['day'] : null;
        $month = isset($repr['dateOfBirth']['month']) ? $repr['dateOfBirth']['month'] : null;
        $year = isset($repr['dateOfBirth']['year']) ? $repr['dateOfBirth']['year'] : null;

        if ($day && $month && $year) {
            $dd = new \DateTime();
            $dd->setDate($year, $month, $day);
            $model->birthdate = $dd->format('d-m-Y');
        }

        $model->gender = $repr['gender'];
        $model->locale = $repr['locale'];

        if(!in_array($model->locale, Locale::locales())){
            $model->locale = null;
        }

        $model->nationality = $repr['nationality'];

        $model->phone_number = $repr['phoneNumber'];

        return $model;
    }
}
