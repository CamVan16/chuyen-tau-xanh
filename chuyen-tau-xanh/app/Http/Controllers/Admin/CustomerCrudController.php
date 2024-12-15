<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('customer', 'customers');
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
            'name' => 'customer_name',
            'label' => "Tên khách hàng",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'customer_type',
            'label' => "Loại khách hàng",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'email',
            'label' => "Email",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'citizen_id',
            'label' => "CMND/CCCD",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'phone',
            'label' => "Số điện thoại",
            'type' => 'Text',
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
        CRUD::setValidation(CustomerRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'customer_name',
            'label' => "Tên khách hàng",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'customer_type',
            'label' => "Loại khách hàng",
            'type' => 'select_from_array',
            'options' => [
                'Người lớn' => 'Người lớn',
                'Sinh viên' => 'Sinh viên',
                'Trẻ em' => 'Trẻ em',
                'Người cao tuổi' => 'Người cao tuổi',
                'Đoàn viên Công đoàn' => 'Đoàn viên Công đoàn',
            ],
        ]);
        $this->crud->addField([
            'name' => 'email',
            'label' => "Email",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'citizen_id',
            'label' => "CMND/CCCD",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'phone',
            'label' => "Số điện thoại",
            'type' => 'Text',
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
