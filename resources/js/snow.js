// Lightweight, atmospheric winter snow effect
(function initWinterSnow() {
    if (typeof window === 'undefined') return;

    // Respect reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    function startSnow() {
        if (document.getElementById('winter-snow-canvas')) return;

        const canvas = document.createElement('canvas');
        canvas.id = 'winter-snow-canvas';
        canvas.setAttribute('aria-hidden', 'true');
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100vw';
        canvas.style.height = '100vh';
        canvas.style.pointerEvents = 'none';
        canvas.style.zIndex = '35';
        canvas.style.opacity = '0.8';
        document.body.appendChild(canvas);

        const ctx = canvas.getContext('2d');
        let width = (canvas.width = window.innerWidth);
        let height = (canvas.height = window.innerHeight);

        let resizeTimeout;
        window.addEventListener(
            'resize',
            () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    width = canvas.width = window.innerWidth;
                    height = canvas.height = window.innerHeight;
                }, 100);
            },
            { passive: true }
        );

        const particleCount = width < 768 ? 22 : 45;
        const flakes = [];

        for (let i = 0; i < particleCount; i++) {
            flakes.push({
                x: Math.random() * width,
                y: Math.random() * height,
                radius: Math.random() * 2.2 + 1.0,
                speedY: Math.random() * 0.8 + 0.4,
                speedX: Math.random() * 0.4 - 0.2,
                opacity: Math.random() * 0.5 + 0.3,
                swayStep: Math.random() * Math.PI * 2,
                swaySpeed: Math.random() * 0.015 + 0.005,
            });
        }

        let isVisible = true;
        document.addEventListener('visibilitychange', () => {
            isVisible = !document.hidden;
        });

        function animate() {
            if (isVisible) {
                ctx.clearRect(0, 0, width, height);
                const isDark = document.documentElement.classList.contains('dark');

                for (let i = 0; i < flakes.length; i++) {
                    const f = flakes[i];
                    f.swayStep += f.swaySpeed;
                    f.y += f.speedY;
                    f.x += Math.sin(f.swayStep) * 0.5 + f.speedX;

                    if (f.y > height + 6) {
                        f.y = -6;
                        f.x = Math.random() * width;
                    }
                    if (f.x > width + 6) f.x = -6;
                    if (f.x < -6) f.x = width + 6;

                    ctx.beginPath();
                    ctx.arc(f.x, f.y, f.radius, 0, Math.PI * 2);
                    ctx.fillStyle = isDark
                        ? `rgba(255, 255, 255, ${f.opacity})`
                        : `rgba(140, 180, 220, ${f.opacity * 0.85})`;
                    ctx.fill();
                }
            }
            requestAnimationFrame(animate);
        }

        requestAnimationFrame(animate);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startSnow);
    } else {
        startSnow();
    }
})();
