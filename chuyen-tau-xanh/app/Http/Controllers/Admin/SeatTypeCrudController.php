<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SeatTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SeatTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SeatTypeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SeatType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/seat-type');
        CRUD::setEntityNameStrings('seat type', 'seat types');
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
            'label' => "Mã loại ghế",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'train_id',
            'label' => "Mã tàu",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'seat_type_code',
            'label' => "Mã loại ghế",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'seat_type_name',
            'label' => "Tên loại ghế",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'price',
            'label' => "Giá ghế",
            'type' => 'Number',
            'decimal' => 2, // Chỉ định hiển thị 2 chữ số sau dấu phẩy
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
        CRUD::setValidation(SeatTypeRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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
