<style>
    /* Prevent selection and context callouts on mobile and desktop without breaking form inputs */
    body, html {
        -webkit-touch-callout: none !important;
    }
    input, textarea, [contenteditable="true"] {
        -webkit-touch-callout: default !important;
        user-select: text !important;
        -webkit-user-select: text !important;
    }
</style>
<script>
    (function () {
        'use strict';

        // 1. Disable Right-Click (Context Menu) completely
        function blockEvent(e) {
            if (e.button === 2 || e.which === 3 || e.type === 'contextmenu') {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return false;
            }
        }

        ['contextmenu', 'mousedown', 'mouseup', 'pointerdown'].forEach(function (eventType) {
            document.addEventListener(eventType, blockEvent, true);
            window.addEventListener(eventType, blockEvent, true);
        });

        document.oncontextmenu = function () { return false; };
        window.oncontextmenu = function () { return false; };

        // 2. Disable DevTools and Inspect shortcuts
        function disableInspectKeys(e) {
            var key = e.key ? e.key.toUpperCase() : '';
            var keyCode = e.keyCode || e.which;
            var isCtrl = Boolean(e.ctrlKey);
            var isMeta = Boolean(e.metaKey); // Cmd on macOS
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

            // Ctrl/Cmd + Shift + (I, J, C, K, E, S)
            if ((isCtrl || isMeta) && isShift && ['I', 'J', 'C', 'K', 'E', 'S'].indexOf(key) !== -1) {
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
