<?php
$pageTitle = 'Guía de Recreos - Huanta';
?>

<style>
:root {
    --primary-color: #4CAF50;
    --secondary-color: #FF9800;
    --accent-color: #8BC34A;
    --dark-color: #2E7D32;
    --light-color: #f8f9fa;
    --gray-color: #6c757d;
    --text-color: #333333;
    --shadow: 0 4px 12px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

body {
    background: linear-gradient(135deg, #f9f9f9 0%, #e0f7fa 100%);
    color: var(--text-color);
    line-height: 1.6;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

/* Header Styles */
.search-header {
    background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
    color: white;
    padding: 2rem 0;
    text-align: center;
}

.search-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.search-header p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Main Container */
.main-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Search Section */
.search-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
}

.search-title {
    color: var(--dark-color);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}

.search-box {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.search-input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 50px;
    font-size: 1rem;
    outline: none;
    transition: var(--transition);
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

.search-button {
    padding: 1rem 2rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.search-button:hover {
    background: var(--dark-color);
    transform: translateY(-2px);
}

.search-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.clear-button {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 600;
    transition: var(--transition);
}

.clear-button:hover {
    background: #5a6268;
}

.search-stats {
    color: var(--gray-color);
    font-weight: 500;
}

.search-type-selector {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
    justify-content: center;
}

.type-option {
    padding: 0.75rem 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    background: white;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
}

.type-option.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.type-option:hover {
    border-color: var(--primary-color);
}

/* Results Layout */
.results-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

@media (max-width: 768px) {
    .results-layout {
        grid-template-columns: 1fr;
    }
}

/* Results List */
.results-list {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    max-height: 600px;
    overflow-y: auto;
}

.result-item {
    padding: 1.25rem;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: var(--transition);
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.result-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

.result-item.selected {
    background: #e8f5e9;
    border-left: 4px solid var(--primary-color);
}

.result-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.result-address {
    color: var(--gray-color);
    font-size: 0.95rem;
}

/* Detail Panel */
.detail-panel {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--shadow);
    position: relative;
}

.detail-header {
    margin-bottom: 2rem;
}

.detail-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.detail-subtitle {
    color: var(--gray-color);
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

/* Info Sections */
.info-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-content {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.info-item i {
    color: var(--primary-color);
    font-size: 1.2rem;
    width: 24px;
}

.info-text {
    flex: 1;
}

.info-label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
}

.info-value {
    color: var(--text-color);
}

/* Services */
.services-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.service-tag {
    background: rgba(76, 175, 80, 0.1);
    color: var(--primary-color);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    border: 1px solid rgba(76, 175, 80, 0.2);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.action-btn {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--dark-color);
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline:hover {
    background: rgba(76, 175, 80, 0.1);
}

/* No Selection State */
.no-selection {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--gray-color);
}

.no-selection i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #ddd;
}

/* Loading */
.loading {
    text-align: center;
    padding: 3rem;
}

.loading-spinner {
    width: 3rem;
    height: 3rem;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Error Message */
.error-message {
    background: #fee;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    margin: 1rem 0;
}

/* Token Hidden */
.token-hidden {
    display: none;
}

/* Footer */
.search-footer {
    text-align: center;
    margin-top: 3rem;
    color: var(--gray-color);
    padding: 2rem 0;
    border-top: 1px solid #eee;
}
</style>

<div class="search-header">
    <div class="container">
        <h1>Guía de Recreos en Huanta</h1>
        <p>Encuentra los mejores lugares para disfrutar en Huanta</p>
    </div>
</div>

<div class="main-container">
    <!-- Token Oculto -->
    <div class="token-hidden">
        <input type="text" id="accessToken" value="e6e8bfd763ab3273e50f847abae929ab11984d9987a759caee32bb8a8d34129e_20251104_1">
    </div>

    <!-- Search Section -->
    <section class="search-section">
        <h2 class="search-title">¿Qué recreo estás buscando?</h2>
        <div class="search-box">
            <input type="text" class="search-input" id="search-input" 
                   placeholder="Escribe el nombre, servicio o característica...">
            <button class="search-button" id="search-button">
                Buscar
            </button>
        </div>
        
        <!-- Selector de Tipo de Búsqueda -->
        <div class="search-type-selector">
            <div class="type-option active" data-type="todo">
                <i class="fas fa-search"></i> Todo
            </div>
            <div class="type-option" data-type="nombre">
                <i class="fas fa-signature"></i> Por Nombre
            </div>
            <div class="type-option" data-type="servicio">
                <i class="fas fa-concierge-bell"></i> Por Servicio
            </div>
        </div>
        
        <div class="search-controls">
            <button class="clear-button" id="clear-button">
                Limpiar
            </button>
            <div class="search-stats" id="search-stats">
                Listos para encontrar los mejores recreos
            </div>
        </div>
    </section>

    <!-- Loading -->
    <div id="loading" class="loading" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Buscando recreos...</p>
    </div>

    <!-- Error Message -->
    <div id="error-message" class="error-message" style="display: none;">
        <i class="fas fa-exclamation-triangle"></i>
        <p id="error-text"></p>
    </div>

    <!-- Results Section -->
    <div class="results-layout" id="results-section" style="display: none;">
        <!-- Results List -->
        <div class="results-list" id="results-list">
            <div class="no-selection">
                <i class="fas fa-search"></i>
                <h3>No hay recreos disponibles</h3>
                <p>Realice una búsqueda para ver los resultados</p>
            </div>
        </div>

        <!-- Detail Panel -->
        <div class="detail-panel" id="detail-panel">
            <div class="no-selection" id="no-selection">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Selecciona un recreo</h3>
                <p>Haz clic en un recreo de la lista para ver sus detalles</p>
            </div>
            <div id="detail-content" style="display: none;">
                <!-- Los detalles se cargarán aquí -->
            </div>
        </div>
    </div>
</div>

<div class="search-footer">
    <div class="container">
        <p>© 2023 Guía de Recreos en Huanta - Todos los derechos reservados</p>
    </div>
</div>

<script>
const BASE_URL = '<?php echo BASE_URL; ?>';

let recreos = [];
let filteredRecreos = [];
let currentSearchType = 'todo';
let timeoutBusqueda;

// Elementos DOM
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');
const clearButton = document.getElementById('clear-button');
const resultsList = document.getElementById('results-list');
const detailContent = document.getElementById('detail-content');
const noSelection = document.getElementById('no-selection');
const searchStats = document.getElementById('search-stats');
const loadingDiv = document.getElementById('loading');
const errorDiv = document.getElementById('error-message');
const errorText = document.getElementById('error-text');
const resultsSection = document.getElementById('results-section');
const typeOptions = document.querySelectorAll('.type-option');

// Configurar selectores de tipo
typeOptions.forEach(option => {
    option.addEventListener('click', function() {
        typeOptions.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
        currentSearchType = this.dataset.type;
    });
});

// Función para mostrar/ocultar loading
function mostrarLoading(mostrar) {
    loadingDiv.style.display = mostrar ? 'block' : 'none';
    if (mostrar) {
        resultsSection.style.display = 'none';
        errorDiv.style.display = 'none';
    }
}

// Función para mostrar error
function mostrarError(mensaje) {
    errorText.textContent = mensaje;
    errorDiv.style.display = 'block';
    resultsSection.style.display = 'none';
    loadingDiv.style.display = 'none';
}

// Función para ocultar error
function ocultarError() {
    errorDiv.style.display = 'none';
}

// Función para formatear precio
function formatearPrecio(precio) {
    if (!precio || precio === 0 || precio === '0' || precio === 'Consultar') {
        return 'Consultar';
    }
    if (typeof precio === 'string' && precio.includes('-')) {
        return 'S/ ' + precio;
    }
    if (typeof precio === 'string' && !isNaN(parseFloat(precio))) {
        return 'S/ ' + parseFloat(precio).toFixed(2);
    }
    return precio;
}

// Función para obtener los datos COMPLETOS de un recreo por ID
function obtenerRecreoCompleto(id) {
    const token = document.getElementById('accessToken').value.trim();
    
    return fetch(BASE_URL + `clienteapi/ver/${id}`, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener datos del recreo');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.data) {
            return data.data;
        } else {
            throw new Error(data.message || 'Error al obtener datos completos');
        }
    });
}

// Función para cargar todos los recreos
function cargarTodosRecreos() {
    const token = document.getElementById('accessToken').value.trim();
    
    if (!token) {
        mostrarError('Token no configurado correctamente');
        return;
    }

    mostrarLoading(true);
    ocultarError();

    fetch(BASE_URL + 'clienteapi/listar', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        mostrarLoading(false);
        if (data.success && data.data) {
            recreos = data.data;
            filteredRecreos = [...recreos];
            resultsSection.style.display = 'grid';
            mostrarResultadosLista(filteredRecreos);
            actualizarEstadisticas();
            
            // Seleccionar el primer recreo automáticamente
            if (recreos.length > 0) {
                setTimeout(() => {
                    mostrarDetallesRecreo(recreos[0]);
                    const firstItem = document.querySelectorAll('.result-item')[0];
                    if (firstItem) {
                        firstItem.classList.add('selected');
                    }
                }, 100);
            }
        } else {
            mostrarError(data.message || 'No se pudieron cargar los recreos');
        }
    })
    .catch(error => {
        mostrarLoading(false);
        mostrarError('Error de conexión: ' + error.message);
        console.error('Error:', error);
    });
}

// Función para buscar recreos
function buscarRecreos() {
    const token = document.getElementById('accessToken').value.trim();
    const termino = searchInput.value.trim();
    
    if (!token) {
        mostrarError('Token no configurado');
        return;
    }

    // Si el término es muy corto, no buscar (esto evita búsquedas con 0 caracteres)
    if (termino.length === 0) {
        cargarTodosRecreos();
        return;
    }

    mostrarLoading(true);
    ocultarError();

    const url = BASE_URL + `clienteapi/buscar?q=${encodeURIComponent(termino)}&tipo=${currentSearchType}`;
    
    fetch(url, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la búsqueda');
        }
        return response.json();
    })
    .then(data => {
        mostrarLoading(false);
        if (data.success && data.data) {
            filteredRecreos = data.data;
            resultsSection.style.display = 'grid';
            mostrarResultadosLista(filteredRecreos);
            actualizarEstadisticas();
        } else {
            mostrarError(data.message || 'No se encontraron resultados');
        }
    })
    .catch(error => {
        mostrarLoading(false);
        mostrarError('Error en la búsqueda: ' + error.message);
        console.error('Error:', error);
    });
}

