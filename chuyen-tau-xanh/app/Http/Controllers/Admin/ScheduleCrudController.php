<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ScheduleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ScheduleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ScheduleCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Schedule::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/schedule');
        CRUD::setEntityNameStrings('schedule', 'schedules');
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
            'label' => "Mã lịch trình",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'train_id',
            'label' => "Mã tàu",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'train_mark',
            'label' => "Mác tàu",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'day_start',
            'label' => "Ngày tàu chạy",
            'type' => 'Date',
        ]);
        $this->crud->addColumn([
            'name' => 'time_start',
            'label' => "Giờ tàu chạy",
            'type' => 'Time',
        ]);
        $this->crud->addColumn([
            'name' => 'day_end',
            'label' => "Ngày tàu đến",
            'type' => 'Date',
        ]);
        $this->crud->addColumn([
            'name' => 'time_end',
            'label' => "Giờ tàu đến",
            'type' => 'Time',
        ]);
        $this->crud->addColumn([
            'name' => 'station_start',
            'label' => "Ga đi",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'station_end',
            'label' => "Ga đến",
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'seat_number',
            'label' => "Số ghế",
            'type' => 'Number',
        ]);
        $this->crud->addColumn([
            'name' => 'car_name',
            'label' => "Toa",
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
        CRUD::setValidation(ScheduleRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'train_id',
            'label' => "Mã tàu",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'train_mark',
            'label' => "Mác tàu",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'day_start',
            'label' => "Ngày tàu chạy",
            'type' => 'Date',
        ]);
        $this->crud->addField([
            'name' => 'time_start',
            'label' => "Giờ tàu chạy",
            'type' => 'Time',
        ]);
        $this->crud->addField([
            'name' => 'day_end',
            'label' => "Ngày tàu đến",
            'type' => 'Date',
        ]);
        $this->crud->addField([
            'name' => 'time_end',
            'label' => "Giờ tàu đến",
            'type' => 'Time',
        ]);
        $this->crud->addField([
            'name' => 'station_start',
            'label' => "Ga đi",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'station_end',
            'label' => "Ga đến",
            'type' => 'Text',
        ]);
        $this->crud->addField([
            'name' => 'seat_number',
            'label' => "Số ghế",
            'type' => 'Number',
        ]);
        $this->crud->addField([
            'name' => 'car_name',
            'label' => "Toa",
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
