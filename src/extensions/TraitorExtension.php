<?php

namespace Clesson\Traitor\Extensions;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Configurable;

/**
 * Extends a data object with the possibility to save the original author and the author of the last change.
 * Note: For versioned data objects, the Versioned extension saves the author anyway.
 *
 * @method Member|string|null Creator()
 * @method Member|string|null Editor()
 * @property int $LastEditedByID ID of the member of the last update
 * @property string $LastEditedBy ID of object, if deleted
 * @property int $CreatedByID ID of the member when the record was initially saved (original author)
 * @property string $CreatedBy ID of object, if deleted
 */
class TraitorExtension extends DataExtension
{

    use Configurable;

    /**
     * @var array
     * @config
     */
    private static $db = [
        'LastEditedByID' => 'Int',
        'LastEditedBy' => 'Varchar',
        'CreatedByID' => 'Int',
        'CreatedBy' => 'Varchar',
    ];

    /**
     * Get the author of the last update of this entry.
     *
     * @return Member|string|null
     */
    public function Editor()
    {
        /** @var Member $member */
        if ($member = DataObject::get_by_id(Member::class, $this->owner->LastEditedByID)) {
            return $member;
        }
        return $this->owner->LastEditedBy;
    }

    /**
     * Get the original author of this entry.
     *
     * @return Member|string|null
     */
    public function Creator()
    {
        /** @var Member $member */
        if ($member = DataObject::get_by_id(Member::class, $this->owner->CreatedByID)) {
            return $member;
        }
        return $this->owner->CreatedBy;
    }

    /**
     * Save the current author and, if the record does not yet exist, the original author.
     *
     * @return void
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $traitorField = $this->traitorField();
        if ($member = Security::getCurrentUser()) {
            $this->owner->LastEditedByID = $member->ID;
            $this->owner->LastEditedBy = $member->$traitorField;
            if (!$this->owner->exists()) {
                $this->owner->CreatedByID = $member->ID;
                $this->owner->CreatedBy = $member->$traitorField;
            }
        }
    }

    /**
     *
     * @return string|void
     */
    private function traitorField()
    {
        $configuredTraitorField = static::config()->traitor_field;
        return $configuredTraitorField ? $configuredTraitorField : 'Title';
    }

}
