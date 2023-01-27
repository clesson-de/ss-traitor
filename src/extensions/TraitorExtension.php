<?php

namespace Clesson\Traitor\Extensions;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Configurable;

/**
 * Extends a data object with the possibility to save the original author and the author of the last change.
 * Note: For versioned data objects, the Versioned extension saves the author anyway.
 *
 * This class deliberately does not use has_one, has_many ... relations for the relationships to the member
 * data object. Instead only the ID's of the members are stored.
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
     * @config
     */
    private static string $traitor_field = 'Title';

    /**
     * @var array
     * @config
     */
    private static $fixed_fields = [
        'LastEditedByID' => 'Int',
        'LastEditedBy' => 'Varchar',
        'CreatedByID' => 'Int',
        'CreatedBy' => 'Varchar',
    ];

    /**
     * @var array
     * @config
     */
    private static $defaults = [
        'LastEditedByID' => null,
        'LastEditedBy' => null,
        'CreatedByID' => null,
        'CreatedBy' => null,
    ];

    /**
     * Get the author of the last update of this entry.
     *
     * This method returns the linked member data object if possible. If this is not possible for some reason
     * (e.g. because the member no longer exists), the text representation is returned (see traitor_field
     * configuration). If it is desired that the value null is returned instead of the text representation, the
     * method must be called with $forceMember=true.
     *
     * @param bool $forceMember If true and no member data object is found, null is returned. If false (default),
     * the text representation is returned.
     * @return Member|string|null
     */
    public function Editor($forceMember=false)
    {
        /** @var Member $member */
        if ($member = DataObject::get_by_id(Member::class, $this->owner->LastEditedByID)) {
            return $member;
        }
        return $forceMember ? null : $this->owner->LastEditedBy;
    }

    /**
     * Get the original author of this entry.
     *
     * This method returns the linked member data object if possible. If this is not possible for some reason
     * (e.g. because the member no longer exists), the text representation is returned (see traitor_field
     * configuration). If it is desired that the value null is returned instead of the text representation, the
     * method must be called with $forceMember=true.
     *
     * @param bool $forceMember If true and no member data object is found, null is returned. If false (default),
     * the text representation is returned.
     * @return Member|string|null
     */
    public function Creator($forceMember=false)
    {
        /** @var Member $member */
        if ($member = DataObject::get_by_id(Member::class, $this->owner->CreatedByID)) {
            return $member;
        }
        return $forceMember ? null : $this->owner->CreatedBy;
    }

    /**
     * @inheritDoc
     */
    public function augmentDatabase()
    {
        // $tableName = DataObject::getSchema()->tableName($this->owner);
        $tableName = $this->owner->baseTable();
        $fields = [
            'LastEditedByID' => 'Int',
            'LastEditedBy' => 'Varchar',
            'CreatedByID' => 'Int',
            'CreatedBy' => 'Varchar',
        ];
        $indexes = [
            'LastEditedByID' => [
                'type' => 'index',
                'columns' => ['LastEditedByID'],
            ],
            'CreatedByID' => [
                'type' => 'index',
                'columns' => ['CreatedByID'],
            ],
        ];
        DB::require_table($tableName, $fields, $indexes, true, null);
    }

    /**
     * Save the current author and, if the record does not yet exist, the original author.
     *
     * @inheritDoc
     */
    public function augmentWrite(&$manipulation)
    {
        if ($member = Security::getCurrentUser()) {
            $traitorField = $this->traitorField();
            $tableName = DataObject::getSchema()->tableName($this->owner);
            $manipulation[$tableName]['fields']['LastEditedByID'] = $member->ID;
            $manipulation[$tableName]['fields']['LastEditedBy'] = $member->$traitorField;
            if (
                isset($manipulation[$tableName]['command']) &&
                $manipulation[$tableName]['command'] == 'insert'
            ) {
                $manipulation[$tableName]['fields']['CreatedByID'] = $member->ID;
                $manipulation[$tableName]['fields']['CreatedBy'] = $member->$traitorField;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function augmentSQL(SQLSelect $query, DataQuery $dataQuery = null)
    {
        $tableName = $this->owner->baseTable();
        $query->addSelect("\"{$tableName}\".\"LastEditedByID\"");
        $query->addSelect("\"{$tableName}\".\"LastEditedBy\"");
        $query->addSelect("\"{$tableName}\".\"CreatedByID\"");
        $query->addSelect("\"{$tableName}\".\"CreatedBy\"");
    }

    /**
     * Returns the name of the member data object property whose value will be used for the text representation
     * when saving or updating.
     *
     * @return string the name of the member data object property.
     */
    private function traitorField()
    {
        $configuredTraitorField = static::config()->get('traitor_field');
        return $configuredTraitorField ? $configuredTraitorField : 'Title';
    }

}
