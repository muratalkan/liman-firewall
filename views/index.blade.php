
 
  <div class="row">
    <div class="col-md-3">
      <div class="card card-primary text-center">
        <div class="card-header">
          <h3 class="card-title">{{ __('Karmaşık Olmayan Güvenlik Duvarı (UFW)') }}</h3>
        </div>
        <div class="card-body">
          <div class="btn-group">
            <button type="button" id="enableBtn" class="btn btn-success" onclick="setUFW_Enabled()">{{ __('Etkinleştir') }}</button>
            <button type="button" id="disableBtn" class="btn btn-danger" onclick="setUFW_Disabled()">{{ __('Devre Dışı Bırak') }}</button>
            </div>
          </div>
        </div>
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">{{ __('Bilgiler') }}</h3>
          </div>
        <div class="card-body">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-ethernet"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">{{ __('Dinleyen Port Sayısı') }}</span>
                <span class="info-box-number" id="totalLP">
                  <div class="spinner-border" role="status">
                    <span class="sr-only">Loading</span>
                  </div>
                </span>
              </div>
          </div>
          <hr>
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">{{ __('Güvenlik Duvarı Kural Sayısı') }}</span>
                <span class="info-box-number" id="totalAllowedTv">
                  <div class="spinner-border" role="status">
                    <span class="sr-only">Loading</span>
                  </div>
                </span>
                <span class="info-box-number" id="totalDeniedTv"></span>
              </div>
          </div>
        </div>
      </div>
 </div>

<!-------------------------------------------- Table ---------------------------------------------->
  <div class="col-md-9">
    <div class="card card-primary card-tabs">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
          <li class="pt-2 px-3">
            <h3 class="card-title">
              <i class="fas fa-angle-double-left"></i>
                {{ __('GÜVENLİK DUVARI YÖNETİMİ') }}
              <i class="fas fa-angle-double-right"></i>
            </h3>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-two-page1-tab" data-toggle="pill" href="#custom-tabs-two-page1" role="tab" aria-controls="custom-tabs-two-page1" aria-selected="true">{{ __('Dinleyen Portlar') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-two-page2-tab" data-toggle="pill" href="#custom-tabs-two-page2" role="tab" aria-controls="custom-tabs-two-page2" aria-selected="false" >{{ __('Güvenlik Duvarı Kuralları') }}</a>
          </li>
        </ul>
      </div>
            <!-------------------------------------------- Tab 1 ---------------------------------------------->
      <div class="card-body">
        <div class="tab-content" id="custom-tabs-two-tabContent">
          <div class="tab-pane fade show active" id="custom-tabs-two-page1" role="tabpanel" aria-labelledby="custom-tabs-two-page1-tab">
            <div class="card card-warning card-tabs">
              <div class="card-header">
                <h3 class="card-title">{{ __('Dinleyen Portlar') }}</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                  <thead>
                    <tr role="row">
                      <th rowspan="1" colspan="1" >{{ __('PID/Program İsmi') }}</th>
                      <th rowspan="1" colspan="1" >{{ __('Adres:Port') }}</th>
                      <th rowspan="1" colspan="1" >{{ __('Protokol') }}</th>
                      <th rowspan="1" colspan="1" >{{ __('Durum') }}</th>
                      <th rowspan="1" colspan="1" class="text-center">{{ __('İşlem') }}</th>
                    </tr>
                  </thead>
                 
                  <tbody>
                    @foreach($procDetailsArr as $proc)
                      <tr>
                        <td>{{$proc['process']}}</td>
                        <td>{{$proc['localAddr']}}</td>
                        <td>{{$proc['protocol']}}</td>
                        <td id="conf{{$proc['ID']}}" style="color:gray;"></td>
                        <td class="text-center">
                          <div class="btn-group">
                            <button type="button" id="actionBtn1" class="btn btn-info">{{ __('Ayarla') }}</button>
                            <button type="button" id="actionBtn2" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu text-center" role="menu" >
                              <div class="btn-group-vertical">
                                <button type="button" class="btn btn-success" style="width:150px;" onclick="allowProcess('{{$proc['ID']}}', '{{$proc['localAddr']}}')">{{ __('İzin ver') }}</button>
                                <button type="button" class="btn btn-danger" style="width:150px;" onclick="denyProcess('{{$proc['ID']}}','{{$proc['localAddr']}}')">{{ __('Engelle') }}</button>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                  @endforeach

                 </tbody>
                </table>
              </div>
            </div>
                  </div>
