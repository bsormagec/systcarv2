<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class Account extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Account::class;
    public static $group = 'Ingresos y Egresos';

    public static function label()
    {
        return __('Accounts');
    }
    public static function singularLabel()
    {
        return __('Account');
    }

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
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', 'accounts');
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
            Text::make('Nombre', 'name')->rules('required'),
            Text::make('Numero', 'number')->rules('required'),
            //Text::make('Moneda','currency_code')->rules('required'),
            Select::make('Moneda', 'currency_id')
                    ->options(\App\Models\tenant\Currency::enabled()->pluck('name', 'id'))
                    ->sortable()
                    ->displayUsingLabels()
                    ->rules('required')
                    ->searchable(),
            Currency::make('Saldo de Apertura','opening_balance')->rules('required'),
            Text::make('Nombre Banco','bank_name'),
            Text::make('Telef. Banco','bank_phone'),
            Textarea::make('Dir. Banco','bank_address')->rows(3),
            Boolean::make('Activo','enabled')
                    ->trueValue('1')
                    ->falseValue('0'),           
            Hidden::make('Sucursals', 'sucursal_id')->default(function ($request) {
                return session()->get('sucursal');
            }),
            Hidden::make('Type', 'type')->default(function ($request) {
                return 'accounts';
            }),
            Hidden::make('User', 'user_id')->default(function ($request) {
                return $request->user()->id;
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

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return view('nova::icons.icon-customer')->render();
    }

    public static function icon1(): string
    {
        return <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path fill="currentColor" d="M11.21 11.99c0-.87-.31-1.62-.93-2.23-.61-.62-1.36-.93-2.23-.93s-1.62.31-2.23.93c-.62.62-.93 1.36-.93 2.23 0 .87.31 1.62.93 2.23.62.62 1.36.93 2.23.93s1.62-.31 2.23-.93c.62-.61.93-1.35.93-2.23zm9.48 6.32c0-.43-.16-.8-.47-1.11a1.52 1.52 0 00-1.11-.47c-.43 0-.8.16-1.11.47-.31.31-.47.68-.47 1.11 0 .44.15.81.46 1.12.31.31.68.46 1.12.46.44 0 .81-.15 1.12-.46s.46-.68.46-1.12zm0-12.64c0-.43-.16-.8-.47-1.11s-.68-.47-1.11-.47c-.43 0-.8.16-1.11.47s-.47.68-.47 1.11c0 .44.15.81.46 1.12.31.31.68.46 1.12.46.44 0 .81-.15 1.12-.46.31-.31.46-.68.46-1.12zm-4.74 5.2v2.28c0 .08-.03.16-.09.24-.06.08-.12.12-.2.13l-1.91.3c-.09.29-.22.6-.4.94.28.4.65.87 1.11 1.42.06.09.09.17.09.25 0 .1-.03.18-.09.23-.19.25-.53.62-1.02 1.1-.49.49-.81.73-.97.73-.09 0-.18-.03-.26-.09l-1.41-1.1c-.3.16-.62.28-.95.38-.09.89-.19 1.53-.28 1.91-.06.2-.18.3-.37.3H6.9c-.09 0-.17-.03-.25-.09-.07-.06-.12-.13-.12-.22l-.28-1.88c-.28-.08-.59-.21-.93-.38l-1.46 1.1c-.06.06-.14.09-.25.09-.09 0-.18-.03-.26-.1-1.19-1.09-1.78-1.75-1.78-1.98 0-.07.03-.15.09-.23.08-.12.25-.33.51-.65s.45-.57.58-.75c-.19-.36-.33-.7-.43-1.01l-1.88-.3c-.08-.01-.15-.05-.21-.12s-.09-.15-.09-.24v-2.28c0-.08.03-.16.09-.24.06-.08.12-.12.2-.13l1.91-.3c.09-.29.22-.6.4-.94-.28-.4-.65-.87-1.11-1.42a.507.507 0 01-.09-.26c0-.1.03-.18.09-.25.18-.25.52-.61 1.01-1.1s.82-.73.98-.73c.09 0 .18.03.26.09L5.3 6.69c.28-.15.6-.28.95-.4.09-.89.19-1.52.28-1.9.06-.2.18-.3.37-.3h2.3c.09 0 .17.03.25.09.07.07.11.14.12.22l.28 1.89c.28.08.59.21.93.38l1.46-1.1c.07-.06.15-.09.25-.09.09 0 .18.03.26.1 1.19 1.09 1.78 1.75 1.78 1.98 0 .07-.03.14-.09.23-.1.13-.27.35-.52.67-.25.31-.43.56-.56.74.19.4.33.73.42 1.01l1.88.28c.08.02.15.06.21.13.05.08.08.16.08.25zm7.9 6.58v1.73c0 .13-.61.26-1.84.38-.1.22-.22.44-.37.64.42.93.63 1.5.63 1.7 0 .03-.02.06-.05.09-1 .58-1.51.88-1.53.88-.07 0-.26-.19-.57-.58-.31-.39-.53-.67-.64-.84-.16.02-.29.02-.37.02-.08 0-.21-.01-.37-.02-.12.17-.33.45-.64.84-.31.39-.5.58-.57.58-.02 0-.53-.29-1.53-.88a.105.105 0 01-.05-.09c0-.21.21-.77.63-1.7a3.78 3.78 0 01-.37-.64c-1.23-.12-1.84-.25-1.84-.38v-1.73c0-.13.61-.26 1.84-.38.11-.24.23-.45.37-.64-.42-.93-.63-1.5-.63-1.7 0-.03.02-.06.05-.09.03-.02.18-.1.43-.25.26-.15.5-.29.73-.42.23-.13.35-.2.37-.2.07 0 .26.19.57.57s.53.66.64.83c.16-.02.29-.02.37-.02.08 0 .21.01.37.02.42-.58.8-1.05 1.14-1.38l.07-.02c.03 0 .54.29 1.53.86.03.02.05.05.05.09 0 .21-.21.77-.63 1.7.14.19.26.4.37.64 1.23.13 1.84.26 1.84.39zm0-12.64v1.73c0 .13-.61.26-1.84.38-.1.22-.22.44-.37.64.42.93.63 1.5.63 1.7 0 .03-.02.06-.05.09-1 .58-1.51.88-1.53.88-.07 0-.26-.19-.57-.58s-.53-.67-.64-.84c-.16.02-.29.02-.37.02-.08 0-.21-.01-.37-.02-.12.17-.33.45-.64.84s-.5.58-.57.58c-.02 0-.53-.29-1.53-.88a.105.105 0 01-.05-.09c0-.21.21-.77.63-1.7a3.78 3.78 0 01-.37-.64c-1.23-.12-1.84-.25-1.84-.38V4.81c0-.13.61-.26 1.84-.38.11-.24.23-.45.37-.64-.42-.93-.63-1.5-.63-1.7 0-.03.02-.06.05-.09.03-.02.18-.1.43-.25.26-.15.5-.29.73-.42.23-.13.35-.2.37-.2.07 0 .26.19.57.57s.53.66.64.83c.16-.02.29-.02.37-.02.08 0 .21.01.37.02.42-.58.8-1.05 1.14-1.38l.07-.02c.03 0 .54.29 1.53.86.03.02.05.05.05.09 0 .21-.21.77-.63 1.7.14.19.26.4.37.64 1.23.13 1.84.26 1.84.39z"/>
            </svg>
        SVG;
    }
}
