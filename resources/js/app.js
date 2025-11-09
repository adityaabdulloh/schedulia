import './bootstrap';
import 'bootstrap/scss/bootstrap.scss';
import 'sweetalert2/dist/sweetalert2.min.css';
import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';
import Chart from 'chart.js/auto';

window.Swal = Swal;
window.Chart = Chart;

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const body = document.body;
    const SIDEBAR_STATE_KEY = 'sidebar_collapsed';

    // Function to apply the saved state
    const applySidebarState = () => {
        if (localStorage.getItem(SIDEBAR_STATE_KEY) === 'true') {
            body.classList.add('sidebar-collapsed');
        } else {
            body.classList.remove('sidebar-collapsed');
        }
    };

    // Apply state on page load
    applySidebarState();

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            body.classList.toggle('sidebar-collapsed');
            // Save the state to localStorage
            const isCollapsed = body.classList.contains('sidebar-collapsed');
            localStorage.setItem(SIDEBAR_STATE_KEY, isCollapsed);
        });
    }

    // Initialize Bootstrap tooltips for sidebar
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('.sidebar [data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'right',
            trigger: 'hover',
            boundary: 'window' // Prevent tooltip from being cut off
        });
    });

    // Logout Modal Logic
    const logoutButton = document.getElementById('logout-button');
    if(logoutButton) {
        logoutButton.addEventListener('click', function(event) {
            event.preventDefault();
            var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        });
    }
});