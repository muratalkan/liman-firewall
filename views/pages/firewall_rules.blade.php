<div class="tab-pane fade" id="tab_firewallRules" role="tabpanel" aria-labelledby="firewallRules">
  <div class="card card-warning card-tabs firewallCard verflow-auto">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">{{ __('Firewall Rules') }}</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" onclick="addCustomUFWRule()">
          <i class="fas fa-plus"></i>
        </button>
        <button type="button" class="btn btn-tool" onclick="getFirewallLogs()">
          <i class="fas fa-file"></i>
        </button>
        <button type="button" onclick="refreshFirewallRules()" class="btn btn-tool refresh-button">
          <i class="fas fa-sync-alt"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize" id="maximizeButton">
            <i class="fas fa-expand"></i>
        </button>
      </div>
    </div>
    <div class="overlay table-overlay">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body">
      <div id="firewallRules-table"></div>
    </div>
  </div>
</div>

@component('modal-component',[
        "id"=>"firewallLogsModal",
        "title" => "Firewall Logs"
    ])

    <div style="background:black; height:500px; overflow-y: scroll;">
        <pre id="firewallLogs" style="color:white; white-space:pre-wrap; word-wrap:break-word;"></pre>
    </div>

@endcomponent


<script>

    $(document).ready(function() {
      $('.firewallCard').find('.table-overlay').show();
    });

    function getFirewallRules(){
      showSwal('{{__("Loading")}}...', 'info', 2500);
      request("{{API('get_firewall_rules')}}", new FormData(), function(response) {
          $('#firewallRules-table').html(response).find('table').DataTable(dataTablePresets('normal'));
          $('.firewallCard').find('.table-overlay').hide();
          getTotalUfwRules();
      }, function(response) {
          const error = JSON.parse(response).message;
          showSwal(error, 'error', 2000);
      });
    }

    function addCustomUFWRule(){
      let prefixes=['sudo', 'sudo ufw', 'ufw',];  
      Swal.fire({
          title: "{{__('Add Custom UFW Rule')}}",
          input: 'text',
          inputPlaceholder: "allow ssh",
          showCancelButton: true,
          confirmButtonColor: "#28a745",
          confirmButtonText: "{{__('Add')}}", cancelButtonText: "{{__('Cancel')}}",
          inputValidator: (value) => {
              if (!value) {
                  return `{{__('Please, enter a valid UFW rule!')}}`;
              }else if(prefixes.indexOf(value) !== -1){
                  return `{{__("Please, do not write these prefixes; ")}}`+ prefixes.join(', ');
              }
            },
          showLoaderOnConfirm: true,
            preConfirm: (value) => {
              return new Promise((resolve) => {
                  let data = new FormData();
                      data.append('ufwCmd', value);
                  request("{{API('add_custom_ufwRule')}}", data, function(response) {
                      const output = JSON.parse(response).message;
                      Swal.fire({title:"{{ __('Added') }}", text: output, type: "success", showConfirmButton: false});
                      getFirewallRules();
                  },function(response) {
                      const error = JSON.parse(response).message;
                      Swal.fire("{{ __('Error!') }}", error, "error");
                  });
              })
            },
            allowOutsideClick: false
      });
    }

    function deleteRule(row){
      var id = row.closest('tr').querySelector('#id').innerHTML;
      const toAddr = row.closest('tr').querySelector('#toAddr').innerHTML;
        Swal.fire({
            title: `<h5>ID: ${id} | ${toAddr}</h5>`,
            text: "{{ __('Are you sure you want to delete this firewall rule?') }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: "{{ __('Delete') }}", cancelButtonText: "{{ __('Cancel') }}", 
            showLoaderOnConfirm: true,
              preConfirm: () => {
                return new Promise((resolve) => {
                    let data = new FormData();
                      data.append("ruleID", id);
                    request("{{API('delete_ufw_rule')}}", data, function(response) {
                        const message = JSON.parse(response).message;
                        Swal.fire({title:"{{ __('Deleted') }}", text: message, type: "success", showConfirmButton: false});
                        getFirewallRules(); 
                    }, function(response) {
                        const error = JSON.parse(response).message;
                        Swal.fire("{{ __('Error') }}!", error, "error");
                    });
                })
              },
              allowOutsideClick: false
        });
    }

    function getFirewallLogs(){
      showSwal('{{__("Loading")}}...', 'info');
      request("{{API('get_firewall_logs')}}", new FormData, function(response) {
          const output = JSON.parse(response).message;
          $('#firewallLogsModal').find('#firewallLogs').html(output).parent().animate({scrollTop: 999999999}, 1000);
          Swal.close();
          $('#firewallLogsModal').modal('show');
      }, function(response) {
          const error = JSON.parse(response).message;
          Swal.fire("{{ __('Error') }}!", error, "error");
      });
    }

    function refreshFirewallRules(){
      $('.firewallCard').find('.table-overlay').show();
      getFirewallRules();
    }


</script>