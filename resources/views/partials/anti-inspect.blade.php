<style>
    /* Prevent mobile callout menus on long press without breaking text input fields */
    body, html {
        -webkit-touch-callout: none;
    }
    input, textarea, [contenteditable="true"] {
        -webkit-touch-callout: default !important;
    }
</style>
<script>
    (function () {
        'use strict';

        // 1. Disable Right Click (Context Menu) across the entire website
        function disableContextMenu(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        document.addEventListener('contextmenu', disableContextMenu, true);
        window.addEventListener('contextmenu', disableContextMenu, true);

        // 2. Disable Developer Inspection & Source Keyboard Shortcuts
        function disableInspectKeys(e) {
            var key = e.key ? e.key.toUpperCase() : '';
            var keyCode = e.keyCode || e.which;
            var isCtrl = Boolean(e.ctrlKey);
            var isMeta = Boolean(e.metaKey); // Cmd key on macOS
            var isShift = Boolean(e.shiftKey);
            var isAlt = Boolean(e.altKey);

            // F12 or Shift+F12
            if (key === 'F12' || keyCode === 123) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return false;
            }

            // Ctrl/Cmd + Shift + (I, J, C, K, E) -> Elements / Console / Network / Debugger
            if ((isCtrl || isMeta) && isShift && ['I', 'J', 'C', 'K', 'E'].indexOf(key) !== -1) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return false;
            }

            // macOS Safari / Chrome: Cmd + Option + (I, J, C, U)
            if (isMeta && isAlt && ['I', 'J', 'C', 'U'].indexOf(key) !== -1) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return false;
            }

            // Ctrl/Cmd + U (View Page Source)
            if ((isCtrl || isMeta) && key === 'U') {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return false;
            }

            // Ctrl/Cmd + S (Save Page)
            if ((isCtrl || isMeta) && key === 'S') {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return false;
            }
        }

        window.addEventListener('keydown', disableInspectKeys, true);
        document.addEventListener('keydown', disableInspectKeys, true);
    })();
</script>
