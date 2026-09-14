// Variable qui mémorise l'overlay actuellement sélectionné
let selectedOverlays = [];

// --- Webcam ---
navigator.mediaDevices.getUserMedia({ video: true })
    .then((stream) => {
        const video = document.getElementById("webcam");
        video.srcObject = stream;
    })
    .catch((error) => {
        console.error("Error accessing webcam: ", error);
    });

// --- Sélection de l'overlay ---
const overlayThumbs = document.querySelectorAll('.overlay-thumb');
const captureBtn = document.getElementById('capture-btn');

overlayThumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
        const overlayPath = thumb.dataset.overlay;

        if (selectedOverlays.includes(overlayPath)) {
            // déjà sélectionné → on le retire
            selectedOverlays = selectedOverlays.filter((path) => path !== overlayPath);
            thumb.classList.remove('selected');
        } else {
            // pas encore sélectionné → on l'ajoute
            selectedOverlays.push(overlayPath);
            thumb.classList.add('selected');
        }

        // le bouton capture est actif seulement s'il y a au moins un overlay choisi
        captureBtn.disabled = selectedOverlays.length === 0;

        updateOverlayPreview();
    });
});

function updateOverlayPreview() {
    const container = document.querySelector('.webcam-container');

    // Retirer les anciennes previews d'overlay
    document.querySelectorAll('.overlay-preview-img').forEach((img) => img.remove());

    // Recréer une balise <img> pour chaque overlay sélectionné
    selectedOverlays.forEach((overlayPath) => {
        const img = document.createElement('img');
        img.src = overlayPath;
        img.className = 'overlay-preview-img';
        img.style.position = 'absolute';
        img.style.top = '0';
        img.style.left = '0';
        img.style.width = '400px';
        img.style.height = '300px';
        container.appendChild(img);
    });
}

captureBtn.addEventListener('click', () => {
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const video = document.getElementById('webcam');

    // On ne capture QUE la vidéo, sans overlay (la fusion se fera côté serveur)
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/png');

    uploadImage(dataUrl);
});

function uploadImage(dataUrl) {
    const formData = new FormData();
    formData.append('photo', dataUrl);
    selectedOverlays.forEach((overlayPath) => {
        formData.append('overlays[]', overlayPath);
    });

    fetch('/edit-images/create', {
        method: 'POST',
        body: formData
    })
    .then((response) => response.text())
    .then((result) => {
        console.log('Success:', result);
        window.location.reload(); // recharge la page pour voir la nouvelle image dans la sidebar
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}