<div class="row" style="background-color:#55588b !important; -webkit-print-color-adjust: exact;">
    <div class="col-58">
        <div class="text company pl-2 mb-1 d-flex align-items-center">
            @stack('company_logo_start')
                <img src="img/company.png" height="128" width="128" alt="Systcarv" />
                <strong class="pl-2 text-white">{{setting('company.name')}}</strong>
            @stack('company_logo_end')
        </div>
    </div>

    <div class="col-42">
        <div class="text company">
            @stack('company_details_start')
           
                    <strong class="text-white">{!! nl2br(setting('company.address')) !!}</strong><br><br>
                
                    <strong class="text-white">
                            {{ trans('NIT') }}: {{setting('company.nit')}}
                    </strong><br><br>
                
                    <strong class="text-white">
                        {{ trans('PHONE') }}: 69658908
                    </strong><br><br>
                
                    <strong class="text-white">
                        {{ trans('EMAIL') }}:auguss24@gmail.com
                    </strong><br><br>
               
            @stack('company_details_end')
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-58">
        <div class="text company">
                <strong>{{ $invoice->electronic_bill ? trans("Factura a:") : trans("Recibo a:") }}</strong>
                <br>
            @stack('name_input_start')
                <strong>{{ $invoice->customer->fullName }}</strong>
                <br><br>
            @stack('name_input_end')

            @stack('address_input_start')
                {{ $invoice->customer->address }}
                    <br><br>
            @stack('address_input_end')
           
            @stack('tax_number_input_start')
                NIT|CI: {{ $invoice->customer->ci }}
                <br><br>
            @stack('tax_number_input_end')
            @if ($invoice->customer->phone)
                @stack('phone_input_start')
                    {{ $invoice->customer->phone }}
                    <br><br>
                @stack('phone_input_end')
            @endif
            @if ($invoice->customer->email)
                @stack('email_start')
                {{ $invoice->customer->email }}
                        <br><br>
                @stack('email_input_end')
            @endif
        </div>
    </div>

    <div class="col-42">
        <div class="text company">
            @stack('invoice_number_input_start')
                    <strong>{{ $invoice->electronic_bill ? trans("Numero de Factura") : trans("Numero de Recibo") }}:</strong>
                    <span class="float-right">{{ $invoice->invoice_number?? $invoice->id}}</span><br><br>
            @stack('invoice_number_input_end')

            @stack('cu_input_start')
                @if ($invoice->cuf)
                    <strong>{{ trans("CUF") }}:</strong>
                    <span class="float-right">{{ $invoice->cuf }}</span><br><br>
                @endif
            @stack('cu_input_end')

            @stack('issued_at_input_start')
                    <strong>Fecha:</strong>
                    <span class="float-right">{{ $invoice->date }}</span><br><br>
            @stack('issued_at_input_end')
        </div>
    </div>
</div>

<div class="row">
    <div class="col-100">
        <div class="text">
            <table class="m-lines">
                <thead style="background-color:#55588b !important; -webkit-print-color-adjust: exact;">
                    <tr>
                        @stack('name_th_start')
                            <th class="item text-left text-white">Articulos</th>
                        @stack('name_th_end')

                        @stack('quantity_th_start')
                            <th class="quantity text-white">Cantidad</th>
                        @stack('quantity_th_end')

                        @stack('price_th_start')
                            <th class="price text-white">Precio</th>
                        @stack('price_th_end')

                        @stack('discount_td_start')
                            <th class="discount text-white">Descuento</th>
                        @stack('discount_td_end')

                        @stack('total_th_start')
                                <th class="total text-white">Importe</th>
                        @stack('total_th_end')
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoice->items as $item)
                        <x-sales.template.line-item
                            :item="$item"
                        />
                    @empty
                        <tr>
                            <td colspan="5" class="text-center empty-items">
                                {{ trans('documents.empty_items') }}
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-7">
    <div class="col-58">
        <div class="text company">
            @stack('qrcode_start')
            @if($invoice->electronic_bill)
                @php
                $qr = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit='.$invoice->document.'&cuf='.$invoice->cuf.'&numero='.$invoice->id.'&t=2';
                @endphp
            @else
                @php
                $qr = 'Codigo:'.$invoice->id.' '.'Date:'.$invoice->date.' '.'Total:'.number_format($invoice->subtotal, 2, '.', '').' '.'CI|NIT:'.$invoice->document;
                @endphp
            @endif
            {!! QrCode::size(100)->generate("$qr"); !!} 
            @stack('qrcode_end')
        </div><br>
        <div class="text company">
            @stack('notes_input_start')
            @if ($invoice->observations)
                <strong>{{ trans_choice('Observaciones', 2) }}:</strong><br><br>
                {!! nl2br($invoice->observations) !!}
            @endif
            @stack('notes_input_end')
        </div>
        <div class="text company">
            @stack('monto_literal_start')
                <strong class="float-left">SON:&nbsp;&nbsp;</strong>
                <span>{!! nl2br($montoliteral) !!}</span>
            @stack('monto_literal_end')
        </div>
    </div>

    <div class="col-42 float-right text-right">
        <div class="text company pr-2">
          
            <strong class="float-left">Subtotal:</strong>
            <span>{{$invoice->amount}}</span><br><br>

            @stack('paid_discount_tr_start')
                <strong class="float-left">{{ trans('Descuento') }}:</strong>
                <span>{{$invoice->discount}}</span><br><br>
            @stack('paid_discount_tr_end')
            @if ($invoice->electronic_bill)
                @stack('paid_total_tr_start')
                    <strong class="float-left">{{ trans('Importe Base Credito Fiscal') }}:</strong>
                    <span>{{$invoice->amount_base}}</span><br><br>
                @stack('paid_total_tr_end')
            @endif
            
            @stack('grand_total_tr_start')
                <strong class="float-left">Total:</strong>
                <span>{{$invoice->subtotal}}</span> <br><br>
            @stack('grand_total_tr_end')

        </div>
    </div>
</div>
<div class="row mt-7">
    <div class="col-100 py-2" style="background-color:#55588b !important; -webkit-print-color-adjust: exact;">
        <div class="text pl-2">
            @if ($invoice->electronic_bill)
                <small class="text-white">
                {!! nl2br("ESTA FACTURA CONTRIBUYE AL DESARROLLO DE NUESTRO PAIS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY") !!}
                </small>
                <br>
                <small class="text-white">
                    {!! nl2br("Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.") !!}
                </small>
            @else
                <small class="text-white">
                {!! nl2br("ESTA FACTURA NO TIENE VALOR PARA IMPUESTOS NACIONALES") !!}
                </small>
            @endif
        </div>
    </div>
</div>