<?php
$pageTitle = 'Buscar Recreos - RecreoHuanta';
include __DIR__ . '/../layouts/header_public.php';
?>

<div class="container-fluid bg-light min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-4 text-primary">
                        <i class="bi bi-search"></i> Buscar Recreos
                    </h1>
                    <p class="lead text-muted">
                        Encuentra los mejores recreos en Huanta
                    </p>
                </div>

                <!-- Formulario de Búsqueda -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-key"></i> Acceso con Token
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Token de Acceso</label>
                            <input type="hidden" class="form-control" id="accessToken" 
                                   placeholder="Ingrese su token de acceso" required value="f52d5d7e42f1c07b46dc41281c045a29c87b427621103cd8636cb00ddacbb28f_20251017_4">
                            <div class="form-text">
                                Obtenga su token en el panel de administración
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-search"></i> Buscar Recreos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">¿Qué estás buscando?</label>
                                <input type="text" class="form-control" id="searchTerm" 
                                       placeholder="Ej: Parque, piscina, restaurante...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Buscar por</label>
                                <select class="form-select" id="searchType">
                                    <option value="todo">Todo</option>
                                    <option value="nombre">Nombre</option>
                                    <option value="servicio">Servicio</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-success w-100" onclick="buscarRecreos()">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultados -->
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul"></i> Resultados
                        </h5>
                        <div>
                            <button class="btn btn-outline-light btn-sm me-2" onclick="listarTodos()">
                                <i class="bi bi-arrow-clockwise"></i> Ver Todos
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="copiarJSON()">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Loading -->
                        <div id="loading" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Buscando recreos...</p>
                        </div>

                        <!-- Mensajes de Error -->
                        <div id="errorMessage" class="alert alert-danger" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i> <span id="errorText"></span>
                        </div>

                        <!-- Resultados JSON -->
                        <pre id="jsonResults" class="bg-light border rounded p-3" 
                             style="min-height: 300px; max-height: 500px; overflow-y: auto; display: none;">
                        </pre>

                        <!-- Sin resultados -->
                        <div id="noResults" class="text-center py-4">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Realice una búsqueda para ver los resultados</p>
                        </div>
                    </div>
                </div>

                <!-- Información -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> ¿Cómo usar?
                        </h5>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Ingrese su token de acceso</li>
                            <li>Escriba lo que busca (nombre o servicio)</li>
                            <li>Seleccione el tipo de búsqueda</li>
                            <li>Haga clic en "Buscar"</li>
                        </ol>
                        <p class="mb-0 text-muted">
                            <small>Los resultados se muestran en formato JSON para fácil integración</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const BASE_URL = '<?php echo BASE_URL; ?>';

function mostrarCarga(mostrar) {
    document.getElementById('loading').style.display = mostrar ? 'block' : 'none';
}

function mostrarResultados(mostrar) {
    document.getElementById('jsonResults').style.display = mostrar ? 'block' : 'none';
}

function mostrarNoResultados(mostrar) {
    document.getElementById('noResults').style.display = mostrar ? 'block' : 'none';
}

function mostrarError(mensaje) {
    const errorDiv = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    errorText.textContent = mensaje;
    errorDiv.style.display = 'block';
}

function ocultarError() {
    document.getElementById('errorMessage').style.display = 'none';
}

function listarTodos() {
    const token = document.getElementById('accessToken').value.trim();
    
    if (!token) {
        mostrarError('Por favor, ingrese un token de acceso');
        return;
    }

    mostrarCarga(true);
    ocultarError();
    mostrarResultados(false);
    mostrarNoResultados(false);
    
    fetch(BASE_URL + 'clienteapi/listar', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        mostrarCarga(false);
        if (data.success) {
            document.getElementById('jsonResults').textContent = JSON.stringify(data, null, 2);
            mostrarResultados(true);
            mostrarNoResultados(false);
        } else {
            mostrarError(data.message);
            mostrarResultados(false);
            mostrarNoResultados(true);
        }
    })
    .catch(error => {
        mostrarCarga(false);
        mostrarError('Error de conexión: ' + error.message);
        console.error('Error:', error);
    });
}

function buscarRecreos() {
    const token = document.getElementById('accessToken').value.trim();
    const termino = document.getElementById('searchTerm').value.trim();
    const tipo = document.getElementById('searchType').value;
    
    if (!token) {
        mostrarError('Por favor, ingrese un token de acceso');
        return;
    }

    if (!termino) {
        mostrarError('Por favor, ingrese un término de búsqueda');
        return;
    }

    mostrarCarga(true);
    ocultarError();
    mostrarResultados(false);
    mostrarNoResultados(false);
    
    const url = BASE_URL + `clienteapi/buscar?q=${encodeURIComponent(termino)}&tipo=${tipo}`;
    
    fetch(url, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        mostrarCarga(false);
        if (data.success) {
            document.getElementById('jsonResults').textContent = JSON.stringify(data, null, 2);
            mostrarResultados(true);
            mostrarNoResultados(false);
        } else {
            mostrarError(data.message);
            mostrarResultados(false);
            mostrarNoResultados(true);
        }
    })
    .catch(error => {
        mostrarCarga(false);
        mostrarError('Error de conexión: ' + error.message);
        console.error('Error:', error);
    });
}

function copiarJSON() {
    const jsonText = document.getElementById('jsonResults').textContent;
    if (!jsonText) {
        alert('No hay resultados para copiar');
        return;
    }
    
    navigator.clipboard.writeText(jsonText)
        .then(() => alert('✅ JSON copiado al portapapeles'))
        .catch(err => alert('❌ Error al copiar: ' + err));
}

// Enter para buscar
document.getElementById('searchTerm').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarRecreos();
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer_public.php'; ?>