<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TicketRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TicketCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TicketCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Ticket::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ticket');
        CRUD::setEntityNameStrings('ticket', 'tickets');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set Fields from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        $this->crud->addColumn([
            'name' => 'id',
            'label' => "Mã vé",
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
            'name' => 'refund_id',
            'label' => "Mã trả vé",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'exchange_id',
            'label' => "Mã đổi vé",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'schedule_id',
            'label' => "Mã lịch trình",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'price',
            'label' => "Giá vé",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'discount_price',
            'label' => "Giảm giá",
            'type' => 'Number',
            'decimal' => 2,
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
        CRUD::setValidation(TicketRequest::class);
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
            'name' => 'refund_id',
            'label' => "Mã trả vé",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'exchange_id',
            'label' => "Mã đổi vé",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'schedule_id',
            'label' => "Mã lịch trình",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'price',
            'label' => "Giá vé",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'discount_price',
            'label' => "Giảm giá",
            'type' => 'Number',
            'decimal' => 2,
            'attributes' => [
                'step' => '0.01',
                'min' => '0',
            ],
        ]);
        $this->crud->addField([
            'name' => 'ticket_status',
            'label' => "Trạng thái vé",
            'type' => 'Number',
            'options' => [
                -1 => 'Không hiệu lực',
                1 => 'Hiệu lực',
            ],
            'default' => 0,
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
