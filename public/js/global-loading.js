(function () {
    if (!window.Livewire || !window.Livewire.hook) return;

    var PENDING = new Map();

    function attrValue(el, prefix) {
        if (!el || !el.attributes) return null;
        for (var i = 0; i < el.attributes.length; i++) {
            var n = el.attributes[i].name;
            if (n.indexOf(prefix) === 0) return el.attributes[i].value;
        }
        return null;
    }

    function findActionEl(node, prefix) {
        var el = node;
        while (el && el.getAttribute) {
            if (attrValue(el, prefix) !== null) return el;
            el = el.parentElement;
        }
        return null;
    }

    function methodName(attrValue) {
        if (!attrValue) return null;
        return String(attrValue).trim().replace(/\s*\(.*$/, '').trim();
    }

    function loadingText(action) {
        var a = (action || '').toLowerCase();
        if (/send|mail|email|sms|whatsapp|sendmail|forgot/.test(a)) return 'Sending...';
        if (/save|store|create|add|update|edit|submit|upload|import|copy|rename|delete|remove|disable|enable/.test(a)) return 'Saving...';
        if (/login|log-in|signin|sign-in|sign in|log in/.test(a)) return 'Signing in...';
        if (/logout|signout|sign-out|exit/.test(a)) return 'Signing out...';
        if (/sync|fetch|load|search|filter|list|get|check|verify|refresh|reset|generate|download|preview|open|show|select/.test(a)) return 'Loading...';
        return 'Processing...';
    }

    function markPending(el) {
        if (PENDING.has(el)) return;

        var compEl = el.closest('[wire\\:id]');
        var compId = compEl ? compEl.getAttribute('wire:id') : null;

        var actions = [];
        var wc = attrValue(el, 'wire:click');
        if (wc) actions.push(methodName(wc));
        var ws = attrValue(el, 'wire:submit');
        if (ws) actions.push(methodName(ws));
        if (!actions.length && el.closest) {
            var form = el.closest('form');
            if (form) {
                var fws = attrValue(form, 'wire:submit');
                if (fws) actions.push(methodName(fws));
            }
        }

        el.classList.add('btn-loading');
        el.setAttribute('aria-disabled', 'true');
        if (typeof el.disabled === 'boolean') {
            el.setAttribute('data-gl-restore', el.disabled ? '1' : '0');
            el.disabled = true;
        }

        var overlay = document.createElement('span');
        overlay.className = 'btn-loading-overlay';
        overlay.setAttribute('role', 'status');
        var spinner = document.createElement('span');
        spinner.className = 'spinner-border spinner-border-sm gl-spinner';
        spinner.setAttribute('aria-hidden', 'true');
        var label = document.createElement('span');
        label.className = 'gl-label';
        label.textContent = loadingText(actions[0] || '');
        overlay.appendChild(spinner);
        overlay.appendChild(label);
        el.appendChild(overlay);

        var entry = {
            el: el,
            compId: compId,
            actions: actions,
            overlay: overlay,
            messageId: null,
            restored: false
        };
        PENDING.set(el, entry);
        entry.timer = setTimeout(function () { restore(el); }, 60000);
    }

    function restore(el) {
        var entry = PENDING.get(el);
        if (!entry || entry.restored) return;
        entry.restored = true;
        clearTimeout(entry.timer);
        PENDING.delete(el);

        el.classList.remove('btn-loading');
        el.removeAttribute('aria-disabled');
        var restoreDisabled = el.getAttribute('data-gl-restore');
        if (restoreDisabled !== null && typeof el.disabled === 'boolean') {
            el.disabled = restoreDisabled === '1';
            el.removeAttribute('data-gl-restore');
        }
        if (entry.overlay && entry.overlay.parentNode === el) {
            el.removeChild(entry.overlay);
        }
    }

    function bindToMessage(message, component) {
        var messageActions = (message.actionQueue || []).map(function (a) { return a.method; });

        PENDING.forEach(function (entry, el) {
            if (entry.restored) return;
            if (entry.compId) {
                if (!component || entry.compId !== component.id) return;
            }
            if (entry.actions.length && !entry.actions.some(function (a) { return messageActions.indexOf(a) !== -1; })) return;
            entry.messageId = message.id;
        });
    }

    function restoreForMessage(message) {
        PENDING.forEach(function (entry, el) {
            if (entry.restored) return;
            if (entry.messageId === message.id) restore(el);
        });
    }

    document.addEventListener('click', function (e) {
        var target = e.target;
        if (!target || !target.getAttribute) return;
        var el = findActionEl(target, 'wire:click');
        if (!el) return;
        if (PENDING.has(el)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return;
        }
        markPending(el);
    }, true);

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || !form.matches || !form.attributes) return;
        if (attrValue(form, 'wire:submit') === null) return;
        var btn = form.querySelector('button[type=submit], input[type=submit]');
        if (btn && !PENDING.has(btn)) markPending(btn);
    }, true);

    Livewire.hook('message.sent', function (message, component) { bindToMessage(message, component); });
    Livewire.hook('message.processed', function (message) { restoreForMessage(message); });
    Livewire.hook('message.failed', function (message) { restoreForMessage(message); });
})();
