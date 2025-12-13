import "./bootstrap";
import "flowbite";
import "@fortawesome/fontawesome-free/css/all.min.css";
import Swal from 'sweetalert2';

window.swal = Swal;

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

// Navigation dropdown menu toggles
document.addEventListener("DOMContentLoaded", function () {
    // Applicant sidebar user menu
    const sidebarUserButton = document.getElementById("sidebarUserButton");
    const sidebarUserMenu = document.getElementById("sidebarUserMenu");
    
    if (sidebarUserButton && sidebarUserMenu) {
        sidebarUserButton.addEventListener("click", function (e) {
            e.stopPropagation();
            sidebarUserMenu.classList.toggle("hidden");
        });
    }

    // Admin sidebar user menu
    const adminSidebarUserButton = document.getElementById("adminSidebarUserButton");
    const adminSidebarUserMenu = document.getElementById("adminSidebarUserMenu");
    
    if (adminSidebarUserButton && adminSidebarUserMenu) {
        adminSidebarUserButton.addEventListener("click", function (e) {
            e.stopPropagation();
            adminSidebarUserMenu.classList.toggle("hidden");
        });
    }

    // Panel sidebar user menu
    const panelSidebarUserButton = document.getElementById("panelSidebarUserButton");
    const panelSidebarUserMenu = document.getElementById("panelSidebarUserMenu");
    
    if (panelSidebarUserButton && panelSidebarUserMenu) {
        panelSidebarUserButton.addEventListener("click", function (e) {
            e.stopPropagation();
            panelSidebarUserMenu.classList.toggle("hidden");
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener("click", function (e) {
        if (sidebarUserMenu && !sidebarUserButton?.contains(e.target) && !sidebarUserMenu.contains(e.target)) {
            sidebarUserMenu.classList.add("hidden");
        }
        if (adminSidebarUserMenu && !adminSidebarUserButton?.contains(e.target) && !adminSidebarUserMenu.contains(e.target)) {
            adminSidebarUserMenu.classList.add("hidden");
        }
        if (panelSidebarUserMenu && !panelSidebarUserButton?.contains(e.target) && !panelSidebarUserMenu.contains(e.target)) {
            panelSidebarUserMenu.classList.add("hidden");
        }
    });
});

// Sidebar toggle functions for mobile
window.toggleApplicantSidebar = function() {
    const sidebar = document.getElementById("applicant-sidebar");
    if (sidebar) {
        sidebar.classList.toggle("-translate-x-full");
    }
};

window.toggleAdminSidebar = function() {
    const sidebar = document.getElementById("admin-sidebar");
    if (sidebar) {
        sidebar.classList.toggle("-translate-x-full");
    }
};

window.togglePanelSidebar = function() {
    const sidebar = document.getElementById("panel-sidebar");
    if (sidebar) {
        sidebar.classList.toggle("-translate-x-full");
    }
};
