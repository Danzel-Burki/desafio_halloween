document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.votar');

    voteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const confirmVote = confirm("¿Estás seguro de que quieres votar por este disfraz?");
            if (!confirmVote) {
                event.preventDefault(); // Prevent form submission if user cancels
            }
        });
    });
});


document.querySelectorAll('.disfraz-img').forEach(img => {
    img.addEventListener('click', function() {
        const lightbox = document.createElement('div');
        lightbox.classList.add('lightbox');
        
        const lightboxImg = document.createElement('img');
        lightboxImg.classList.add('lightbox-img');
        lightboxImg.src = this.src; // Usar la imagen clickeada
        lightbox.appendChild(lightboxImg);

        const closeBtn = document.createElement('span');
        closeBtn.classList.add('close');
        closeBtn.innerHTML = '&times;';
        closeBtn.addEventListener('click', () => {
            lightbox.remove();
        });
        lightbox.appendChild(closeBtn);

        document.body.appendChild(lightbox);
    });
});
