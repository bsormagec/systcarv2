<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Medico extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Contact::class;
    
    public static function group () {
        return __('Clinica');
    }
    
    public static function label()
    {
        return __('Medicos');
    }
    public static function singularlabel()
    {
        return __('Medico');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'fullName';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','name','lastName','ci'
    ];
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', 'medic');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Nombre','name'),
            Text::make('Apellido','lastName'),
            BelongsTo::make('Especialidad','speciality','App\Nova\Speciality')
                       ->searchable(),
            Text::make('Direccion','address'),
            Text::make('Celular','phone'),
            BelongsTo::make('Tipo. Doc','typedocument','App\Nova\TypeDocument'),
            Text::make('Documento','ci'),
            Text::make('Email')
                  ->sortable()
                  ->rules('required', 'email', 'max:254')
                  ->creationRules('unique:contacts,email')
                  ->updateRules('unique:contacts,email,{{resourceId}}'),
            Hidden::make('Type', 'type')->default(function ($request) {
                return 'medic';
            }),
            Hidden::make('sucursal_id')->default(function ($request) {
                return session()->get('sucursal');
            })
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
