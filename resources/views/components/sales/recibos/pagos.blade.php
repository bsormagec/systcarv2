<div class="receipt-main">
  
    <p class="receipt-title">Recibo</p>
    
    <div class="receipt-section pull-left">
      <span class="receipt-label text-large">Número:</span>
      <span class="text-large">{{$cuenta->id}}</span>
    </div>
    
    <div class="pull-right receipt-section">
      <span class="text-large receipt-label">BOB</span>
      <span class="text-large">{{$cuenta->amount}}</span>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="receipt-section">
      <span class="receipt-label">Beneficiário:</span>
      <span>{{$cuenta->account->customer->fullName}}(CI:{{$cuenta->account->customer->ci}})</span>
    </div>
    
    <div class="receipt-section">
      <span class="receipt-label">Responsable:</span>
      <span>{{$cuenta->account->user->name}} (EMAIL:{{$cuenta->account->user->email}})</span>
    </div>
    
    <div class="receipt-section">
      <p>{{$cuenta->detail}}</p>
    </div>
    
    <div class="receipt-section">
      <p class="pull-right text-large">{{ Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="receipt-signature col-xs-6">
      <p class="receipt-line"></p>
      <p>{{ setting('company.name') }}</p>
      <p>{{ setting('company.nit') }}</p>
      <p>{{ setting('company.address') }}</p>
    </div>
  
    <div class="receipt-signature col-xs-6">
      <p class="receipt-line"></p>
      <p>{{$cuenta->account->customer->fullName}}</p>
      <p>{{$cuenta->account->customer->ci}}</p>
    </div>
</div>
  