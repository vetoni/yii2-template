<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class TranslatableBehavior
 * @package common\behaviors
 *
 * @property ActiveRecord $owner
 */
class TranslatableBehavior extends Behavior
{
    /**
     * @var string the translations relation name
     */
    public $translationRelation = 'translations';

    /**
     * @var string the translations model language attribute name
     */
    public $translationLanguageAttribute = 'language';

    /**
     * @var string[] the list of attributes to be translated
     */
    public $translationAttributes;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete'
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->translationAttributes === null) {
            throw new InvalidConfigException('The "translationAttributes" property must be set.');
        }
    }

    /**
     * Returns the translation model for the specified language.
     * @param string|null $language
     * @return ActiveRecord
     */
    public function translate($language = null)
    {
        return $this->getTranslation($language);
    }

    /**
     * Returns the translation model for the specified language.
     * @param string|null $language
     * @return ActiveRecord
     */
    public function getTranslation($language = null)
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        /* @var ActiveRecord[] $translations */
        $translations = $this->getTranslations();

        foreach ($translations as $translation) {
            if ($translation->getAttribute($this->translationLanguageAttribute) === $language) {
                return $translation;
            }
        }

        /* @var ActiveRecord $class */
        $class = $this->owner->getRelation($this->translationRelation)->modelClass;
        /* @var ActiveRecord $translation */
        $translation = new $class();
        $translation->setAttribute($this->translationLanguageAttribute, $language);
        $this->_translations[$language] = $translation;
        //$this->owner->populateRelation($this->translationRelation, $translations);

        return $translation;
    }

    /**
     * Returns a value indicating whether the translation model for the specified language exists.
     * @param string|null $language
     * @return boolean
     */
    public function hasTranslation($language = null)
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        /* @var ActiveRecord $translation */
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getAttribute($this->translationLanguageAttribute) === $language) {
                return true;
            }
        }

        return false;
    }


    /**
     * Triggers just before validation starts
     */
    public function beforeValidate()
    {
        $modelClass = $this->owner->getRelation($this->translationRelation)->modelClass;
        $shortName = (new \ReflectionClass($modelClass))->getShortName();
        if (isset($_POST[$shortName])) {
            $this->setTranslations($_POST[$shortName]);
        }

        foreach ($this->getTranslations() as $translation) {
            $owner = $this->owner;
            if ($translation->attributes['language'] == Yii::$app->language
                && !StringHelper::endsWith($owner::className(), 'Search')
                && !$translation->validate()
            ) {
                $this->owner->addErrors($translation->errors);
            }
        }
    }

    /**
     * @return void
     */
    public function afterSave()
    {
        /* @var ActiveRecord $translation */
        foreach ($this->getTranslations() as $translation) {
            $this->owner->link($this->translationRelation, $translation);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return (in_array($name, $this->translationAttributes) || $name == 'translations') ?: parent::canGetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return (in_array($name, $this->translationAttributes) || $name == 'translations') ?: parent::canSetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name == $this->translationRelation) {
            return $this->getTranslations();
        } else {
            return $this->getTranslation()->getAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($name == $this->translationRelation) {
            $this->setTranslations($value);
        } else {
            $translation = $this->getTranslation();
            $translation->setAttribute($name, $value);
        }
    }

    /**
     * @var ActiveRecord[]
     */
    protected $_translations;

    /**
     * @return ActiveRecord[]
     */
    public function getTranslations()
    {
        if (!$this->_translations) {
            $this->_translations = ArrayHelper::index($this->owner->{$this->translationRelation}, 'language');
        }
        return $this->_translations;
    }

    /**
     * @param ActiveRecord[] $value
     */
    public function setTranslations($value)
    {
        $translations = $this->getTranslations();

        foreach ($value as $language => $translation) {
            $relationInfo = $this->owner->getRelation($this->translationRelation);
            $className = $relationInfo->modelClass;
            $shortName = (new \ReflectionClass($className))->getShortName();
            if (isset($translations[$language])) {
                $translations[$language]->load([$shortName => $translation]);
            } else {
                $relationInfo = $this->owner->getRelation($this->translationRelation);
                $link = array_keys($relationInfo->link)[0];
                $translation[$link] = $this->owner->primaryKey;
                $translation[$this->translationLanguageAttribute] = $language;
                $translations[$language] = new $className($translation);
            }
        }
        $this->_translations = $translations;
    }

    /**
     * Triggers before deleting record
     */
    function beforeDelete()
    {
        $this->owner->unlinkAll($this->translationRelation, true);
    }
}