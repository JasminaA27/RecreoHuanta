<?php
// Incluir modelos para usar datos reales
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Recreo.php';
require_once __DIR__ . '/../models/TokenApi.php';

//TOKEN
$TOKEN_VALIDO = "7ed5be94b77a9d33f5d4e5d2c99703504eb108cdb6d65c87ea1962917fcb63bb_20251021_2";

// Verificar token automáticamente con el token hardcodeado
$tokenModel = new TokenApi();
$tokenData = $tokenModel->getByToken($TOKEN_VALIDO);

if (!$tokenData || !$tokenData['estado']) {
    // Mostrar error si el token hardcodeado no es válido
    mostrarErrorToken('Token no válido en el sistema');
    exit;
}

// Si el token es válido, obtener los recreos
$recreoModel = new Recreo();
$recreosReales = $recreoModel->getActive();

// Convertir a formato para el frontend
$recreos = [];
foreach ($recreosReales as $recreoReal) {
    $recreos[] = [
        'id' => $recreoReal['id'],
        'nombre' => $recreoReal['nombre'],
        'direccion' => $recreoReal['direccion'],
        'telefono' => $recreoReal['telefono'] ?? 'No disponible',
        'descripcion' => $recreoReal['servicio'] ?? 'Descripción no disponible',
        'servicios' => $recreoReal['servicio'] ? [$recreoReal['servicio']] : ['Servicios no especificados'],
        'horario' => $recreoReal['horario'] ?? 'Horario no especificado',
        'precio' => $recreoReal['precio'] ? ($recreoReal['precio'] > 50 ? '$$$' : ($recreoReal['precio'] > 25 ? '$$' : '$')) : '$$',
        'precio_numero' => $recreoReal['precio'] ?? 0,
        'referencia' => $recreoReal['referencia'] ?? 'Referencia no disponible',
        'imagen' => $recreoReal['url_maps'] ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
        'destacado' => false
    ];
}

