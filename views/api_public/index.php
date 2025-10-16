<?php
$pageTitle = 'API Recreos Huanta - Consulta Pública';
include __DIR__ . '/../layouts/header_public.php';
?>

<div class="container-fluid bg-light min-vh-100">
    <div class="container py-5">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-tree-fill"></i> API Recreos Huanta
                </h1>
                <p class="lead text-muted">
                    Consulta pública de todos los recreos disponibles en Huanta
                </p>
            </div>
        </div>

        <!-- Panel de Búsqueda -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-search me-2"></i>Buscar Recreos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="searchTerm" class="form-label">Término de búsqueda</label>
                                <input type="text" class="form-control" id="searchTerm" 
                                       placeholder="Ej: Parque, piscina, Libertad...">
                            </div>
                            <div class="col-md-4">
                                <label for="searchType" class="form-label">Tipo de búsqueda</label>
                                <select class="form-select" id="searchType">
                                    <option value="todo">Todo</option>
                                    <option value="nombre">Nombre</option>
                                    <option value="servicio">Servicio</option>
                                    <option value="direccion">Dirección</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" onclick="buscarRecreos()">
                                    <i class="bi bi-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>Resultados de la Búsqueda
                        </h5>
                        <div>
                            <button class="btn btn-outline-light btn-sm me-2" onclick="listarTodos()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Ver Todos
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="copiarJSON()">
                                <i class="bi bi-clipboard me-1"></i>Copiar JSON
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loading" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Buscando recreos...</p>
                        </div>
                        
                        <div id="resultsInfo" class="alert alert-info" style="display: none;">
                            <strong id="resultsCount">0</strong> resultados encontrados
                        </div>
                        
                        <pre id="jsonResults" class="bg-dark text-light p-3 rounded" 
                             style="max-height: 600px; overflow-y: auto; display: none;">
                        </pre>
                        
                        <div id="noResults" class="text-center py-4" style="display: none;">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">No se encontraron resultados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la API -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Endpoints Disponibles</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>GET /clienteapi/listar</strong>
                                <br><small class="text-muted">Lista todos los recreos</small>
                            </li>
                            <li class="mb-2">
                                <strong>GET /clienteapi/buscar?q=term&tipo=nombre</strong>
                                <br><small class="text-muted">Busca recreos por término</small>
                            </li>
                            <li class="mb-2">
                                <strong>GET /clienteapi/ver/ID</strong>
                                <br><small class="text-muted">Obtiene un recreo específico</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-code-slash me-2"></i>Ejemplo de Uso</h6>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-2 rounded small"><code>// Ejemplo en JavaScript
fetch('/clienteapi/buscar?q=parque&tipo=nombre')
  .then(response => response.json())
  .then(data => console.log(data));</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarCarga(mostrar) {
    document.getElementById('loading').style.display = mostrar ? 'block' : 'none';
}

function mostrarResultados(mostrar) {
    document.getElementById('jsonResults').style.display = mostrar ? 'block' : 'none';
}

function mostrarNoResultados(mostrar) {
    document.getElementById('noResults').style.display = mostrar ? 'block' : 'none';
}

function mostrarInfoResultados(cantidad) {
    const info = document.getElementById('resultsInfo');
    const count = document.getElementById('resultsCount');
    count.textContent = cantidad;
    info.style.display = cantidad > 0 ? 'block' : 'none';
}

function listarTodos() {
    mostrarCarga(true);
    mostrarResultados(false);
    mostrarNoResultados(false);
    
    fetch('<?php echo BASE_URL; ?>index.php?action=api_public&method=listarRecreos')
        .then(response => response.json())
        .then(data => {
            mostrarCarga(false);
            if (data.success && data.data.length > 0) {
                document.getElementById('jsonResults').textContent = JSON.stringify(data, null, 2);
                mostrarResultados(true);
                mostrarInfoResultados(data.total);
                mostrarNoResultados(false);
            } else {
                mostrarResultados(false);
                mostrarNoResultados(true);
                mostrarInfoResultados(0);
            }
        })
        .catch(error => {
            mostrarCarga(false);
            console.error('Error:', error);
            alert('Error al cargar los recreos');
        });
}

function buscarRecreos() {
    const termino = document.getElementById('searchTerm').value.trim();
    const tipo = document.getElementById('searchType').value;
    
    if (!termino) {
        alert('Por favor, ingrese un término de búsqueda');
        return;
    }
    
    mostrarCarga(true);
    mostrarResultados(false);
    mostrarNoResultados(false);
    
    const url = `<?php echo BASE_URL; ?>index.php?action=api_public&method=buscarRecreos&q=${encodeURIComponent(termino)}&tipo=${tipo}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            mostrarCarga(false);
            if (data.success && data.data.length > 0) {
                document.getElementById('jsonResults').textContent = JSON.stringify(data, null, 2);
                mostrarResultados(true);
                mostrarInfoResultados(data.total);
                mostrarNoResultados(false);
            } else {
                mostrarResultados(false);
                mostrarNoResultados(true);
                mostrarInfoResultados(0);
            }
        })
        .catch(error => {
            mostrarCarga(false);
            console.error('Error:', error);
            alert('Error en la búsqueda');
        });
}

function copiarJSON() {
    const jsonText = document.getElementById('jsonResults').textContent;
    if (!jsonText) {
        alert('No hay JSON para copiar');
        return;
    }
    
    navigator.clipboard.writeText(jsonText)
        .then(() => alert('JSON copiado al portapapeles'))
        .catch(err => alert('Error al copiar: ' + err));
}

// Cargar todos los recreos al iniciar
document.addEventListener('DOMContentLoaded', function() {
    listarTodos();
});
</script>

<?php include __DIR__ . '/../layouts/footer_public.php'; ?>