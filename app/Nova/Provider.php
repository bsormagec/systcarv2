<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class Provider extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Contact::class;
   
    public static function group () {
       return __('Purchases');
    }

    public static function label()
    {
        return __('Providers');
    }
    public static function singularlabel()
    {
        return __('Provider');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'business_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','business_name','address','phone','phone','email'
    ];
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', 'provider');
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
            Text::make(__('Business Name'), 'business_name')->rules('required'),
            Textarea::make(__('Address'),'address')->rows(3),
            Text::make(__('Phone'), 'phone'),
            Text::make(__('Email'), 'email')->rules('required'),
            BelongsTo::make('Tipo Documento','typedocument','App\Nova\TypeDocument')->hideFromIndex(),
            Text::make(__('Documento'), 'ci')->sortable(),
            Hidden::make('Type', 'type')->default(function ($request) {
                return 'provider';
            }),
            Hidden::make('Sucursals', 'sucursal_id')->default(function ($request) {
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
