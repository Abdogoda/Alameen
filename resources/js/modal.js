/**
 * Global Modal Manager
 * - Ensures all modal backdrops are mounted to document.body (never trapped in nested scrolling or transformed containers)
 * - Centers modals strictly on the visible viewport (screen height)
 * - Locks page background scroll while a modal is open
 * - Supports closing via ESC key
 */

export function initModals() {
    function teleportModals() {
        document.querySelectorAll('[id^="modal-"].fixed, .modal-backdrop').forEach((modal) => {
            // Never touch loader, sidebars, or elements marked no-teleport
            if (
                modal.id === 'alameen-loader' ||
                modal.classList.contains('student-sidebar-drawer') ||
                modal.classList.contains('admin-sidebar-drawer') ||
                modal.dataset.noTeleport === 'true'
            ) {
                return;
            }

            if (modal.parentElement && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        });
    }

    // Run on initial load and on Livewire page transitions
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', teleportModals);
    } else {
        teleportModals();
    }
    document.addEventListener('livewire:navigated', teleportModals);

    // Escape key closes open modals
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' || e.key === 'Esc') {
            document.querySelectorAll('[id^="modal-"].fixed, .modal-backdrop').forEach((modal) => {
                if (!modal.classList.contains('hidden') && modal.style.display !== 'none' && !modal.classList.contains('modal-hidden')) {
                    modal.classList.add('hidden');
                    modal.classList.add('modal-hidden');
                }
            });
            document.documentElement.classList.remove('modal-open');
            document.body.classList.remove('modal-open');
        }
    });

    // Provide universal safe open/close helpers
    window.openModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden', 'modal-hidden');
            modal.style.display = 'flex';
            document.documentElement.classList.add('modal-open');
            document.body.classList.add('modal-open');
            const content = modal.querySelector('.modal-content') || modal;
            if (content && typeof content.scrollTop !== 'undefined') {
                content.scrollTop = 0;
            }
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden', 'modal-hidden');
            modal.style.display = 'none';
        }
        const openRemaining = document.querySelectorAll('[id^="modal-"].fixed:not(.hidden):not(.modal-hidden), .modal-backdrop:not(.hidden):not(.modal-hidden)');
        if (openRemaining.length === 0) {
            document.documentElement.classList.remove('modal-open');
            document.body.classList.remove('modal-open');
        }
    };
}

// Auto-run if imported
initModals();

