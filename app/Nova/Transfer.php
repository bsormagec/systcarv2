<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transfer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Transfer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            ID::make(__('ID'), 'id')->sortable(),
            Select::make('De Cuenta', 'expense_account_id')
                    ->options(\App\Models\tenant\Account::where('type','accounts')->pluck('name', 'id'))
                    ->sortable()
                    ->displayUsingLabels()
                    ->rules('required')
                    ->searchable()
                    ->onlyOnForms(),
            Select::make('A Cuenta', 'income_account_id')
                    ->options(\App\Models\tenant\Account::where('type','accounts')->pluck('name', 'id'))
                    ->sortable()
                    ->displayUsingLabels()
                    ->rules('required')
                    ->searchable()
                    ->onlyOnForms(),
            Currency::make('Importe','amount')
                      ->min(1)
                      ->max(1000)
                      ->step(0.01)
                      ->showOnCreating(),
            Date::make('Fecha'),
             Textarea::make('Descripcion')->rows(3),
            Select::make('Forma de Pago', 'payment')
                    ->options(\App\Models\tenant\Type::where('tipo','payment')
                                                       ->pluck('description', 'id'))
                    ->sortable()
                    ->displayUsingLabels()
                    ->rules('required')
                    ->searchable()
                    ->onlyOnForms(),
            Textarea::make('Referencia')->rows(3),
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
