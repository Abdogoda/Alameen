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
        }
    });

    // Sync body.modal-open scroll lock whenever modal visibility changes
    function checkModalState() {
        const modals = document.querySelectorAll('[id^="modal-"].fixed, .modal-backdrop');
        let isOpen = false;
        modals.forEach((m) => {
            if (
                m.id !== 'alameen-loader' &&
                !m.classList.contains('student-sidebar-drawer') &&
                !m.classList.contains('admin-sidebar-drawer') &&
                !m.classList.contains('hidden') &&
                !m.classList.contains('modal-hidden') &&
                m.style.display !== 'none'
            ) {
                isOpen = true;
            }
        });

        if (isOpen) {
            document.documentElement.classList.add('modal-open');
            document.body.classList.add('modal-open');
        } else {
            document.documentElement.classList.remove('modal-open');
            document.body.classList.remove('modal-open');
        }
    }

    const observer = new MutationObserver(checkModalState);
    observer.observe(document.body, {
        attributes: true,
        subtree: true,
        attributeFilter: ['class', 'style'],
    });

    // Also run initial check
    checkModalState();
}

// Auto-run if imported
initModals();

