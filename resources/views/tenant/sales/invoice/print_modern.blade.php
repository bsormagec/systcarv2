@extends('layouts.invoices.print')

@section('title','FACTURA : ' . $invoice->id)
@section('content')
    <x-sales.template.modern
        :invoice="$invoice"
        :montoliteral="$monto_literal"
    />
@endsection
