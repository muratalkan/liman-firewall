<div class="tab-pane fade show active" id="tab_listeningPorts" role="tabpanel" aria-labelledby="listeningPorts">
  <div class="card card-warning card-tabs listeningPortsCard overflow-auto">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">{{ __('Listening Ports') }}</h3>
      <div class="card-tools">
        <button type="button" onclick="refreshListeningPorts()" class="btn btn-tool refresh-button">
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
      <div id="listeningPorts-table"></div>
    </div>
  </div>
</div>


<script>

    $(document).ready(function() {
        $('.listeningPortsCard').find('.table-overlay').show();
        getListeningPorts();
    });

    function getListeningPorts(){
        showSwal('{{__("Loading")}}...','info');
        request("{{API('get_listening_ports')}}", new FormData(), function(response) {
            $('#listeningPorts-table').html(response).find('table').DataTable(dataTablePresets('normal'));
            $('.listeningPortsCard').find('.table-overlay').hide();
            Swal.close();
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
