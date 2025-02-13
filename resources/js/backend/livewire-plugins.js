const Swal = require('sweetalert2');

document.addEventListener('livewire:init', () => {
    Livewire.on('notify-feature', (event) => {
        notifyFeature(event.message);
    });


    Livewire.on('alert', ({message, useSwal = false, type = 'warning'}) => {

        if(useSwal){
            alertSwal(message, type);
        } else {
            alert(message);
        }
    });

    Livewire.on('client-loading', () => {

        console.log('client-loading');
        document.body.style.pointerEvents = 'none';

        Swal.fire({
            title: 'Please wait...',
            html: 'Processing your request',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });
    });

    Livewire.on('destroy-alert', () => {

        document.body.style.pointerEvents = 'auto';

        // Destroy all alerts
        // find all Swal instances and destroy them
        Swal.close();

    });
 });



 const notifyFeature = (message = "Feature not available.") => {
    alert(message);
 };


 const alertSwal = (message = "Feature not available.", type = 'warning') => {
    Swal.fire({
        text: message,
        icon: type,
        confirmButtonText: 'Ok'
    });
 }

