<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExchangePolicyRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExchangePolicyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExchangePolicyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ExchangePolicy::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/exchange-policy');
        CRUD::setEntityNameStrings('exchange policy', 'exchange policies');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        $this->crud->addColumn([
            'name' => 'id',
            'label' => "ID",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'min_hours',
            'label' => "Giờ tối thiểu",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'max_hours',
            'label' => "Giờ tối đa",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'exchange_fee',
            'label' => "Phí đổi vé",
            'type' => 'Number',
            'decimals' => 2,
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ExchangePolicyRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'min_hours',
            'label' => "Giờ tối thiểu",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'max_hours',
            'label' => "Giờ tối đa",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'exchange_fee',
            'label' => "Phí đổi vé",
            'type' => 'Number',
            'decimals' => 2,
            'attributes' => [
                'step' => '0.01',
                'min' => '0',
            ],
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
