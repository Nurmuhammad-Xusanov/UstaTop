import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

(function () {
    const root = document.documentElement;

    // 1️⃣ Logged in user → DB
    if (window.AUTHENTICATED && window.USER_THEME) {
        if (window.USER_THEME === "dark") root.classList.add("dark");
        if (window.USER_THEME === "light") root.classList.remove("dark");
        return;
    }

    // 2️⃣ Guest → localStorage
    const stored = localStorage.getItem("theme");
    if (stored) {
        root.classList.toggle("dark", stored === "dark");
        return;
    }

    // 3️⃣ Fallback → system
    if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        root.classList.add("dark");
    }
})();

window.toggleTheme = async function () {
    const root = document.documentElement;
    const isDark = root.classList.toggle('dark');
    const theme = isDark ? 'dark' : 'light';

    try {
        localStorage.setItem('theme', theme);
        document.cookie = "theme=" + theme + "; path=/; max-age=" + (365 * 24 * 60 * 60);
    } catch (e) {}

    // Logged in → Persist to database across devices
    if (window.AUTHENTICATED) {
        try {
            const tokenEl = document.querySelector('meta[name="csrf-token"]');
            if (tokenEl) {
                await fetch('/theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': tokenEl.content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme })
                });
            }
        } catch (e) {
            console.error('Failed to sync theme:', e);
        }
    }
};

// Sync guest theme to account upon first load if logged in
if (window.AUTHENTICATED) {
    const guestTheme = localStorage.getItem('theme');
    if (guestTheme && window.USER_THEME && guestTheme !== window.USER_THEME) {
        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        if (tokenEl) {
            fetch('/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenEl.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: guestTheme })
            }).catch(() => {});
        }
    }
}


