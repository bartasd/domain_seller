const popup = document.getElementById('popup');

if (popup) {
    setTimeout(() => {
        popup.remove();
    }, 3000);
}

const cookieConsent = document.getElementById('cookieConsent');
const acceptCookies = document.getElementById('acceptCookies');

if (!localStorage.getItem('cookieConsent')) {
    cookieConsent.hidden = false;
}

acceptCookies.addEventListener('click', () => {
    localStorage.setItem('cookieConsent', 'accepted');
    cookieConsent.hidden = true;
});