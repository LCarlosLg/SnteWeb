<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('images/icono.jpg') ?>" type="image/x-icon">
    <style>
        .search-bar {
            max-width: 500px;
        }
        .category-btn.active {
            background-color: #ff6f00;
            color: white;
        }
        .product-img-placeholder {
            width: 100%;
            height: 200px;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 1.2rem;
        }
    </style>
</head>
<body style="background-color: #f5f5f5;">

    <!-- Barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="<?= base_url('images/perfil.png') ?>" alt="Perfil" width="32" height="32" class="rounded-circle">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="topNav">
                <form class="d-flex mx-auto" style="max-width: 500px;">
                    <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar producto..." aria-label="Buscar">
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a class="btn btn-outline-secondary" href="https://maps.google.com/?q=27.920556,-110.891556" target="_blank" title="Ubicación">📍</a>
                    </li>
                    <li class="nav-item me-3">
                        <button class="btn btn-outline-primary position-relative" data-bs-toggle="modal" data-bs-target="#carritoModal">
                            🛒
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="carrito-count">0</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">

        <!-- Carrusel de imágenes -->
        <div id="carruselProductos" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $imagenes = [
                    ['archivo' => 'cama.jpg', 'texto' => '¡Duerme como rey! Encuentra tu cama ideal aquí.'],
                    ['archivo' => 'cocina.jpg', 'texto' => 'Moderniza tu cocina con electrodomésticos de alta calidad.'],
                    ['archivo' => 'estante.jpg', 'texto' => 'Organiza tu hogar con estilo — estantes en oferta.'],
                    ['archivo' => 'lavadora.jpg', 'texto' => '¡Ahorra tiempo! Lavadoras con tecnología inteligente.'],
                    ['archivo' => 'refrigerador.jpg', 'texto' => 'Mantén tus alimentos frescos, descubre nuestras ofertas.'],
                    ['archivo' => 'Sofa.jpg', 'texto' => 'Relájate en grande: Sofás cómodos a precios increíbles.']
                ];

                foreach ($imagenes as $index => $img): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="<?= base_url('images/' . $img['archivo']) ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;" alt="Producto">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                            <h5><?= $img['texto'] ?></h5>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carruselProductos" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carruselProductos" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <!-- Filtros -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <input type="text" id="searchInput" class="form-control search-bar mb-2 mb-md-0" placeholder="Buscar producto...">
            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary category-btn active" data-category="todos">Todos</button>
                <button class="btn btn-outline-primary category-btn" data-category="mueble">Muebles</button>
                <button class="btn btn-outline-primary category-btn" data-category="electrodomestico">Electrodomésticos</button>
            </div>
        </div>

        <!-- Lista de productos -->
        <div class="row" id="productList">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-4 col-sm-6 mb-4 producto-card" 
                        data-nombre="<?= strtolower($producto['nombre']) ?>" 
                        data-categoria="<?= strtolower($producto['categoria']) ?>">
                        <div class="card h-100 shadow-sm">
                            <div class="product-img-placeholder">Sin imagen</div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p class="card-text">Categoría: <?= ucfirst($producto['categoria']) ?></p>
                                <p class="card-text">Stock: <?= $producto['stock'] ?></p>
                                <p class="card-text text-success fw-bold">$<?= number_format($producto['precio'], 2) ?></p>
                                <a href="#" class="btn btn-primary mt-auto">Comprar ahora</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay productos disponibles.</p>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal del Carrito -->
    <div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tu carrito de compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="carrito-lista">
                        <p class="text-muted">No hay productos en el carrito.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url('carrito/ver') ?>" class="btn btn-primary">Ver carrito completo</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchInput = document.getElementById('searchInput');
        const productCards = document.querySelectorAll('.producto-card');
        const categoryButtons = document.querySelectorAll('.category-btn');
        const carrito = [];
        const carritoCount = document.getElementById('carrito-count');
        const carritoLista = document.getElementById('carrito-lista');

        let currentCategory = 'todos';

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            productCards.forEach(card => {
                const nombre = card.dataset.nombre;
                const categoria = card.dataset.categoria;
                const matchesSearch = nombre.includes(searchTerm);
                const matchesCategory = currentCategory === 'todos' || categoria === currentCategory;
                card.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
            });
        }

        searchInput.addEventListener('input', filterProducts);
        categoryButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                categoryButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentCategory = btn.dataset.category;
                filterProducts();
            });
        });

        // Agregar al carrito
        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const card = btn.closest('.producto-card');
                const nombre = card.querySelector('.card-title').textContent;
                const precio = card.querySelector('.text-success').textContent;
                carrito.push({ nombre, precio });
                actualizarCarritoUI();
            });
        });

        function actualizarCarritoUI() {
            carritoCount.textContent = carrito.length;
            if (carrito.length === 0) {
                carritoLista.innerHTML = '<p class="text-muted">No hay productos en el carrito.</p>';
                return;
            }

            carritoLista.innerHTML = '<ul class="list-group">';
            carrito.forEach(item => {
                carritoLista.innerHTML += `<li class="list-group-item d-flex justify-content-between">
                    <span>${item.nombre}</span><span>${item.precio}</span>
                </li>`;
            });
            carritoLista.innerHTML += '</ul>';
        }
    </script>
</body>
</html>
