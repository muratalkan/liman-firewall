<div class="table-responsive">
  <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
    <thead>
      <tr role="row">
        <th rowspan="1" colspan="1" style="width:1px;" >ID</th>
        <th rowspan="1" colspan="1" >{{ __('TO') }}</th>
        <th rowspan="1" colspan="1" >{{ __('ACTION') }}</th>
        <th rowspan="1" colspan="1" >{{ __('FROM') }}</th>
        <th rowspan="1" colspan="1" class="text-center" style="width:1px;"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($firewallRules as $rule)
        <tr>
            <th scope="row" id="id">{{$rule['id']}}</th>
            <td id="toAddr">{{$rule['toAddress']}}</td>
            <td id="status" class="font-weight-bold" style="color: @if(strpos($rule['status'], 'ALLOW') !== false) green @else red @endif;">
              {{$rule['status']}}
            </td>
            <td id="fromAddr">{{$rule['fromAddress']}}</td>
            <td class="text-center">
              <button class="btn bg-danger btn-sm" onclick="deleteRule(this)"><i class="fa fa-trash" ></i></button>
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>