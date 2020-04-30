<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Profile extends Model {

    public $first_name;
    public $middle_name;
    public $last_name;
    public $prefix_name;
    public $suffix_name;

    public $birthdate;
    public $bd_year;
    public $bd_month;
    public $bd_day;

    public $gender;
    public $locale;
    public $nationality;

    const GENDER_M = 'M';
    const GENDER_F = 'F';
    const GENDER_O = 'O';

    const LANG_EN = 'en-US';

    /**
    * {@inheritdoc}
    */
    public function rules() {
        return [
            [['first_name', 'middle_name', 'last_name', 'prefix_name', 'suffix_name', 'gender', 'nationality', 'locale'], 'string'],
            [['bd_year', 'bd_month', 'bd_day'], 'integer']
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
            'bd_year' => Yii::t('profile', 'Year'),
            'bd_month' => Yii::t('profile', 'Month'),
            'bd_day' => Yii::t('profile', 'Day'),
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

}
