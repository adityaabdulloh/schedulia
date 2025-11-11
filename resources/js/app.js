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
    const MOBILE_BREAKPOINT = 991.98;

    // Create and append the backdrop element
    const backdrop = document.createElement('div');
    backdrop.className = 'sidebar-backdrop';
    body.appendChild(backdrop);

    // Function to handle sidebar state on desktop
    const applyDesktopSidebarState = () => {
        if (window.innerWidth > MOBILE_BREAKPOINT) {
            if (localStorage.getItem(SIDEBAR_STATE_KEY) === 'true') {
                body.classList.add('sidebar-collapsed');
            } else {
                body.classList.remove('sidebar-collapsed');
            }
        } else {
            body.classList.remove('sidebar-collapsed');
        }
    };

    // Apply state on page load
    applyDesktopSidebarState();

    // Sidebar toggle event listener
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth <= MOBILE_BREAKPOINT) {
                // Mobile behavior
                body.classList.toggle('sidebar-mobile-open');
            } else {
                // Desktop behavior
                body.classList.toggle('sidebar-collapsed');
                const isCollapsed = body.classList.contains('sidebar-collapsed');
                localStorage.setItem(SIDEBAR_STATE_KEY, isCollapsed);
            }
        });
    }

    // Backdrop click listener to close mobile sidebar
    backdrop.addEventListener('click', () => {
        if (body.classList.contains('sidebar-mobile-open')) {
            body.classList.remove('sidebar-mobile-open');
        }
    });

    // Window resize listener
    window.addEventListener('resize', () => {
        // Close mobile sidebar if window is resized to desktop
        if (window.innerWidth > MOBILE_BREAKPOINT) {
            body.classList.remove('sidebar-mobile-open');
        }
        // Re-apply desktop state on resize
        applyDesktopSidebarState();
    });


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

    // Listen for new announcements
    if (typeof KELAS_ID !== 'undefined' && KELAS_ID) {
        window.Echo.private(`kelas.${KELAS_ID}`)
            .listen('pengumuman.created', (e) => {
                console.log('Event received:', e); // For debugging

                const pengumuman = e.pengumuman;

                // Show a toast notification
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: `Pengumuman Baru: ${pengumuman.matakuliah}`,
                    html: `<strong>Tipe:</strong> ${pengumuman.tipe.charAt(0).toUpperCase() + pengumuman.tipe.slice(1)}<br>
                           <strong>Pesan:</strong> ${pengumuman.pesan}<br>
                           <strong>Oleh:</strong> ${pengumuman.dosen}<br>
                           <small>${pengumuman.created_at}</small>`,
                    showConfirmButton: false,
                    timer: 7000, // Increased timer for more info
                    timerProgressBar: true
                });

                // Create the new announcement element
                const newAnnouncement = document.createElement('div');
                let alertClass = 'alert-info';
                if (pengumuman.tipe === 'perubahan') {
                    alertClass = 'alert-warning';
                } else if (pengumuman.tipe === 'pembatalan') {
                    alertClass = 'alert-danger';
                }
                newAnnouncement.className = `alert ${alertClass}`;
                newAnnouncement.setAttribute('role', 'alert');

                newAnnouncement.innerHTML = `
                    <h5 class="alert-heading">
                        ${pengumuman.matakuliah}
                        <span class="badge badge-secondary">${pengumuman.tipe.charAt(0).toUpperCase() + pengumuman.tipe.slice(1)}</span>
                    </h5>
                    <p>${pengumuman.pesan}</p>
                    <hr>
                    <p class="mb-0 text-right">
                        <small>
                            Oleh: ${pengumuman.dosen} | Baru saja
                        </small>
                    </p>
                `;

                // Prepend to the list
                const pengumumanList = document.getElementById('announcement-list');
                if (pengumumanList) {
                    const noAnnouncement = document.getElementById('no-announcement-message');
                    if (noAnnouncement) {
                        noAnnouncement.remove();
                    }
                    pengumumanList.prepend(newAnnouncement);
                }
            });
    }
});