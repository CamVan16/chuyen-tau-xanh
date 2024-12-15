<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Car::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/car');
        CRUD::setEntityNameStrings('car', 'cars');
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
            'label' => "Mã Xe",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'train_id',
            'label' => "Mã Tàu",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'car_index',
            'label' => "Số hiệu toa",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'car_name',
            'label' => "Tên toa xe",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'car_code',
            'label' => "Mã toa xe",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'car_layout',
            'label' => "Bố trí toa",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'car_description',
            'label' => "Mô tả toa",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'num_of_seats',
            'label' => "Số ghế",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'num_of_available_seats',
            'label' => "Số ghế còn trống",
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
        CRUD::setValidation(CarRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        CRUD::addField([
            'name' => 'train_id',
            'label' => 'Mã Tàu',
            'type' => 'select2',
            'entity' => 'train',
            'attribute' => 'train_name',
            'model' => "App\Models\Train",
            'default' => null, // Có thể thiết lập mặc định
        ]);

        CRUD::addField([
            'name' => 'car_index',
            'label' => 'Số hiệu toa',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'car_name',
            'label' => 'Tên toa xe',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'car_code',
            'label' => 'Mã toa xe',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'car_layout',
            'label' => 'Bố trí toa',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'car_description',
            'label' => 'Mô tả toa',
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'num_of_seats',
            'label' => 'Số ghế',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'num_of_available_seats',
            'label' => 'Số ghế còn trống',
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
