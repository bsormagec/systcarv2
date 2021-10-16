<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Http\Requests\NovaRequest;

class Box extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Account::class;
    public static $with = ['user'];
    public static $group = 'Ingresos y Egresos';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';
    
    public static function label()
    {
        return 'Cajas';
    }
    public static function singularLabel()
    {
        return 'caja';
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','name','number'
    ];
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', 'boxes');
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
            Text::make('Nombre', 'name')->sortable(),
            Text::make(__('Code'), 'number'),
            Select::make('Moneda', 'currency_id')
                    ->options(\App\Models\tenant\Currency::enabled()->pluck('name', 'id'))
                    ->sortable()
                    ->displayUsingLabels()
                    ->rules('required')
                    ->searchable(),
            Currency::make('Saldo Apertura','opening_balance'),
            Badge::make('Status')->map([
                'cerrada' => 'danger',
                'iniciada' => 'success'
            ]),
            HasMany::make('Historial','runbox','App\Nova\RunBox'),
            BelongsTo::make('Registrado por','user','App\Nova\User')
                       ->onlyOnDetail(),
            Text::make('Iniciado por', 'usuario.name')->exceptOnForms(),           
            Hidden::make('Sucursals', 'sucursal_id')->default(function ($request) {
                return session()->get('sucursal');
            }),
            Hidden::make('Type', 'type')->default(function ($request) {
                return 'boxes';
            }),
            Hidden::make('Status', 'status')->default(function ($request) {
                return 'cerrada';
            }),
            Hidden::make('User', 'user_id')->default(function ($request) {
                return $request->user()->id;
            }),
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
            (new Actions\UserRunBox)
            ->confirmText('Esta seguro de iniciar esta caja?')
            ->confirmButtonText('Si iniciar')
            ->cancelButtonText("Cancelar"),
            (new Actions\DownBox)
            ->confirmText('Esta seguro de cerrar esta caja?')
            ->confirmButtonText('Si cerrar')
            ->cancelButtonText("Cancelar"),
            new Actions\IncomeMoney,
            new Actions\ExpenseMoney
        ];
    }
}