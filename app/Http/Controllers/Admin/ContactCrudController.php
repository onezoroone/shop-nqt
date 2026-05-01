<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContactCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Contact::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings('contact', 'contacts');

        CRUD::denyAccess(['create', 'update']);
    }

    protected function setupListOperation()
    {
        CRUD::column('is_read')->type('boolean')->label('Read');
        CRUD::column('name')->type('text');
        CRUD::column('email')->type('email');
        CRUD::column('subject')->type('text')->limit(40);
        CRUD::column('created_at')->type('datetime');

        CRUD::orderBy('created_at', 'desc');

        CRUD::addButtonFromView('line', 'mark_read', 'mark_read', 'beginning');
    }

    protected function setupShowOperation()
    {
        CRUD::column('name')->type('text');
        CRUD::column('email')->type('email');
        CRUD::column('subject')->type('text');
        CRUD::column('message')->type('textarea');
        CRUD::column('is_read')->type('boolean');
        CRUD::column('created_at')->type('datetime');

        // Mark as read when viewed
        $entry = $this->crud->getCurrentEntry();
        if ($entry && ! $entry->is_read) {
            $entry->update(['is_read' => true]);
        }
    }

    /**
     * Mark a contact message as read via AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(int $id)
    {
        $contact = \App\Models\Contact::findOrFail($id);
        $contact->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'Marked as read']);
    }
}
