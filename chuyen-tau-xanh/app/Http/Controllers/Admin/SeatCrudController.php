<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SeatRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SeatCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SeatCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Seat::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/seat');
        CRUD::setEntityNameStrings('seat', 'seats');
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
            'label' => "Mã ghế",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'car_id',
            'label' => "Tên Toa",
            'type' => 'relationship',
            'attribute' => 'car_name',
        ]);

        $this->crud->addColumn([
            'name' => 'seat_type_id',
            'label' => "Tên loại ghế",
            'type' => 'relationship',
            'attribute' => 'seat_type_name',
        ]);

        $this->crud->addColumn([
            'name' => 'seat_type',
            'label' => "Tên loại ghế",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'seat_index',
            'label' => "Số hiệu ghế",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'seat_status',
            'label' => "Trạng thái ghế",
            'type' => 'Number',
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
        CRUD::setValidation(SeatRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */

        $this->crud->addField([
            'name' => 'car_id',
            'label' => 'Toa',
            'type' => 'relationship',
            'attribute' => 'car_name',
        ]);

        $this->crud->addField([
            'name' => 'seat_type_id',
            'label' => 'Loại ghế',
            'type' => 'relationship',
            'attribute' => 'seat_type_name',
        ]);

        $this->crud->addField([
            'name' => 'seat_index',
            'label' => 'Số hiệu ghế',
            'type' => 'number',
        ]);

        $this->crud->addField([
            'name' => 'seat_status',
            'label' => 'Trạng thái ghế',
            'type' => 'Number',
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
