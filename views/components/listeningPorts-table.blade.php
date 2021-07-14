<table class="table table-bordered table-hover dataTable dtr-inline" role="grid" >
  <thead>
    <tr role="row">
      <th rowspan="1" colspan="1" style="width:1px;">#</th>
      <th rowspan="1" colspan="1" >{{ __('PID/Process') }}</th>
      <th rowspan="1" colspan="1" >{{ __('User') }}</th>
      <th rowspan="1" colspan="1" hidden>{{ __('Protocol') }}</th>
      <th rowspan="1" colspan="1" hidden>{{ __('IP') }}</th>
      <th rowspan="1" colspan="1" >{{ __('Protocol/IP') }}</th>
      <th rowspan="1" colspan="1" >{{ __('Adress:Port') }}</th>
      <th rowspan="1" colspan="1" class="text-center" >{{ __('Status') }}</th>
      <th rowspan="1" colspan="1" class="text-center" style="width:1px;"></th>
    </tr>
  </thead>
  <tbody>
    @php($counter = 0)
    @foreach($listeningPorts as $port)
      @php(++$counter)
      <tr>
        <th>{{$counter}}</th>
        <td id="name">{{$port['processID']}}/{{$port['processName']}}</td>
        <td id="addressPort">{{$port['userName']}}</td>
        <td id="ip-version" hidden>{{$port['ipVersion']}}</td>
        <td id="protocol" hidden>{{$port['protocol']}}</td>
        <td id="protocol-ip">{{$port['protocol']}}/{{$port['ipVersion']}}</td>
        <td id="port">{{$port['port']}}</td>
        <td class="text-center">
            @php($status = __('Not Configured'))
            @php($badge = 'secondary')
            @if($port['status'] == 1)
                @php($status = __('Allowed'))
                @php($badge = 'success')
            @elseif($port['status'] == 0)
                @php($status = __('Denied'))
                @php($badge = 'danger')
            @endif
            <small class="badge badge-pill badge-{{$badge}}">
                <a>{{$status}}</a>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" style="display: none;"></span>
            </small>
        </td>
        <td class="text-center">
          <div class="btn-group">
            <div class="btn-group dropleft" role="group">
                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <div class="dropdown-menu text-center" role="menu" >
                    <div class="btn-group-vertical">
                      <button id="btn1" type="button" class="btn btn-success" style="width:150px;" onclick="allowPort(this)" @if($port['status'] == 1) disabled @endif>
                        <i class="fas fa-check-circle mr-1"></i>{{ __('Allow') }}
                      </button>
                      <button id="btn2" type="button" class="btn btn-danger" style="width:150px;" onclick="denyPort(this)" @if($port['status'] == 0) disabled @endif>
                        <i class="fa fa-ban mr-1"></i>{{ __('Deny') }}
                      </button>
                    </div>
                </div>
              </div>
            </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>