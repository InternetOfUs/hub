<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Profile extends Model {

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

    const GENDER_M = 'M';
    const GENDER_F = 'F';
    const GENDER_O = 'O';

    const LANG_EN = 'en';

    /**
    * {@inheritdoc}
    */
    public function rules() {
        return [
            [['first_name', 'middle_name', 'last_name', 'prefix_name', 'suffix_name', 'gender', 'nationality', 'locale', 'birthdate'], 'string'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels() {
        return [
            'first_name' => Yii::t('profile', 'First name'),
            'middle_name' => Yii::t('profile', 'Middle name'),
            'last_name' => Yii::t('profile', 'Last name'),
            'prefix_name' => Yii::t('profile', 'Prefix name'),
            'suffix_name' => Yii::t('profile', 'Suffix name'),
            'birthdate' => Yii::t('profile', 'Birthdate'),
            'gender' => Yii::t('profile', 'Gender'),
            'nationality' => Yii::t('profile', 'Nationality'),
            'locale' => Yii::t('profile', 'Language'),
        ];
    }

    public static function genderLabels() {
        return [
    		self::GENDER_M => Yii::t('common', 'Male'),
    		self::GENDER_F => Yii::t('common', 'Female'),
    		self::GENDER_O => Yii::t('common', 'Other')
    	];
    }

    public static function languageLabels() {
        return [
    		self::LANG_EN => Yii::t('common', 'English')
    	];
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
            'email' => null,
            'phoneNumber' => null,
            'locale' => $this->locale,
            'nationality' => $this->nationality,
            'avatar' => null,
            'languages' => [],
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

        $day = $repr['dateOfBirth']['day'];
        $month = $repr['dateOfBirth']['month'];
        $year = $repr['dateOfBirth']['year'];
        if ($day && $month && $year) {
            $dd = new \DateTime();
            $dd->setDate($year, $month, $day);
            $model->birthdate = $dd->format('d-m-Y');
        }


        $model->gender = $repr['gender'];
        $model->locale = $repr['locale'];
        $model->nationality = $repr['nationality'];

        return $model;
    }
}