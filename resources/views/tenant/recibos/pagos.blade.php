@extends('layouts.invoices.pagos')

@section('title','RECIBO : ' . $cuenta->id)
@section('content')
    <x-sales.recibos.pagos
        :cuenta="$cuenta"
        :montoliteral="$monto_literal"
    />
@endsection