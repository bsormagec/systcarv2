<?php

namespace Augusto\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Luecano\NumeroALetras\NumeroALetras;
use DB;
use App\Models\tenant\AccountDetail;

class ReportesController extends Controller {
  
    public function printboletapago($id)
    {
        
        $cuenta = AccountDetail::with(['account:id,user_id,contact_id,deadline','account.customer:id,fullName,ci','account.user:id,name,email'])->findOrFail($id);
        $monto_literal = (new NumeroALetras())->toInvoice($cuenta->amount, 2, 'BOLIVIANOS', 'CENTAVOS');
        $view = view('tenant.recibos.pagos',compact('cuenta','monto_literal'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }
}