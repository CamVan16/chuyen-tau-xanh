<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RefundRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RefundCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RefundCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Refund::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/refund');
        CRUD::setEntityNameStrings('refund', 'refunds');
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
            'label' => "Mã trả vé",
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
            'name' => 'refund_amount',
            'label' => "Tiền phải trả",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'payment_method',
            'label' => "Phương thức thanh toán",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'refund_status',
            'label' => "Trạng thái trả vé",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'refund_time',
            'label' => "Thời gian trả vé",
            'type' => 'Datetime',
        ]);
        $this->crud->addColumn([
            'name' => 'refund_time_processed',
            'label' => "Thời gian trả vé thành công",
            'type' => 'Datetime',
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
        CRUD::setValidation(RefundRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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
            'name' => 'refund_amount',
            'label' => "Tiền phải trả",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'payment_method',
            'label' => "Phương thức thanh toán",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'refund_status',
            'label' => 'Trạng thái trả vé',
            'type' => 'select_from_array',
            'options' => [
                'pending' => 'Chờ xử lý',
                'confirmed' => 'Đã xác nhận',
                'completed' => 'Hoàn thành',
                'rejected' => 'Thất bại',
            ],
            'default' => 'pending',
        ]);
        $this->crud->addField([
            'name' => 'refund_time',
            'label' => "Thời gian trả vé",
            'type' => 'Datetime',
        ]);
        $this->crud->addField([
            'name' => 'refund_time_processed',
            'label' => "Thời gian trả vé thành công",
            'type' => 'Datetime',
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
