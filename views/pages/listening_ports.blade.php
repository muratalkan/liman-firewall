<div class="tab-pane fade show active" id="custom-tabs-two-page1" role="tabpanel" aria-labelledby="custom-tabs-two-page1-tab">
  <div class="card card-warning card-tabs listeningPortsCard">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">{{ __('Listening Ports') }}</h3>
      <div class="card-tools">
        <button type="button" onclick="refreshListeningPorts()" class="btn btn-tool refresh-button">
          <i class="fas fa-sync-alt"></i>
        </button>
      </div>
    </div>
    <div class="overlay table-overlay">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body">
      <div id="listeningPorts-table"></div>
    </div>
  </div>
</div>


<script>

    $(document).ready(function() {
        $('.listeningPortsCard').find('.table-overlay').show();
    });

    function getListeningPorts(){
        showSwal('{{__("Loading")}}...','info');
        request("{{API('get_listening_ports')}}", new FormData(), function(response) {
            Swal.close();
            $('#listeningPorts-table').html(response).find('table').DataTable(dataTablePresets('normal'));
            $('.listeningPortsCard').find('.table-overlay').hide();
            getTotalListeningPorts();
        }, function(response) {
            const error = JSON.parse(response).message;
            showSwal(error, 'error', 2000);
        });
    }

    function allowPort(row){
        showSwal('{{__("Applying")}}...','info');
        let data = new FormData();
            data.append("port", row.closest('tr').querySelector('#port').innerHTML);
            data.append("ipVersion", row.closest('tr').querySelector('#ip-version').innerHTML);
            data.append("protocol",  row.closest('tr').querySelector('#protocol').innerHTML);
        request("{{API('allow_listening_port')}}", data, function(response) {
            const message = JSON.parse(response).message;
            showSwal(message, 'success', 2000);
            getListeningPorts();
            getTotalUfwRules();
        }, function(response) {
            const error = JSON.parse(response).message;
            showSwal(error, 'error', 2000);
        });
    }

    function denyPort(row){
        showSwal('{{__("Applying")}}...','info');
        let data = new FormData();
            data.append("port", row.closest('tr').querySelector('#port').innerHTML);
            data.append("ipVersion", row.closest('tr').querySelector('#ip-version').innerHTML);
            data.append("protocol", row.closest('tr').querySelector('#protocol').innerHTML);
        request("{{API('deny_listening_port')}}", data, function(response) {
            const message = JSON.parse(response).message;
            showSwal(message, 'success', 2000);
            getListeningPorts();
            getTotalUfwRules();
        }, function(response) {
            const error = JSON.parse(response).message;
            showSwal(error, 'error', 2000);
        });
    }

    function refreshListeningPorts(){
        $('.listeningPortsCard').find('.table-overlay').show();
        getListeningPorts();
    }

</script>
