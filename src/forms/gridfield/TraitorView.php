<?php

namespace Clesson\Traitor\Forms\GridField;

use SilverStripe\Forms\GridField\AbstractGridFieldComponent;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;
use SilverStripe\Security\Member;
use SilverStripe\Control\Controller;

/**
 * The TraitorView class provides a simple way to display traitor properties in the GridView.
 */
class TraitorView extends AbstractGridFieldComponent implements GridField_ColumnProvider
{

    /**
     * @inheritdoc
     */
    public function augmentColumns($field, &$columns)
    {
        if (!in_array('Traitor', $columns ?? [])) {
            $columns[] = 'Traitor';
        }
    }

    /**
     * @inheritdoc
     */
    public function getColumnsHandled($field)
    {
        return ['Traitor'];
    }

    /**
     * @inheritdoc
     */
    public function getColumnContent($field, $record, $col)
    {
        if (!$record->canView()) {
            return null;
        }

        $Creator = $record->Creator();
        $Editor = $record->Editor();
        $Data = [];

        if (is_a($Creator, Member::class)) {
            $Data['Creator'] = [
                'Title' => $Creator->Title,
                'Link' => Controller::join_links('/admin/security/EditForm/field/Members/item', $Creator->ID, 'edit'),
                'Date' => $record->Created,
            ];
        } else if (is_string($Creator)) {
            $Data['Creator'] = [
                'Title' => $Creator,
                'Link' => null,
                'Date' => $record->Created,
            ];
        }

        if (is_a($Editor, Member::class)) {
            $Data['Editor'] = [
                'Title' => $Editor->Title,
                'Link' => Controller::join_links('/admin/security/EditForm/field/Members/item', $Editor->ID, 'edit'),
                'Date' => $record->LastEdited,
            ];
        } else if (is_string($Editor)) {
            $Data['Editor'] = [
                'Title' => $Editor,
                'Link' => null,
                'Date' => $record->LastEdited,
            ];
        }

        if ($Data['Creator']['Title'] == $Data['Editor']['Title'] && $Data['Creator']['Date'] == $Data['Editor']['Date']) {
            unset($Data['Editor']);
        }

        $data = new ArrayData($Data);
        $template = SSViewer::get_templates_by_class($this, '', __CLASS__);
        return $data->renderWith($template);
    }

    /**
     * @inheritdoc
     */
    public function getColumnAttributes($field, $record, $col)
    {
        return ['class' => 'grid-field__col-compact grid-field__col-traitor'];
    }

    /**
     * @inheritdoc
     */
    public function getColumnMetadata($gridField, $col)
    {
        return ['title' => null];
    }
}
