//valida si en el DOM exsite un elemento html con id mapa, antes de insertar el código
if(document.querySelector('#map')) {

    //nuestras variables personalizadas
    const lat = 39.94695982671244;
    const lng = -0.06110297132978128;
    const zoom = 15;

    const map = L.map('map').setView([lat, lng], zoom);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    //configuración personalizada del marcador
    L.marker([lat, lng]).addTo(map)
        .bindPopup(` 
            <h3 class="mapa__heading">CampusDevWeb</h3>
            <p class="mapa__texto">Almassora - Castelló</p>
        `)
        .openPopup();

}


