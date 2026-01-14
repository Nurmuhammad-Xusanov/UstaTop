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

async function toggleTheme() {
    const root = document.documentElement;
    const isDark = root.classList.toggle('dark');
    const theme = isDark ? 'dark' : 'light';

    // Logged in → DB
    if (window.AUTHENTICATED) {
        await fetch('/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ theme })
        });
        return;
    }

    // Guest → localStorage
    localStorage.setItem('theme', theme);
}

if (window.AUTHENTICATED) {
    const guestTheme = localStorage.getItem('theme');
    if (guestTheme) {
        fetch('/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ theme: guestTheme })
        });
        localStorage.removeItem('theme');
    }
}


// var themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
// var themeToggleLightIcon = document.getElementById("theme-toggle-light-icon");

// if (
//     localStorage.getItem("color-theme") === "dark" ||
//     (!("color-theme" in localStorage) &&
//         window.matchMedia("(prefers-color-scheme: dark)").matches)
// ) {
//     themeToggleLightIcon.classList.remove("hidden");
// } else {
//     themeToggleDarkIcon.classList.remove("hidden");
// }

// var themeToggleBtn = document.getElementById("theme-toggle");

// themeToggleBtn.addEventListener("click", function () {
//     themeToggleDarkIcon.classList.toggle("hidden");
//     themeToggleLightIcon.classList.toggle("hidden");

//     if (localStorage.getItem("color-theme")) {
//         if (localStorage.getItem("color-theme") === "light") {
//             document.documentElement.classList.add("dark");
//             localStorage.setItem("color-theme", "dark");
//         } else {
//             document.documentElement.classList.remove("dark");
//             localStorage.setItem("color-theme", "light");
//         }
//     } else {
//         if (document.documentElement.classList.contains("dark")) {
//             document.documentElement.classList.remove("dark");
//             localStorage.setItem("color-theme", "light");
//         } else {
//             document.documentElement.classList.add("dark");
//             localStorage.setItem("color-theme", "dark");
//         }
//     }
// });
