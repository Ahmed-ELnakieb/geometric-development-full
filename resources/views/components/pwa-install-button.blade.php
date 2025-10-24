<!-- PWA Install Button -->
<button id="pwa-install-btn" class="pwa-install-btn" style="display: none;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 9H15L13 7H9V9H5C3.9 9 3 9.9 3 11V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V11C21 9.9 20.1 9 19 9ZM19 18H5V11H19V18ZM7.5 13.5L9 12L10.5 13.5L12 12L13.5 13.5L15 12L16.5 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Install App
</button>

<style>
.pwa-install-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pwa-install-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
}

.pwa-install-btn svg {
    width: 18px;
    height: 18px;
}

@media (max-width: 768px) {
    .pwa-install-btn {
        bottom: 15px;
        right: 15px;
        padding: 10px 16px;
        font-size: 13px;
    }
}
</style>

<script>
// PWA Install Button Functionality
let deferredPrompt;
const installBtn = document.getElementById('pwa-install-btn');

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    installBtn.style.display = 'flex';
});

installBtn.addEventListener('click', async () => {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            installBtn.style.display = 'none';
        }
        deferredPrompt = null;
    }
});

window.addEventListener('appinstalled', () => {
    installBtn.style.display = 'none';
});
</script>