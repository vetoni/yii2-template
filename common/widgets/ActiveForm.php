<?php

namespace common\widgets;

use yii\base\InvalidCallException;
use yii\base\Model;
use yii\bootstrap\ActiveField;
use yii\bootstrap\Html;

/**
 * Class ActiveForm
 * @package common\widgets
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    /**
     * @var
     */
    public $isNewRecord;

    /**
     * @var ActiveField[] the ActiveField objects that are currently active
     */
    private $_fields = [];

    /**
     * Runs the widget.
     * This registers the necessary JavaScript code and renders the form open and close tags.
     * @throws InvalidCallException if `beginField()` and `endField()` calls are not matching.
     */
    public function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        $content = ob_get_clean();
        $this->options['data-extended-form'] = true;
        echo Html::beginForm($this->action, $this->method, $this->options);
        echo $content;

        echo '<div class="form-group">' . $this->render('ActiveFormButtons', ['isNewRecord' => $this->isNewRecord]) . '</div>';

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }

        echo Html::endForm();
    }

    /**
     * Begins a form field.
     * This method will create a new form field and returns its opening tag.
     * You should call [[endField()]] afterwards.
     * @param Model $model the data model.
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object.
     * @return string the opening tag.
     * @see endField()
     * @see field()
     */
    public function beginField($model, $attribute, $options = [])
    {
        $field = $this->field($model, $attribute, $options);
        $this->_fields[] = $field;
        return $field->begin();
    }

    /**
     * Ends a form field.
     * This method will return the closing tag of an active form field started by [[beginField()]].
     * @return string the closing tag of the form field.
     * @throws InvalidCallException if this method is called without a prior [[beginField()]] call.
     */
    public function endField()
    {
        $field = array_pop($this->_fields);
        if ($field instanceof ActiveField) {
            return $field->end();
        }

        throw new InvalidCallException('Mismatching endField() call.');
    }
}