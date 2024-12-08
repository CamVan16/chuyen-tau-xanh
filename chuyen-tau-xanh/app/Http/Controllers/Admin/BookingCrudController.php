<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookingCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Booking::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/booking');
        CRUD::setEntityNameStrings('booking', 'bookings');
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
            'label' => "Mã đặt vé",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'customer_id',
            'label' => "Mã khách hàng",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'discount_price',
            'label' => "Giảm giá",
            'type' => 'Number',
            'decimal' => 2,
        ]);
        $this->crud->addColumn([
            'name' => 'booked_time',
            'label' => "Thời gian đặt vé",
            'type' => 'Date',
        ]);
        $this->crud->addColumn([
            'name' => 'booking_status',
            'label' => "Trạng thái",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'total_price',
            'label' => "Tổng giá",
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
        CRUD::setValidation(BookingRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'customer_id',
            'label' => "Mã khách hàng",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'discount_price',
            'label' => "Giảm giá",
            'type' => 'Number',
            'decimal' => 2,
        ]);
        $this->crud->addField([
            'name' => 'booked_time',
            'label' => "Thời gian đặt vé",
            'type' => 'Date',
        ]);
        $this->crud->addField([
            'name' => 'booking_status',
            'label' => "Trạng thái",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'total_price',
            'label' => "Tổng giá",
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
