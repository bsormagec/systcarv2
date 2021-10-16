<tr>
    @stack('name_td_start')
        <td class="item">
            {{ $item->product->text }}
            @if (!empty($item->product->descripcion_small))
                <br><small>{!! \Illuminate\Support\Str::limit($item->product->descripcion_small, 500) !!}</small>
            @endif
            @stack('item_custom_fields')
            @stack('item_custom_fields_' . $item->id)
        </td>
    @stack('name_td_end')

    @stack('quantity_td_start')
            <td class="quantity">{{ $item->quantity }}-{{ $item->unit->abreviacion }}</td>
    @stack('quantity_td_end')

    @stack('price_td_start')
        <td class="price"> {{$item->sale_price}}</td>
    @stack('price_td_end')

            @stack('discount_td_start')
                <td class="discount">{{ $item->discount }}</td>
            @stack('discount_td_end')
    
    @stack('total_td_start')
            <td class="total">{{ $item->quantity *  $item->sale_price}}</td>
    @stack('total_td_end')
</tr>
