<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TrainRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TrainCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TrainCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Train::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/train');
        CRUD::setEntityNameStrings('train', 'trains');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Hiển thị cột ID
        $this->crud->addColumn([
            'name' => 'id',
            'label' => "ID Tàu",
            'type' => 'number',
        ]);

        $this->crud->addColumn([
            'name' => 'num_of_seats',
            'label' => "Số ghế",
            'type' => 'number',
        ]);

        $this->crud->addColumn([
            'name' => 'num_of_available_seats',
            'label' => "Số ghế khả dụng",
            'type' => 'number',
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
        CRUD::setValidation(TrainRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'num_of_seats',
            'label' => "Tổng số ghế",
            'type' => 'number',
        ]);

        $this->crud->addField([
            'name' => 'num_of_available_seats',
            'label' => "Số ghế khả dụng",
            'type' => 'number',
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
