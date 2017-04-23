{{ $currentController->reinitGlobalMessages()  }}
@if ($currentController->globalSuccessMessage != '' || $currentController->globalErrorMessage != "")
    <div class="global-message">
        @if($currentController->globalSuccessMessage != "") 
        <div class="alert alert-success">
            <button class="close" data-dismiss="alert">×</button>
            <strong>Success!</strong> {{$currentController->globalSuccessMessage}}
        </div>                
        @endif
        
        @if($currentController->globalErrorMessage != "") 
        <div class="alert alert-error"> 
            <button class="close" data-dismiss="alert">×</button>
            <strong>Error!</strong> {{$currentController->globalErrorMessage}}
        </div>
        @endif
    </div>
@endif