function mostrarErrorToken($mensaje) {
    http_response_code(401);
    die('
    <!DOCTYPE html>
    <html>
    <head>
        <title>Acceso Denegado</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
                margin: 0; 
            }
            .error { 
                background: white; 
                padding: 2rem; 
                border-radius: 10px; 
                text-align: center; 
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            }
            .error h1 { color: #e74c3c; }
            .error p { color: #666; }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>🔐 Acceso Denegado</h1>
            <p>' . $mensaje . '</p>
        </div>
    </body>
    </html>');
}

// Convertir recreos a JSON para JavaScript
$recreosJson = json_encode($recreos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guía de Recreos - Huanta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f9f9f9 0%, #e0f7fa 100%);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            padding-bottom: 2rem;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.1"><circle cx="50" cy="50" r="40" fill="white"/></svg>');
            background-size: 200px;
            opacity: 0.1;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .logo {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .search-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            position: relative;
        }
        
        .search-title {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
        }
        
        .search-box {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
        }
        
        .search-input-container {
            position: relative;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        input[type="text"] {
            padding: 16px 20px 16px 50px;
            border: 2px solid #ddd;
            border-radius: 50px;
            font-size: 16px;
            width: 100%;
            transition: var(--transition);
            outline: none;
            flex: 1;
        }
        
        input[type="text"]:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }
        
        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
        }
        
        .search-button {
            padding: 16px 24px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .search-button:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }
        
        .clear-button {
            padding: 16px 24px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .clear-button:hover {
            background-color: #5a6268;
        }
        
        .search-controls {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .suggestions {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-height: 300px;
            overflow-y: auto;
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 10;
            margin-top: 5px;
        }
        
        .suggestion-item {
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition);
        }
        
        .suggestion-item:hover {
            background-color: #f0f9ff;
        }
        
        .suggestion-icon {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .results-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        @media (min-width: 992px) {
            .results-section {
                grid-template-columns: 1fr 2fr;
            }
        }
        
        .results-list {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-height: 600px;
            overflow-y: auto;
        }
        
        .result-item {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .result-item:hover {
            background-color: #f5fdf7;
        }
        
        .result-item.selected {
            background-color: #e8f5e9;
            border-left: 5px solid var(--primary-color);
        }
        
        .result-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .result-info {
            flex-grow: 1;
        }
        
        .result-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .result-address {
            color: var(--gray-color);
            font-size: 0.9rem;
        }
        
        .detail-panel {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2rem;
            position: relative;
        }
        
        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 1.5rem;
        }
        
        .detail-title {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .detail-address {
            color: var(--gray-color);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0.5rem;
        }
        
        .detail-phone {
            color: var(--gray-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .detail-content {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .detail-image {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 1rem;
            height: 200px;
            object-fit: cover;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }
        
        .service-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }
        
        .service-icon {
            color: var(--primary-color);
            font-size: 1.2rem;
            width: 24px;
        }
        
        .no-results, .no-selection {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-color);
        }
        
        .no-results i, .no-selection i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        .hidden {
            display: none;
        }
        
        .tag {
            display: inline-block;
            background-color: #e8f5e9;
            color: var(--primary-color);
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .price-indicator {
            display: flex;
            gap: 5px;
            margin-top: 0.5rem;
        }
        
        .price-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ddd;
        }
        
        .price-dot.filled {
            background-color: var(--secondary-color);
        }
        
        .section-title {
            color: var(--dark-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-color);
            display: inline-block;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: #e8f5e9;
        }
        
        .featured-tag {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        footer {
            text-align: center;
            margin-top: 3rem;
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .search-stats {
            text-align: center;
            margin-top: 1rem;
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        /* ESTILOS ACTUALIZADOS SIN COLORES */
        .info-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1rem 0;
            border-left: 4px solid var(--primary-color);
        }

        .price-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1rem 0;
            border-left: 4px solid var(--secondary-color);
        }

        .reference-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-color);
        }

        .price-amount {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .price-label {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .reference-text {
            color: var(--text-color);
            line-height: 1.6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .info-card {
            background: #f8f9fa;
            padding: 1.2rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }

        .info-card h4 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card h4 i {
            color: var(--primary-color);
        }

        .contact-info {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1rem 0;
            border-left: 4px solid var(--primary-color);
        }

        /* Campo token oculto */
        .token-hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!-- CAMPO TOKEN OCULTO -->
    <input type="hidden" id="accessToken" value="<?php echo $TOKEN_VALIDO; ?>">
    
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-utensils"></i>
            </div>
            <h1>Guía de Recreos en Huanta</h1>
            <p>Encuentra los mejores lugares para disfrutar en Huanta</p>
        </div>
    </header>
    
    <div class="container">
        <section class="search-section">
            <h2 class="search-title">¿Qué recreo estás buscando?</h2>
            <div class="search-box">
                <div class="search-input-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="search-input" placeholder="Escribe el nombre, servicio o característica...">
                    <button class="search-button" id="search-button">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
                <div class="search-controls">
                    <button class="clear-button" id="clear-button">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
                <div class="suggestions" id="suggestions">
                    <!-- Las sugerencias se cargarán aquí -->
                </div>
                <div class="search-stats" id="search-stats">
                    Mostrando <span id="results-count"><?php echo count($recreos); ?></span> de <?php echo count($recreos); ?> recreos
                </div>
            </div>
        </section>
        
        <section class="results-section">
            <div class="results-list" id="results-list">
                <!-- Los resultados se cargarán aquí -->
            </div>
            
            <div class="detail-panel" id="detail-panel">
                <div class="no-selection" id="no-selection">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Selecciona un recreo para ver sus detalles</h3>
                    <p>Busca en la lista de la izquierda y haz clic en un recreo para ver toda su información.</p>
                </div>
                <div class="detail-content hidden" id="detail-content">
                    <!-- Los detalles se cargarán aquí -->
                </div>
            </div>
        </section>
    </div>

    <footer>
        <p>© 2023 Guía de Recreos en Huanta - Todos los derechos reservados</p>
    </footer>

    <script>
        // Usar datos REALES de PHP
        const recreos = <?php echo $recreosJson; ?>;
        let filteredRecreos = [...recreos];

        // Elementos DOM
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const clearButton = document.getElementById('clear-button');
        const suggestionsBox = document.getElementById('suggestions');
        const resultsList = document.getElementById('results-list');
        const detailContent = document.getElementById('detail-content');
        const noSelection = document.getElementById('no-selection');
        const searchStats = document.getElementById('search-stats');
        const resultsCount = document.getElementById('results-count');
        
        // Función para formatear precio
        function formatearPrecio(precio) {
            if (!precio || precio === 0) {
                return 'Consultar';
            }
            return `S/ ${parseFloat(precio).toFixed(2)}`;
        }

        // Función para obtener etiqueta de precio
        function obtenerEtiquetaPrecio(precio) {
            if (!precio || precio === 0) return 'Consultar precio';
            if (precio <= 15) return 'Económico';
            if (precio <= 30) return 'Moderado';
            if (precio <= 50) return 'Alto';
            return 'Premium';
        }
        
        // Función para realizar búsqueda
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            if (searchTerm.length === 0) {
                filteredRecreos = [...recreos];
                suggestionsBox.style.display = 'none';
            } else {
                // Filtrar recreos por NOMBRE, DESCRIPCIÓN, SERVICIOS y REFERENCIA
                filteredRecreos = recreos.filter(recreo => 
                    recreo.nombre.toLowerCase().includes(searchTerm) ||
                    recreo.descripcion.toLowerCase().includes(searchTerm) ||
                    recreo.direccion.toLowerCase().includes(searchTerm) ||
                    recreo.referencia.toLowerCase().includes(searchTerm) ||
                    recreo.servicios.some(servicio => 
                        servicio.toLowerCase().includes(searchTerm)
                    )
                );
                
                // Mostrar sugerencias
                showSuggestions(filteredRecreos);
            }
            
            // Mostrar resultados
            displayResults(filteredRecreos);
            updateSearchStats();
        }
        
        // Búsqueda en tiempo real al escribir
        searchInput.addEventListener('input', function() {
            performSearch();
        });
        
        // Búsqueda al hacer clic en el botón
        searchButton.addEventListener('click', function() {
            performSearch();
            searchInput.focus();
        });
        
        // Búsqueda al presionar Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Limpiar búsqueda
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            filteredRecreos = [...recreos];
            suggestionsBox.style.display = 'none';
            displayResults(filteredRecreos);
            updateSearchStats();
            searchInput.focus();
        });
        
        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target) && !searchButton.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
        
        // Actualizar estadísticas de búsqueda
        function updateSearchStats() {
            resultsCount.textContent = filteredRecreos.length;
        }
        
        // Mostrar sugerencias de búsqueda
        function showSuggestions(recreos) {
            if (recreos.length === 0) {
                suggestionsBox.style.display = 'none';
                return;
            }
            
            suggestionsBox.innerHTML = '';
            recreos.slice(0, 5).forEach(recreo => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.innerHTML = `
                    <i class="fas fa-utensils suggestion-icon"></i>
                    <div>
                        <div>${recreo.nombre}</div>
                        <small>${recreo.direccion}</small>
                    </div>
                `;
                div.addEventListener('click', () => {
                    searchInput.value = recreo.nombre;
                    suggestionsBox.style.display = 'none';
                    filteredRecreos = [recreo];
                    displayResults(filteredRecreos);
                    showRecreoDetails(recreo);
                    updateSearchStats();
                });
                suggestionsBox.appendChild(div);
            });
            
            suggestionsBox.style.display = 'block';
        }
        
        // Mostrar resultados de búsqueda
        function displayResults(recreos) {
            resultsList.innerHTML = '';
            
            if (recreos.length === 0) {
                resultsList.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron recreos</h3>
                        <p>Intenta con otros términos de búsqueda</p>
                    </div>
                `;
                return;
            }
            
            recreos.forEach(recreo => {
                const div = document.createElement('div');
                div.className = 'result-item';
                div.innerHTML = `
                    <div class="result-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="result-info">
                        <div class="result-name">${recreo.nombre}</div>
                        <div class="result-address">${recreo.direccion}</div>
                        <div class="price-indicator">
                            ${Array(3).fill(0).map((_, i) => 
                                `<div class="price-dot ${i < (recreo.precio === '$' ? 1 : recreo.precio === '$$' ? 2 : 3) ? 'filled' : ''}"></div>`
                            ).join('')}
                        </div>
                    </div>
                `;
                
                div.addEventListener('click', () => {
                    // Remover selección anterior
                    document.querySelectorAll('.result-item').forEach(item => {
                        item.classList.remove('selected');
                    });
                    
                    // Seleccionar actual
                    div.classList.add('selected');
                    
                    // Mostrar detalles
                    showRecreoDetails(recreo);
                });
                
                resultsList.appendChild(div);
            });
        }
        
        // Mostrar detalles del recreo seleccionado
        function showRecreoDetails(recreo) {
            noSelection.classList.add('hidden');
            detailContent.classList.remove('hidden');
            
            // Generar indicador de precio
            const priceDots = Array(3).fill(0).map((_, i) => 
                `<div class="price-dot ${i < (recreo.precio === '$' ? 1 : recreo.precio === '$$' ? 2 : 3) ? 'filled' : ''}"></div>`
            ).join('');
            
            detailContent.innerHTML = `
               <div>
    ${recreo.destacado ? '<div class="featured-tag"><i class="fas fa-star"></i> Destacado</div>' : ''}
    
    <!-- IMAGEN ÚNICA PARA TODOS -->
    <div class="detail-image" id="main-image-container">
        <img src="data:image/jpeg;base64,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" 
             alt="Imagen del recreo" 
             id="main-image"
             style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
    </div>
                    <!-- INFORMACIÓN BÁSICA -->
                    <div class="info-grid">
                        <div class="info-card">
                            <h4><i class="fas fa-info-circle"></i> Descripción</h4>
                            <p>${recreo.descripcion}</p>
                        </div>
                        
                        <div class="info-card">
                            <h4><i class="far fa-clock"></i> Horario</h4>
                            <p>${recreo.horario}</p>
                        </div>
                    </div>
                    
                    <!-- SECCIÓN DE PRECIOS -->
                    <div class="price-section">
                        <h3 class="section-title"><i class="fas fa-tag"></i> Precios</h3>
                        <div class="price-amount">${formatearPrecio(recreo.precio_numero)}</div>
                        <div class="price-label">${obtenerEtiquetaPrecio(recreo.precio_numero)}</div>
                        <div class="price-indicator" style="margin-top: 1rem;">
                            ${priceDots}
                        </div>
                    </div>
                </div>
                <div>
                    <!-- INFORMACIÓN DE CONTACTO Y UBICACIÓN -->
                    <div class="contact-info">
                        <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Ubicación</h3>
                        <p class="detail-address">
                            <i class="fas fa-map-marker-alt"></i> ${recreo.direccion}
                        </p>
                        
                        <!-- REFERENCIA DEL LUGAR (DESPUÉS DE DIRECCIÓN) -->
                        <div class="reference-section" style="margin-top: 1rem;">
                            <h4><i class="fas fa-compass"></i> Referencia del Lugar</h4>
                            <p class="reference-text">${recreo.referencia}</p>
                        </div>
                        
                        <p class="detail-phone" style="margin-top: 1rem;">
                            <i class="fas fa-phone"></i> ${recreo.telefono}
                        </p>
                    </div>
                    
                    <!-- SERVICIOS -->
                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-concierge-bell"></i> Servicios</h3>
                        <div>
                            ${recreo.servicios.map(servicio => `
                                <div class="service-item">
                                    <i class="fas fa-check-circle service-icon"></i>
                                    <span>${servicio}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    
                    <!-- BOTONES DE ACCIÓN -->
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="window.open('tel:${recreo.telefono}')">
                            <i class="fas fa-phone"></i> Llamar ahora
                        </button>
                        <button class="btn btn-outline" onclick="window.open('${recreo.imagen && recreo.imagen.startsWith('http') ? recreo.imagen : 'https://maps.google.com'}', '_blank')">
                            <i class="fas fa-directions"></i> Cómo llegar
                        </button>
                    </div>
                </div>
            `;
        }
        
        // Inicializar con todos los recreos REALES
        displayResults(recreos);
        updateSearchStats();
        
        // Seleccionar el primer recreo automáticamente
        if (recreos.length > 0) {
            setTimeout(() => {
                showRecreoDetails(recreos[0]);
                document.querySelectorAll('.result-item')[0].classList.add('selected');
            }, 500);
        }
    </script>
</body>
</html>