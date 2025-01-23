document.addEventListener('livewire:init', () => {
    Livewire.on('notify-feature', (event) => {
        notifyFeature(event.message);
    });
 });



 const notifyFeature = (message = "Feature not available.") => {
    alert(message);
 };
