const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'https://ustatop-lmze.onrender.com';
const CHROME_PATH = '/usr/bin/google-chrome-stable';
const OUT_DIR = path.join(__dirname, '../docs/screenshots');

if (!fs.existsSync(OUT_DIR)) {
    fs.mkdirSync(OUT_DIR, { recursive: true });
}

async function run() {
    console.log('Launching Chrome...');
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            '--window-size=1440,900'
        ],
        defaultViewport: {
            width: 1440,
            height: 900,
            deviceScaleFactor: 2
        }
    });

    const page = await browser.newPage();

    // 1. Landing Page (Dark)
    console.log('Capturing Landing Page (Dark)...');
    await page.goto(`${BASE_URL}/`, { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    });
    await new Promise(r => setTimeout(r, 1000));
    await page.screenshot({ path: path.join(OUT_DIR, 'landing-dark.png') });

    // 2. Landing Page (Light)
    console.log('Capturing Landing Page (Light)...');
    await page.evaluate(() => {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    });
    await new Promise(r => setTimeout(r, 1000));
    await page.screenshot({ path: path.join(OUT_DIR, 'landing-light.png') });

    // 3. Login as Admin
    console.log('Logging in as Admin...');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
    });
    await page.type('#email', 'nursherzil@gmail.com');
    await page.type('#password', 'password123');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle2' }),
        page.click('button[type="submit"]')
    ]);

    // Ensure dark mode inside dashboard
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
    });

    // 4. Admin Dashboard
    console.log('Capturing Admin Dashboard...');
    await page.goto(`${BASE_URL}/admin/dashboard`, { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
    });
    await new Promise(r => setTimeout(r, 1500));
    await page.screenshot({ path: path.join(OUT_DIR, 'admin-dashboard.png') });

    // 5. Admin Master Applications (Pending & Approved)
    console.log('Capturing Admin Master Applications...');
    await page.goto(`${BASE_URL}/admin/provider-requests`, { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
    });
    await new Promise(r => setTimeout(r, 1500));
    await page.screenshot({ path: path.join(OUT_DIR, 'admin-masters.png') });

    // 6. Admin Service Requests
    console.log('Capturing Admin Service Requests...');
    await page.goto(`${BASE_URL}/admin/service-requests`, { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
    });
    await new Promise(r => setTimeout(r, 1500));
    await page.screenshot({ path: path.join(OUT_DIR, 'admin-services.png') });

    // 7. Profile & Preferences (with English language active to verify dynamic localization & no stickers)
    console.log('Capturing Profile Preferences...');
    await page.goto(`${BASE_URL}/locale/en`, { waitUntil: 'networkidle2' });
    await page.goto(`${BASE_URL}/profile`, { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        document.documentElement.classList.add('dark');
    });
    await new Promise(r => setTimeout(r, 1500));
    await page.screenshot({ path: path.join(OUT_DIR, 'profile-preferences.png') });

    console.log('All screenshots captured successfully!');
    await browser.close();
}

run().catch(err => {
    console.error('Error capturing screenshots:', err);
    process.exit(1);
});
