(() => {
    const root = document.documentElement;
    const savedTheme = localStorage.getItem('hackathon-radar-theme');

    if (savedTheme === 'light') {
        root.classList.add('light-theme');
    }

    const toggle = document.getElementById('themeToggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            root.classList.toggle('light-theme');
            const theme = root.classList.contains('light-theme') ? 'light' : 'dark';
            localStorage.setItem('hackathon-radar-theme', theme);
        });
    }
})();
