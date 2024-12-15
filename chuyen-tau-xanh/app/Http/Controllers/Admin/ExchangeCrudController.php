<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExchangeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExchangeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExchangeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Exchange::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/exchange');
        CRUD::setEntityNameStrings('exchange', 'exchanges');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set s from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        $this->crud->addColumn([
            'name' => 'id',
            'label' => "Mã đổi vé",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'old_ticket_id',
            'label' => "Mã vé cũ",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'new_ticket_id',
            'label' => "Mã vé mới",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'booking_id',
            'label' => "Mã đặt vé",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'customer_id',
            'label' => "Mã khách hàng",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'old_price',
            'label' => "Giá vé cũ",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'new_price',
            'label' => "Giá vé mới",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'additional_price',
            'label' => "Tiền phải trả",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'payment_method',
            'label' => "Phương thức thanh toán",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'exchange_status',
            'label' => "Trạng thái đổi vé",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'exchange_time',
            'label' => "Thời gian đổi vé",
            'type' => 'Date',
        ]);
        $this->crud->addColumn([
            'name' => 'exchange_time_processed',
            'label' => "Thời gian đổi vé thành công",
            'type' => 'Date',
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
        CRUD::setValidation(ExchangeRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'old_ticket_id',
            'label' => "Mã vé cũ",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'new_ticket_id',
            'label' => "Mã vé mới",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'booking_id',
            'label' => "Mã đặt vé",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'customer_id',
            'label' => "Mã khách hàng",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'old_price',
            'label' => "Giá vé cũ",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'new_price',
            'label' => "Giá vé mới",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'additional_price',
            'label' => "Tiền phải trả",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'payment_method',
            'label' => "Phương thức thanh toán",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'exchange_status',
            'label' => "Trạng thái đổi vé",
            'type' => 'select_from_array',
            'options' => [
                'pending' => 'Chờ xử lý',
                'completed' => 'Hoàn thành',
                'rejected' => 'Thất bại',
            ],
            'default' => 'pending',
        ]);
        $this->crud->addField([
            'name' => 'exchange_time',
            'label' => "Thời gian đổi vé",
            'type' => 'Date',
        ]);
        $this->crud->addField([
            'name' => 'exchange_time_processed',
            'label' => "Thời gian đổi vé thành công",
            'type' => 'Date',
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
