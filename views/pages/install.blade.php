<div class="alert alert-info" role="alert">
  <i class="fas fa-info-circle mr-2"></i>{{__("In order to use this extension, you must install the 'ufw' and 'lsof' packages on the server. You can install them by clicking on 'Install Packages' button below.")}}
</div>

<button id="installPackageButton" class="btn btn-secondary" onclick="installPackages()">{{__("Install Packages")}}</button>

@component('modal-component',[
    "id" => "packageInstallerModal",
    "title" => "Package Installer"
])@endcomponent

<script>

    function installPackages(){
      showSwal('{{__("Yükleniyor...")}}','info',2000);
      request(API('install_package'), new FormData(), function (response) {
        let output = JSON.parse(response);
        $("#installPackageButton").attr("disabled","true");
        $('#packageInstallerModal').modal({backdrop: 'static', keyboard: false})
        $('#packageInstallerModal').find('.modal-body').html(output.message);
        $('#packageInstallerModal').modal("show"); 
      }, function(response){
          let error = JSON.parse(response);
          showSwal(error.message,'error',2000);
      })
    }

    function onTaskSuccess(){
        showSwal('{{__("Your request has been completed successfully")}}', 'success', 2000);
        setTimeout(function(){
          $('#packageInstallerModal').modal("hide"); 
        }, 2000);
        window.location.href = 'index';
    }

    function onTaskFail(){
        showSwal('{{__("An error occurred while processing your request")}}!', 'error', 2000);
    }
</script>