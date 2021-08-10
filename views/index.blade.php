<?php
$checkPackage = \App\Controllers\PackageController::verify();
if (!$checkPackage) {
	echo "<script>window.location.href = '".navigate('install')."';</script>";
}
?>

<div class="row">
  <div class="col-md-3">
    <div class="card border card-primary text-center">
      <div class="card-header">
        <h3 class="card-title">{{ __('Uncomplicated Firewall') }} (UFW)</h3>
      </div>
      <div class="card-body">
        <div class="btn-group">
          <button type="button" id="enableBtn" class="btn btn-success" onclick="enableUFW_Confirm()" disabled>{{ __('Enable') }}</button>
          <button type="button" id="disableBtn" class="btn btn-danger" onclick="disableUFW()" disabled>{{ __('Disable') }}</button>
        </div>
      </div>
    </div>
    <div class="card border card-primary">
      <div class="card-header">
        <h3 class="card-title">{{ __('Information') }}</h3>
      </div>
      <div class="card-body p-0 pt-3">
        <div class="info-box">
          <span class="info-box-icon bg-info"><i class="fas fa-ethernet"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">{{ __('Listening Ports') }}</span>
              <span class="info-box-number" id="totalLP">
                <div class="spinner-grow spinner-grow-sm text-dark" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </span>
            </div>
        </div>
        <div class="info-box">
          <span class="info-box-icon bg-info"><i class="fas fa-shield-alt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">{{ __('Firewall Rules') }}</span>
            <div class="spinner-grow spinner-grow-sm text-dark" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <div id="totalUfwRules"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="card card-primary card-tabs main-card">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
          <li class="pt-2 px-3">
            <h3 class="card-title">
              <i class="fas fa-angle-double-left"></i>
                {{ __('FIREWALL MANAGEMENT') }}
              <i class="fas fa-angle-double-right"></i>
            </h3>
          </li>
          <li class="nav-item">
            <a class="nav-link active mb-1" style="color:white;" onclick="getListeningPorts()" id="listeningPorts" data-toggle="pill" href="#tab_listeningPorts" role="tab" aria-controls="tab_listeningPorts" aria-selected="true">{{ __('Listening Ports') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-1"  style="color:black;" onclick="getFirewallRules()" id="firewallRules" data-toggle="pill" href="#tab_firewallRules" role="tab" aria-controls="tab_firewallRules" aria-selected="false" >{{ __('Firewall Rules') }}</a>
          </li>
        </ul>
      </div>
      <div class="card-body main-body pb-0 pl-2 pr-2 pt-3">
        <div class="tab-content" id="custom-tabs-two-tabContent">
            <!-------------------------------------------- Tab 1 ---------------------------------------------->
            @include('pages.listening_ports')
            <!-------------------------------------------- Tab 2 ---------------------------------------------->
            @include('pages.firewall_rules')
        </div>
      </div>
    </div>
  </div>
</div>


<script>
    
    $(function() {
      fetchUFWStatus();
      getContent();
    });

    function getContent(){
      getTotalListeningPorts();
      getTotalUfwRules();
    }
    
    function fetchUFWStatus(){
      request("{{API('fetch_ufw_status')}}", new FormData(), function(response){
          const output = JSON.parse(response).message;
          showSwal(output, 'info', 2000);
          $("#enableBtn").attr('disabled', true);   $("#disableBtn").attr('disabled', false);
          getContent();
      }, function(response){
          const error = JSON.parse(response).message;
          $("#enableBtn").attr('disabled', false);  $("#disableBtn").attr('disabled', true);
          showSwal(error, 'error', 2000);
      });
    }
    
    function getTotalListeningPorts(){
      request("{{API('get_lps_count')}}", new FormData(), function(response){
          const output = JSON.parse(response).message;
          $('#totalLP').html(`
            <h5><span class="badge badge-light"><b>${output}</b></span></h5>
          `);
          $('#totalLP').find('overlay').hide();
      }, function(response){
          const error = JSON.parse(response).message;
          showSwal(error, 'error', 2000);
      });
    }

    function getTotalUfwRules(){
      $('.info-box').find('.spinner-grow').show();
      $('#totalUfwRules').html('');
      request("{{API('get_ufw_rules_count')}}", new FormData(), function(response){
          const output = JSON.parse(response).message;
          $('.info-box').find('.spinner-grow').hide();
          $('#totalUfwRules').html(`
              <h6>
                <span class="info-box-number">
                  <span class="badge badge-success">{{ __('Allowed:') }}</span>
                  <span class="badge badge-success"><b>${output.allowCount}</b></span>
                </span>
                <span class="info-box-number">
                  <span class="badge badge-danger">{{ __('Denied:') }}</span>
                  <span class="badge badge-danger"><b>${output.denyCount}</b></span>
                </span>
              </h6>
          `);
      }, function(response){
          const error = JSON.parse(response).message;
          $('.info-box').find('.spinner-grow').hide();
          $('#totalUfwRules').html(`
                <span class="badge badge-danger">{{__('Firewall is not active!')}}</span>
          `);
      });
    }

    function enableUFW_Confirm(){
      Swal.fire({
          html: `
              <div class='row'>
                  <div class='col-md-12 center-block text-center'>
                      <p>{{__("Enabling the firewall may affect your existing SSH connection. Therefore, you may need to type the following command via your client's terminal after enabling.")}}</p>
                      <blockquote class='quote-secondary'>sudo ufw allow ssh</blockquote>
                  </div>
              </div>
          `,
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: "{{__('OK')}}", cancelButtonText: "{{__('Cancel')}}",
          showLoaderOnConfirm: true,
          preConfirm: () => {
              return new Promise((resolve) => {            
                  Swal.close();
                  enableUFW();
              })
            }
      });
    }

    function enableUFW(){
      showSwal('{{__("Enabling")}}...', 'info');
      request("{{API('enable_ufw')}}", new FormData(), function(response){
        switchButton();
      },function(response){
        const error = JSON.parse(response).message;
        showSwal(error, 'error', 2000);
      });
    }

    function disableUFW(){
      showSwal('{{__("Disabling")}}...', 'info');
      request("{{API('disable_ufw')}}", new FormData(), function(response){
        switchButton();
      },function(response){
        const error = JSON.parse(response).message;
        showSwal(error, 'error', 2000);
      });
    }

    function switchButton(){
      getTotalUfwRules();
      refreshListeningPorts();
      refreshFirewallRules();
      $("#enableBtn").attr('disabled', !$("#enableBtn").is(':disabled'));
      $("#disableBtn").attr('disabled', !$("#disableBtn").is(':disabled'));
    }

    $(".main-card").find('.nav-tabs').find('a').bind("click", function() {
      $(".main-card").find('.nav-tabs').find('a').css('color', 'black');
      $(this).css('color', 'white');
    });

</script>

<style>
    .swal2-container {
        z-index: 1000001;
    }

    .modal{
        z-index: 1000000;
    }
</style>