// Función para mostrar lista de resultados
function mostrarResultadosLista(recreos) {
    resultsList.innerHTML = '';
    
    if (!recreos || recreos.length === 0) {
        resultsList.innerHTML = `
            <div class="no-selection">
                <i class="fas fa-search"></i>
                <h3>No se encontraron recreos</h3>
                <p>Intenta con otros términos de búsqueda</p>
            </div>
        `;
        return;
    }
    
    recreos.forEach((recreo) => {
        const div = document.createElement('div');
        div.className = 'result-item';
        div.innerHTML = `
            <div class="result-name">${recreo.nombre || 'Nombre no disponible'}</div>
            <div class="result-address">${recreo.direccion || 'Dirección no disponible'}</div>
        `;
        
        div.addEventListener('click', () => {
            // Remover selección anterior
            document.querySelectorAll('.result-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Seleccionar actual
            div.classList.add('selected');
            
            // Mostrar detalles - SIEMPRE obtener datos completos
            mostrarDetallesRecreoCompleto(recreo);
        });
        
        resultsList.appendChild(div);
    });
}

// Función para mostrar detalles del recreo con datos COMPLETOS
function mostrarDetallesRecreoCompleto(recreo) {
    noSelection.style.display = 'none';
    detailContent.style.display = 'block';
    
    // Mostrar loading mientras obtenemos datos completos
    detailContent.innerHTML = `
        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Cargando información completa...</p>
        </div>
    `;
    
    // Siempre obtener los datos COMPLETOS del recreo
    obtenerRecreoCompleto(recreo.id)
        .then(recreoCompleto => {
            // Usar los datos completos para mostrar la información
            mostrarDetallesConDatosCompletos(recreoCompleto);
        })
        .catch(error => {
            console.error('Error al obtener datos completos:', error);
            // Si falla, mostrar con los datos que tenemos
            mostrarDetallesConDatosCompletos(recreo);
        });
}

// Función para mostrar detalles con datos completos
function mostrarDetallesConDatosCompletos(recreo) {
    // Procesar servicios para asegurar que sea un array
    let servicios = [];
    if (Array.isArray(recreo.servicios)) {
        servicios = recreo.servicios;
    } else if (recreo.servicios && typeof recreo.servicios === 'string') {
        servicios = recreo.servicios.split(',').map(s => s.trim());
    } else if (recreo.servicio) {
        // Usar el campo 'servicio' como fallback
        if (Array.isArray(recreo.servicio)) {
            servicios = recreo.servicio;
        } else if (typeof recreo.servicio === 'string') {
            servicios = recreo.servicio.split(',').map(s => s.trim());
        }
    }
    
    // Si no hay servicios, mostrar mensaje
    if (servicios.length === 0) {
        servicios = ['Servicios no especificados'];
    }
    
    detailContent.innerHTML = `
        <div class="detail-header">
            <h2 class="detail-title">${recreo.nombre || 'Nombre no disponible'}</h2>
            <div class="detail-subtitle">
                <i class="fas fa-location-dot"></i> 
                ${recreo.ubicacion || 'Ubicación no disponible'} • 
                ${recreo.direccion || 'Dirección no disponible'}
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <i class="far fa-clock"></i>
                <div class="info-text">
                    <div class="info-label">Horario</div>
                    <div class="info-value">${recreo.horario || 'No especificado'}</div>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-tag"></i>
                <div class="info-text">
                    <div class="info-label">Precios</div>
                    <div class="info-value">${formatearPrecio(recreo.precio_numero || recreo.precio)}</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Ubicación
            </h3>
            <div class="info-content">
                <div class="info-item">
                    <i class="fas fa-location-dot"></i>
                    <div class="info-text">
                        <div class="info-label">Ubicación</div>
                        <div class="info-value">${recreo.ubicacion || 'No disponible'}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-map-pin"></i>
                    <div class="info-text">
                        <div class="info-label">Dirección</div>
                        <div class="info-value">${recreo.direccion || 'No disponible'}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-compass"></i>
                    <div class="info-text">
                        <div class="info-label">Referencia del Lugar</div>
                        <div class="info-value">${recreo.referencia || 'Referencia no especificada'}</div>
                    </div>
                </div>
                
                ${recreo.telefono && recreo.telefono !== 'No disponible' ? `
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div class="info-text">
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">${recreo.telefono}</div>
                    </div>
                </div>
                ` : ''}
            </div>
        </div>

        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-concierge-bell"></i>
                Servicios
            </h3>
            <div class="info-content">
                <div class="services-list">
                    ${servicios.map(servicio => 
                        `<span class="service-tag">${servicio}</span>`
                    ).join('')}
                </div>
            </div>
        </div>

        <div class="action-buttons">
            ${recreo.telefono && recreo.telefono !== 'No disponible' && recreo.telefono !== 'Consultar' ? `
            <button class="action-btn btn-primary" onclick="window.open('tel:${recreo.telefono}')">
                <i class="fas fa-phone"></i>
                Llamar ahora
            </button>
            ` : '<button class="action-btn btn-primary" disabled><i class="fas fa-phone"></i> Sin teléfono</button>'}
            ${recreo.url_maps ? `
            <button class="action-btn btn-outline" onclick="window.open('${recreo.url_maps}', '_blank')">
                <i class="fas fa-map"></i>
                Ver en Maps
            </button>
            ` : `
            <button class="action-btn btn-outline" onclick="window.open('https://maps.google.com?q=${encodeURIComponent((recreo.ubicacion || '') + ' ' + (recreo.direccion || ''))}', '_blank')">
                <i class="fas fa-directions"></i>
                Cómo llegar
            </button>
            `}
        </div>
    `;
}

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    const count = filteredRecreos.length;
    const total = recreos.length;
    searchStats.textContent = `Mostrando ${count} de ${total} recreos`;
}

// Función para limpiar búsqueda
function limpiarBusqueda() {
    // Cancelar cualquier búsqueda pendiente
    clearTimeout(timeoutBusqueda);
    
    searchInput.value = '';
    filteredRecreos = [...recreos];
    mostrarResultadosLista(filteredRecreos);
    actualizarEstadisticas();
    searchInput.focus();
}

// Event listeners
searchButton.addEventListener('click', buscarRecreos);
clearButton.addEventListener('click', limpiarBusqueda);
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarRecreos();
    }
});

// Búsqueda automática al escribir
searchInput.addEventListener('input', function(e) {
    const termino = searchInput.value.trim();
    
    // Limpiar el timeout anterior
    clearTimeout(timeoutBusqueda);
    
    // Si hay al menos un carácter, buscar automáticamente después de 300ms
    if (termino.length >= 1) {
        timeoutBusqueda = setTimeout(() => {
            buscarRecreos();
        }, 300);
    } else if (termino.length === 0) {
        // Si se borra todo, mostrar todos los recreos inmediatamente
        limpiarBusqueda();
    }
});

// Inicializar la aplicación
document.addEventListener('DOMContentLoaded', function() {
    cargarTodosRecreos();
});
</script>
