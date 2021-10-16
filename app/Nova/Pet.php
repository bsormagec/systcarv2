<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class Pet extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Pet::class;
    
    public static function group(){
        return __('Clinica');
    }

    public static function label()
    {
        return __('Mascotas');
    }
    public static function singularlabel()
    {
        return __('Mascota');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'nombre';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','nombre','chip'
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
            BelongsTo::make('Customer')
                        ->showCreateRelationButton()
                        ->rules('required')
                        ->searchable(),
            NovaBelongsToDepend::make('Especie')
                                ->placeholder('Seleccione una Especie')
                                ->options(\App\Models\tenant\Especie::all())
                                ->rules('required'),
            NovaBelongsToDepend::make('Raza')
                                ->placeholder('Primero seleccione una Especie')
                                ->optionsResolve(function ($especie) {
                                    //return $especie->razas()->get(['id','nombre']);
                                    return \App\Models\tenant\Raza::whereHas('especie', function ($q) use ($especie) {
                                        $q->where('especie_id', $especie->id);
                                    })->get();
                                })
                                ->dependsOn('Especie')
                                ->rules('required'),
            Text::make(__('Nombre'),'nombre')->rules('required'),
            Text::make(__('Color')),
            Select::make('Sexo')->options([
                                    'F' => 'Femenino',
                                    'M' => 'Masculino'
                                ])->displayUsingLabels(),
            Date::make('F. Nac.','fecha_nacim'),
            Number::make(__('Peso'))->step(0.01),
            Text::make(__('Chip')),
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
