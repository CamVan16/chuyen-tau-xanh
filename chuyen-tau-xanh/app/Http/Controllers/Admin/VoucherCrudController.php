<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VoucherRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class VoucherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VoucherCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Voucher::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/voucher');
        CRUD::setEntityNameStrings('voucher', 'vouchers');
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
            'label' => "Mã Voucher",
            'type' => 'Number',
            'display' => function ($value) {
                return (string) $value;  // Chuyển ID voucher thành chuỗi
            }
        ]);

        $this->crud->addColumn([
            'name' => 'code',
            'label' => "Mã code",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'label' => "Tên Voucher",
            'type' => 'Text',
        ]);

        $this->crud->addColumn([
            'name' => 'min_price_order',
            'label' => "Giá trị đơn hàng tối thiểu",
            'type' => 'Number',
            'decimals' => 2,  // Định dạng số thập phân
        ]);

        $this->crud->addColumn([
            'name' => 'percent',
            'label' => "Phần trăm giảm giá",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'max_price_discount',
            'label' => "Giảm giá tối đa",
            'type' => 'Number',
            'decimals' => 2,  // Định dạng số thập phân
        ]);

        $this->crud->addColumn([
            'name' => 'type',
            'label' => "Loại voucher",
            'type' => 'select_from_array',
            'options' => [
                0 => 'Tất cả',
                1 => 'Sinh viên',
                2 => 'Trẻ em',
            ],
            'default' => '0', // Giá trị mặc định nếu không có
        ]);

        $this->crud->addColumn([
            'name' => 'from_date',
            'label' => "Ngày bắt đầu",
            'type' => 'Date',
            'format' => 'd/m/Y',  // Định dạng ngày theo yêu cầu
        ]);

        $this->crud->addColumn([
            'name' => 'to_date',
            'label' => "Ngày kết thúc",
            'type' => 'Date',
            'format' => 'd/m/Y',  // Định dạng ngày theo yêu cầu
        ]);

        $this->crud->addColumn([
            'name' => 'quantity',
            'label' => "Số lượng voucher",
            'type' => 'Number',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => "Mô tả",
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
        CRUD::setValidation(VoucherRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'code',
            'label' => "Mã code",
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => "Tên Voucher",
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'min_price_order',
            'label' => "Giá trị đơn hàng tối thiểu",
            'type' => 'number',
        ]);

        $this->crud->addField([
            'name' => 'percent',
            'label' => "Phần trăm giảm giá",
            'type' => 'number',
            'attributes' => ["min" => 0, "max" => 100],
        ]);

        $this->crud->addField([
            'name' => 'max_price_discount',
            'label' => "Giảm giá tối đa",
            'type' => 'number',
        ]);

        $this->crud->addField([
            'name' => 'type',
            'label' => "Loại voucher",
            'type' => 'select_from_array',
            'options' => [
                0 => 'Tất cả',
                1 => 'Sinh viên',
                2 => 'Trẻ em',
            ],
        ]);

        $this->crud->addField([
            'name' => 'from_date',
            'label' => "Ngày bắt đầu",
            'type' => 'date',
        ]);

        $this->crud->addField([
            'name' => 'to_date',
            'label' => "Ngày kết thúc",
            'type' => 'date',
        ]);

        $this->crud->addField([
            'name' => 'quantity',
            'label' => "Số lượng voucher",
            'type' => 'number',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => "Mô tả",
            'type' => 'textarea',
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
