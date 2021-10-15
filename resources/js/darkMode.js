// Make dark mode switch.
const darkModeToggles = document.getElementsByClassName('darkModeToggle');

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark');
} else {
  document.documentElement.classList.remove('dark');
}

for (let i = 0; i < darkModeToggles.length; i++) {
  darkModeToggles[i].onclick = () => {
    if (localStorage.theme === 'light') {
      localStorage.theme = 'dark';
      document.querySelector('html').classList.add('dark');
      document.querySelector('html').classList.remove('light');
    } else {
      localStorage.theme = 'light';
      document.querySelector('html').classList.remove('dark');
      document.querySelector('html').classList.add('light');
    }
  };
}