<!-------------------------------------------- Tab 2 ---------------------------------------------->
     <div class="tab-pane fade" id="custom-tabs-two-page2" role="tabpanel" aria-labelledby="custom-tabs-two-page2-tab">
      <div class="card card-warning card-tabs">
        <div class="card-header">
          <h3 class="card-title">{{ __('Güvenlik Duvarı Kuralları') }}</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="maximize">
                <i class="fas fa-expand"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
        </div>
        <div class="card-body">
          <table id="example2" class="table table-striped projects" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th rowspan="1" colspan="1" >ID</th>
                <th rowspan="1" colspan="1" >Port</th>
                <th rowspan="1" colspan="1" >{{ __('Durum') }}</th>
                <th rowspan="1" colspan="1" >{{ __('Adres') }}</th>
                <th rowspan="1" colspan="1" class="text-center">{{ __('İşlem') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rulesArr as $rule)
                <tr>
                  <th scope="row">{{$rule['id']}}</th>
                  <td>{{$rule['toAddr']}}</td>
                  @if (strpos($rule['action'], "ALLOW") !== false)
                    <td style="color:green;">{{$rule['action']}}</td>
                  @else
                    <td style="color:red;">{{$rule['action']}}</td>
                  @endif
                    <td>{{$rule['fromAddr']}}</td>
                    <td class="text-center">
                      <button class="btn bg-danger" onclick="deleteRule('{{$rule['id']}}')"><i class="fa fa-trash" ></i></button>
                    </td>
                  </tr>
                  @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!---------------------------------------------------------------------------------------------------------------------- -->

<script>

    $('#custom-tabs-two-page2-tab').on('click',function(){
      if(window.location.hash != "#custom-tabs-two-page2"){
        reload();
      }  
    });

    function getTotalListeningPorts(){
        request("{{API("getTotalListeningPorts")}}", new FormData(), function(response){
            response = JSON.parse(response);
            $('#totalLP > div').remove();
            document.getElementById ("totalLP").innerText  = response.message;
        }, function(response){
            response = JSON.parse(response);
        });
    }

    function getTotalPortAndUfwNumbers(){
        request("{{API("getTotalUfwRules")}}", new FormData(), function(response){
            response = JSON.parse(response);
            $('#totalAllowedTv > div').remove();
            document.getElementById ("totalAllowedTv").innerHTML = "<a style='color:green;'>{{ __('İzin Verilen') }}: "+response.message['allowCount']+"</a>";
            document.getElementById ("totalDeniedTv").innerHTML  = "<a style='color:red;'>{{ __('Engellenen') }}: "+response.message['denyCount']+"</a>";
        }, function(response){
            response = JSON.parse(response);
        });
    }

    function allowProcess(id, localAddr){
      let { addr, port } = splitLocalAddr(localAddr);
      var command = "sudo ufw allow from " + addr + " to any port " + port;

      showSwal('{{__("Uygulanıyor..")}}', 'info');
      let form = new FormData();
      form.append("command", command);
      request(API("runFirewallCommand"), form, function(success){
        let json = JSON.parse(success);
        getTotalPortAndUfwNumbers();
        Swal.close();
        document.getElementById ( "conf"+id).innerHTML = "<small class='badge badge-success'>{{ __('İzin verildi') }}</small>";
        showSwal(json.message, 'info', 1500);
        },function(error){
            let json = JSON.parse(error);
            showSwal(json.message,'error',1500);
        });
    }

    function denyProcess(id, localAddr){
      let { addr, port } = splitLocalAddr(localAddr);
      var command = "sudo ufw deny from " + addr + " to any port " + port;

      showSwal('{{__("Uygulanıyor..")}}', 'info');
      let form = new FormData();
      form.append("command", command);
      request(API("runFirewallCommand"), form, function(success){
        let json = JSON.parse(success);
        getTotalPortAndUfwNumbers();
        Swal.close();
        document.getElementById ( "conf"+id).innerHTML  = "<small class='badge badge-danger'>{{ __('Engellendi') }}</small>";
        showSwal(json.message, 'info', 1500);
        },function(error){
            let json = JSON.parse(error);
            showSwal(json.message,'error',1500);
        });
    }

    function deleteRule(ruleID){
      var command = "echo y | sudo ufw delete " + ruleID.slice(1,-1);

      showSwal('{{__("Uygulanıyor..")}}', 'info');
      let form = new FormData();
      form.append("command", command);
      request(API("runFirewallCommand"), form, function(success){
        let json = JSON.parse(success);
        Swal.close();
        reload();
        showSwal("Rule deleted", 'info', 1500);
        },function(error){
            let json = JSON.parse(error);
            showSwal(json.message,'error',1500);
        });
    }

    getUFWStatus();
    function getUFWStatus(){
        request("{{API("fetchUFWStatus")}}", new FormData(), function(response){
            response = JSON.parse(response);
            setUFWButtons(response.message);
            Swal.close();
            getTotalListeningPorts();
            getTotalPortAndUfwNumbers();
            msg = response.message.includes("inactive") ? "Firewall Status: inactive" : "Firewall Status: active";
            showSwal(msg, 'info', 1000);
        }, function(response){
            response = JSON.parse(response);
            showSwal(response.message, 'error', 500);
        });
    }

    checkStatus();
    function checkStatus(){
      var procArr = <?php echo json_encode($procDetailsArr); ?>;
      var ruleArr = <?php echo json_encode($rulesArr); ?>;

      procArr.forEach(function(proc) {
        let { addr, port } = splitLocalAddr(proc['localAddr']);
        var res = "{{ __('Ayarlı değil') }}";
        var icon = "secondary";

        if(addr.includes("/0")){ 
          addr = "Anywhere";
          if(proc['protocol'].includes("6") && !addr.includes(":")){
            addr = "Anywhere(v6)";
            port = port + " (v6)"
          }
        }

        ruleArr.forEach(function(rule){
            if(rule['toAddr'].includes(port)){
              if(rule['fromAddr'].includes(addr)){
                if(rule['action'].includes("ALLOW")){
                  res = "{{ __('İzin verildi') }}";
                  icon = "success";
                }
                else if(rule['action'].includes("DENY")){
                  res = "{{ __('Engellendi') }}";
                  icon = "danger";
                }
              }
            }
        });

        document.getElementById ( "conf"+proc['ID']).innerHTML  = "<small class='badge badge-"+icon+"'>"+res+"</small>";
      });
        
    }

    function splitLocalAddr(localAddr){
        var tempArr = localAddr.split(":");  
        var addr = tempArr[0];
        var port = tempArr[1];

        if(tempArr.length > 2){ //ipv6
          port = tempArr[tempArr.length-1];
          addr = localAddr.slice(0, -(port.length+1));
        }
        if(addr ==  "0.0.0.0" || addr == "::"){
            addr += "/0";
        }

        return {addr, port};
    }

    ////////////////////////////////////////// Set Buttons //////////////////////////////////////////
    function setUFW_Enabled(){
      showSwal('{{__("Uygulanıyor..")}}', 'info');
        request("{{API("enableUFWStatus")}}", new FormData(), function(response){
          getUFWStatus();
        }, function(response){
            response = JSON.parse(response);
            showSwal(response.message, 'error', 500);
        });
    }

    function setUFW_Disabled(){
      showSwal('{{__("Uygulanıyor..")}}', 'info');
        request("{{API("disableUFWStatus")}}", new FormData(), function(response){
          getUFWStatus();
        }, function(response){
            response = JSON.parse(response);
            showSwal(response.message, 'error', 5000);
        });
    }

    function setUFWButtons(val){
      var enBtn = document.getElementById("enableBtn");
      var disBtn = document.getElementById("disableBtn");
      var tabBtn_Rule = document.getElementById("custom-tabs-two-page2-tab");

      if(val.includes("inactive")){
          enBtn.disabled = false;
          disBtn.disabled = true;
          tabBtn_Rule.disabled = true;
          $('button[id^="actionBtn1"]').prop('disabled', true);
          $('button[id^="actionBtn2"]').prop('disabled', true);
      }
      else{
          enBtn.disabled = true;
          disBtn.disabled = false;
          tabBtn_Rule.disabled = false;
          $('button[id^="actionBtn1"]').prop('disabled', false);
          $('button[id^="actionBtn2"]').prop('disabled', false);
      }
      
    }
</script>