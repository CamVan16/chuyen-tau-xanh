<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RouteStationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RouteStationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RouteStationCrudController extends CrudController
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
        CRUD::setModel(\App\Models\RouteStation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/route-station');
        CRUD::setEntityNameStrings('route station', 'route stations');
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
            'name' => 'route_id',
            'label' => "Tuyến",
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'station_id',
            'label' => "ID ga",
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'station_code',
            'label' => "Mã ga",
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'station_name',
            'label' => "Tên ga",
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'km',
            'label' => "Định tuyến",
            'type' => 'number',
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
        CRUD::setValidation(RouteStationRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'route_id',
            'label' => "Tuyến",
            'type' => 'number',
        ]);
        $this->crud->addField([
            'name' => 'station_id',
            'label' => "ID ga",
            'type' => 'number',
        ]);
        $this->crud->addField([
            'name' => 'station_code',
            'label' => "Mã ga",
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'station_name',
            'label' => "Tên ga",
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'km',
            'label' => "Định tuyến",
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
