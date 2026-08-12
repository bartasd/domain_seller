const popup = document.getElementById('popup');

if (popup) {
    setTimeout(() => {
        popup.remove();
    }, 3000);
}

document.addEventListener('keydown', function (event) {

    if (event.key === 'Enter') {
        document.querySelector('#proposalForm').requestSubmit();
    }

});