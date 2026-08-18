const popup = document.getElementById('popup');

if (popup) {
    setTimeout(() => {
        popup.remove();
    }, 3000);
}