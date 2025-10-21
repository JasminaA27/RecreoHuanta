<?php
// Incluir modelos para usar datos reales
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Recreo.php';
require_once __DIR__ . '/../models/TokenApi.php';

// Verificar token con la base de datos real
$token = $_GET['token'] ?? '';

if (empty($token)) {
    mostrarErrorToken('Token requerido');
    exit;
}

// Validar token en la base de datos
$tokenModel = new TokenApi();
$tokenData = $tokenModel->getByToken($token);

if (!$tokenData || !$tokenData['estado']) {
    mostrarErrorToken('Token inválido o expirado');
    exit;
}

// Obtener recreos reales de la base de datos
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
        'imagen' => $recreoReal['url_maps'] ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
        'destacado' => false // Puedes cambiar esto según tu lógica
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
            <p>Usa: http://localhost:8888/RecreoHuanta/recreo-view?token=tu_token_valido</p>
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
        /* TODO EL CSS QUE TE ENVIASTE ANTES - NO CAMBIA */
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
        }
        
        input[type="text"] {
            padding: 16px 20px 16px 50px;
            border: 2px solid #ddd;
            border-radius: 50px;
            font-size: 16px;
            width: 100%;
            transition: var(--transition);
            outline: none;
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
    </style>
</head>
<body>
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
                </div>
                <div class="suggestions" id="suggestions">
                    <!-- Las sugerencias se cargarán aquí -->
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

        // Elementos DOM
        const searchInput = document.getElementById('search-input');
        const suggestionsBox = document.getElementById('suggestions');
        const resultsList = document.getElementById('results-list');
        const detailContent = document.getElementById('detail-content');
        const noSelection = document.getElementById('no-selection');
        
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            // Limpiar sugerencias si no hay término
            if (searchTerm.length === 0) {
                suggestionsBox.style.display = 'none';
                displayResults(recreos);
                return;
            }
            
            // Filtrar recreos por NOMBRE y SERVICIO
            const filteredRecreos = recreos.filter(recreo => 
                recreo.nombre.toLowerCase().includes(searchTerm) ||
                recreo.descripcion.toLowerCase().includes(searchTerm) ||
                recreo.servicios.some(servicio => 
                    servicio.toLowerCase().includes(searchTerm)
                )
            );
            
            // Mostrar sugerencias
            showSuggestions(filteredRecreos);
            
            // Mostrar resultados
            displayResults(filteredRecreos);
        });
        
        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
        
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
                    displayResults([recreo]);
                    showRecreoDetails(recreo);
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
                    <div class="detail-image">
                        ${recreo.imagen && recreo.imagen.startsWith('http') ? 
                            `<img src="${recreo.imagen}" alt="${recreo.nombre}" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">` :
                            `<i class="fas fa-image" style="font-size: 3rem;"></i><div>Imagen del lugar</div>`
                        }
                    </div>
                    <h3 class="section-title">Descripción</h3>
                    <p>${recreo.descripcion}</p>
                    
                    <h3 class="section-title">Horario de atención</h3>
                    <p><i class="far fa-clock"></i> ${recreo.horario}</p>
                    
                    <h3 class="section-title">Nivel de precios</h3>
                    <div class="price-indicator">
                        ${priceDots}
                    </div>
                </div>
                <div>
                    <h3 class="section-title">Información de contacto</h3>
                    <p class="detail-address">
                        <i class="fas fa-map-marker-alt"></i> ${recreo.direccion}
                    </p>
                    <p class="detail-phone">
                        <i class="fas fa-phone"></i> ${recreo.telefono}
                    </p>
                    
                    <h3 class="section-title">Servicios</h3>
                    <div>
                        ${recreo.servicios.map(servicio => `
                            <div class="service-item">
                                <i class="fas fa-check-circle service-icon"></i>
                                <span>${servicio}</span>
                            </div>
                        `).join('')}
                    </div>
                    
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