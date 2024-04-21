<?php

namespace juni\twigcomponents\models;

use Craft;
use craft\base\Model;

/**
 * laravelmix settings
 */
class Settings extends Model
{
    /**
     * @var string Folder where the manifest file is located.
     */
    public string $componentDir = '_components';

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['componentDir', 'string'],
            [['componentDir'], 'required'],
        ];
    }
}
