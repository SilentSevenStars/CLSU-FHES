import "./bootstrap";
import "flowbite";
import "@fortawesome/fontawesome-free/css/all.min.css";

window.addEventListener("alert", (event) => {
    let data = event.detail;

    Swal.fire({
        position: data.position,
        icon: data.type,
        title: data.title,
        text: data.text,
        timer: 1500,
        showConfirmButton: false,
    });
});

window.addEventListener("confirmation", (event) => {
    let data = event.detail;

    Swal.fire({
        title: data.title,
        text: data.text,
        icon: data.icon,
        showCancelButton: data.showCancelButton,
        confirmButtonColor: data.confirmButtonColor,
        cancelButtonColor: data.cancelButtonColor,
        confirmButtonText: data.confirmButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            let componentId = document
                .querySelector("[wire\\:id]")
                .getAttribute("wire:id");
            if (componentId && typeof Livewire !== "undefined" && Livewire) {
                Livewire.find(componentId).dispatch("destroy", { id: data.id });
            }
        }
    });
});
