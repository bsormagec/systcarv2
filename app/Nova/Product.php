<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text as TexInput;
use R64\NovaFields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Panel;
use R64\NovaFields\Row;
use R64\NovaFields\Number;
use R64\NovaFields\Select;
use R64\NovaFields\BelongsTo;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\tenant\Warehouse;
use Laravel\Nova\Fields\BelongsToMany;
class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\tenant\Product::class;
    public static $with = ['category','subcategory'];
    public static $group = 'Inventario';

    public static function label()
    {
        return ('Productos');
    }
    public static function singularlabel()
    {
        return ('Producto');
    }

    // public static function indexQuery(NovaRequest $request, $query)
    // {
    //     return $query->whereHas('warehouses', function ($query) use ($request) {
    //         $query->where('warehouse_id',1);
    //     })->get();
    // }
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
        'id','nombre'
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
            NovaBelongsToDepend::make('Categoria','category','App\Nova\Category')
                                ->placeholder('Seleccione una categoria')
                                ->options(\App\Models\tenant\Category::where('type','item')->get())
                                ->rules('required'),
            NovaBelongsToDepend::make('Sub-Categoria','subcategory','App\Nova\SubCategory')
                                ->placeholder('Primero seleccione una categoria')
                                ->optionsResolve(function ($category) {
                                    //return $especie->razas()->get(['id','nombre']);
                                    return \App\Models\tenant\SubCategory::whereHas('category', function ($q) use ($category) {
                                        $q->where('category_id', $category->id);
                                    })->get();
                                })
                                ->dependsOn('Category')
                                ->rules('required'),
            BelongsToMany::make('Almacen','warehouses','App\Nova\Warehouse')
            ->fields(function () {
                return [
                    Text::make('Stock')
                ];
            }),
            
           // BelongsTo::make('Sub-Categoria','subcategory','App\Nova\SubCategory')->searchable(),
            TexInput::make(__('Nombre'), 'nombre')->rules('required'),
            Slug::make('Slug')->from('nombre')->separator('-'),
            Textarea::make(__('Descripcion'), 'descripcion_small')->rows(3),
            Text::make(__('Lote'), 'lote'),
            Image::make(__('Imagen'),'imagen')->prunable(),
            Text::make(__('Stock '))->default(function(){
                return $this->depositos->sum('stock');
            })
            ->exceptOnForms(),
            Stack::make('Almacen', [
                Line::make('Almacen', function () {
                    return $this->depositos->map(function ($deposito) {
                                return '['.$deposito->warehouse->nombre.']';
                    
                    })->join(' - ');
                })->asSubTitle(),
                Text::make('Cantidad', function () {
                    return $this->depositos->map(function ($deposito) {
                        return '['.$deposito->stock.']';
                    })->join('-');
                })->textAlign('center'),
            ]),
            new Panel('Inf. Adicional', $this->addressFields()),
            new Panel('Precios de venta', $this->pricesofSalesFields())
        ];
    }
    
    protected function addressFields()
    {
        return [
            Date::make(__('Fecha Expiracion.'),'fecha_expir')->format('DD MMM'),
            Textarea::make(__('Dosis'), 'dosis')->rows(3),
            Textarea::make(__('Indicaciones'), 'indicaciones')->rows(3),
           
        ];
    }
    protected function pricesofSalesFields()
    {
        return [
            Row::make('Precios', [
                Number::make('Precio')->step(0.01),
                Select::make('Unidad-Medida','unit_id')
                  ->options(\App\Models\tenant\Unit::pluck('nombre','id')),
               // Number::make('Precio-minimo'),
                
                Number::make('Cantidad unidad','cantidad_unidad')
            ],'prices')
              ->onlyOnForms()
              ->fillUsing(function ($request, $model) {
                $model->prices = json_encode($request->prices);
                })
              ->fieldClasses('w-full')
              ->childConfig([
                'fieldClasses' => 'w-full px-4 py-2',
                'hideLabelInForms' => true,
              ])
            ->prepopulateRowWhenEmpty()
            ->addRowButtonClasses('btn btn-primary p-3 rounded cursor-pointer')
            //->addRowText('nueva fila')
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
        return [
            new Filters\WarehouseType
        ];
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
            new Actions\IncomeProduct,
            new Actions\ExpenseProduct
        ];
    }
}
