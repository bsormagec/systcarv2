<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Carbon\Carbon;
use App\Models\tenant\AccountReceivable;
class AccountDetail extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\AccountDetail::class;
    public static $displayInNavigation = false;

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
            Currency::make(__('Amount'), 'amount')
                     ->rules('required')
                     ->currency('BOB'),
            Date::make(__('Date'),'date')
                 ->exceptOnForms(),
            Textarea::make(__('Detail'),'detail')
                    ->rules('required')
                    ->rows(3),
            BelongsTo::make(__('User'),'user','App\Nova\User')
                      ->viewable(false)
                      ->exceptOnForms(),
            Hidden::make('User', 'user_id')->default(function ($request) {
                return $request->user()->id;
            }),
            Hidden::make('Date', 'date')->default(function ($request) {
                return Carbon::now();
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
        return [
            (new Actions\PrintfReciboPago)
            ->confirmText('Esta seguro de imprimir el recibo?')
            ->confirmButtonText('Si')
            ->cancelButtonText("Cancelar")
            ->onlyOnTableRow()
            ->canRun(function ($request, $user) {
                return $request->user()->can('printfRecibo', $user);
            }),
        ];
    }
}
