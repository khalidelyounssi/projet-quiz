</main> </div> <script>
    const mobileMenu = document.getElementById('mobile-menu');
    const openBtn = document.getElementById('open-menu');
    const closeBtn = document.getElementById('close-menu');

    if(openBtn) {
        openBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('-translate-x-full');
        });
    }
    if(closeBtn) {
        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.add('-translate-x-full');
        });
    }
</script>
</body>
</html>