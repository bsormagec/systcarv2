<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class AccountReceivable extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\AccountReceivable::class;
    public static $with = ['user','customer'];
    public static $group = 'Ingresos y Egresos';
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public static function label()
    {
        return 'Cta x Cobrar';
    }
    public static function singularLabel()
    {
        return 'cta x cobrar';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','quantity','detail'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('COD'), 'id')->sortable(),
            Currency::make(__('Quantity'), 'quantity')
                      ->rules('required')
                      ->currency('BOB'),
            BelongsTo::make(__('Customer'),'customer','App\Nova\Customer')->searchable(),
            Textarea::make(__('Detail'),'detail')
                      ->rules('required')
                      ->rows(3),
            Date::make(__('DeadLine'), 'deadline')->rules('required'),
            BelongsTo::make(__('User'),'user','App\Nova\User')
                       ->exceptOnForms(),
            Hidden::make('User', 'user_id')->default(function ($request) {
                return $request->user()->id;
            }),
            Boolean::make('Status')
                    ->trueValue('1')
                    ->falseValue('0'),
            Hidden::make('Sucursals', 'sucursal_id')->default(function ($request) {
                return session()->get('sucursal');
            }),
            HasMany::make(__('Detail'),'details','App\Nova\AccountDetail'),
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
