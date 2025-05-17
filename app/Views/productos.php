<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="icon" href="<?= base_url('images/icono.jpg') ?>" type="image/x-icon" />
    <style>
        body { background-color: #f5f5f5; }

        .navbar {
            background: linear-gradient(90deg, #ff6f00, #d32f2f, #fdd835, #6d4c41);
            background-size: 300% 300%;
            animation: gradientShift 10s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .navbar * { color: white !important; }

        .search-bar input.form-control {
            background-color: white !important;
            color: black !important;
            border: 1px solid #ccc;
        }

        .category-btn {
            background-color: white !important;
            color: #6d4c41 !important;
            border: 1px solid #6d4c41;
            cursor: pointer;
        }
        .category-btn.active,
        .category-btn:hover {
            background-color: #6d4c41 !important;
            color: white !important;
        }

        .btn-carrito {
            background-color: #ff6f00;
            border-color: #ff6f00;
            color: white;
        }
        .btn-carrito:hover {
            background-color: #e65100;
            border-color: #e65100;
        }

        .btn-cerrar-sesion {
            background-color: #d32f2f;
            border-color: #d32f2f;
            color: white;
        }
        .btn-cerrar-sesion:hover {
            background-color: #b71c1c;
            border-color: #b71c1c;
        }

        .btn-comprar {
            background-color: #fdd835;
            border-color: #fdd835;
            color: #000;
            cursor: pointer;
        }
        .btn-comprar:hover {
            background-color: #fbc02d;
            border-color: #fbc02d;
            color: #000;
        }

        .mensaje-bienvenida {
            color: #ff6f00;
            background-color: #fff3e0;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 95%;
            text-align: center;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .animate-entry {
            opacity: 0;
            animation: fadeSlideIn 1.5s ease-out forwards;
        }
        @keyframes fadeSlideIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Barra superior -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top">
    <div class="container-fluid">
        <span class="navbar-text me-2 d-flex align-items-center">
            <img src="<?= base_url('images/perfil.png') ?>" alt="Perfil" width="32" height="32" class="rounded-circle me-2" />
            <span class="d-none d-sm-inline"><?= session('email') ?></span>
        </span>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse flex-wrap" id="topNav">
            <form class="d-flex mx-auto search-bar my-2 my-lg-0 px-2" style="max-width: 300px;">
                <input id="searchInput" class="form-control" type="search" placeholder="Buscar producto..." aria-label="Buscar" />
            </form>

            <div class="btn-group mx-auto my-2" role="group" aria-label="Filtro categorías">
                <button class="btn category-btn active" data-category="todos">Todos</button>
                <button class="btn category-btn" data-category="mueble">Muebles</button>
                <button class="btn category-btn" data-category="electrodomestico">Electrodomésticos</button>
            </div>

            <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light" href="https://maps.google.com/?q=27.920556,-110.891556" target="_blank" title="Ubicación">
                        📍
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light" href="https://www.facebook.com/share/1Ad9kJxPyp/?mibextid=wwXIfr" target="_blank" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light" href="https://www.instagram.com/seccion54snte?igsh=MXFjbWh1OGdsNHZ2aw==" target="_blank" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light" href="https://youtube.com/@sntenacional?si=iFx9Z4y3rtyL8FqL" target="_blank" title="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <button class="btn btn-carrito position-relative" data-bs-toggle="modal" data-bs-target="#carritoModal" aria-label="Abrir carrito de compras">
                        🛒
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="carrito-count">0</span>
                    </button>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/logout') ?>" class="btn btn-cerrar-sesion">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mensaje de bienvenida -->
<div class="mensaje-bienvenida animate-entry">
    Diseño, confort y calidad para tu hogar — descubre lo mejor en muebles y electrodomésticos.
</div>

<!-- Carrusel -->
<div class="container py-4">
    <div id="carruselProductos" class="carousel slide mb-4" data-bs-ride="carousel">
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
                    <img src="<?= base_url('images/' . $img['archivo']) ?>" class="d-block w-100 rounded" style="height: 250px; object-fit: cover;" alt="Producto">
                    <div class="carousel-caption bg-dark bg-opacity-50 rounded p-2">
                        <h5><?= esc($img['texto']) ?></h5>
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

   <!-- Productos -->
<div class="row" id="productList">
    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 producto-card"
                 data-nombre="<?= strtolower($producto['nombre']) ?>"
                 data-categoria="<?= strtolower($producto['categoria']) ?>"
                 data-id="<?= $producto['id'] ?? $producto['nombre'] ?>">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="<?= base_url('uploads/' . $producto['imagen']) ?>" alt="Imagen de <?= esc($producto['nombre']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="product-img-placeholder">Sin imagen</div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= esc($producto['nombre']) ?></h5>
                        <p class="card-text">Categoría: <?= ucfirst(esc($producto['categoria'])) ?></p>
                        <p class="card-text">Stock: <?= intval($producto['stock']) ?></p>
                        <p class="card-text text-success fw-bold precio-producto">$<?= number_format($producto['precio'], 2) ?></p>
                        <a href="#" class="btn btn-comprar mt-auto" role="button" aria-label="Agregar <?= esc($producto['nombre']) ?> al carrito">Agregar al carrito</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</div>

<!-- Modal carrito -->
<div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="carritoModalLabel">Tu carrito de compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="carritoItems">
        <!-- Productos agregados aquí -->
        <p id="carrito-vacio" class="text-center text-muted">Tu carrito está vacío.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="vaciarCarrito" class="btn btn-outline-danger">Vaciar carrito</button>
        <button type="button" id="finalizarCompra" class="btn btn-primary" disabled>Finalizar compra</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Filtrado por categorías y búsqueda
    const productList = document.getElementById('productList');
    const searchInput = document.getElementById('searchInput');
    const categoryButtons = document.querySelectorAll('.category-btn');

    function filtrarProductos() {
        const textoBusqueda = searchInput.value.trim().toLowerCase();
        const categoriaSeleccionada = Array.from(categoryButtons).find(btn => btn.classList.contains('active')).dataset.category;

        const productos = productList.querySelectorAll('.producto-card');
        productos.forEach(prod => {
            const nombre = prod.dataset.nombre;
            const categoria = prod.dataset.categoria;
            const coincideBusqueda = nombre.includes(textoBusqueda);
            const coincideCategoria = (categoriaSeleccionada === 'todos' || categoria === categoriaSeleccionada);

            if (coincideBusqueda && coincideCategoria) {
                prod.style.display = '';
            } else {
                prod.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filtrarProductos);

    categoryButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            categoryButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrarProductos();
        });
    });

    // Carrito de compras en memoria
    let carrito = [];

    const carritoItems = document.getElementById('carritoItems');
    const carritoCount = document.getElementById('carrito-count');
    const carritoVacio = document.getElementById('carrito-vacio');
    const finalizarCompraBtn = document.getElementById('finalizarCompra');
    const vaciarCarritoBtn = document.getElementById('vaciarCarrito');

    function actualizarCarritoUI() {
        carritoItems.innerHTML = '';
        if (carrito.length === 0) {
            carritoVacio.style.display = 'block';
            finalizarCompraBtn.disabled = true;
            carritoCount.textContent = '0';
            return;
        }
        carritoVacio.style.display = 'none';
        finalizarCompraBtn.disabled = false;
        carritoCount.textContent = carrito.reduce((acc, p) => acc + p.cantidad, 0);

        carrito.forEach(producto => {
            const div = document.createElement('div');
            div.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2', 'border-bottom', 'pb-2');

            div.innerHTML = `
                <div>
                    <h6 class="mb-0">${producto.nombre}</h6>
                    <small>$${producto.precio.toFixed(2)} x ${producto.cantidad}</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${producto.id}" aria-label="Eliminar ${producto.nombre} del carrito">Eliminar</button>
                </div>
            `;
            carritoItems.appendChild(div);
        });

        // Añadir event listeners a los botones eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', e => {
                const id = e.target.dataset.id;
                carrito = carrito.filter(p => p.id !== id);
                actualizarCarritoUI();
            });
        });
    }

    function agregarAlCarrito(producto) {
        const index = carrito.findIndex(p => p.id === producto.id);
        if (index !== -1) {
            carrito[index].cantidad++;
        } else {
            producto.cantidad = 1;
            carrito.push(producto);
        }
        actualizarCarritoUI();
    }

    productList.addEventListener('click', e => {
        if (e.target.classList.contains('btn-comprar')) {
            e.preventDefault();
            const card = e.target.closest('.producto-card');
            if (!card) return;
            const producto = {
                id: card.dataset.id,
                nombre: card.dataset.nombre.charAt(0).toUpperCase() + card.dataset.nombre.slice(1),
                precio: parseFloat(
                    card.querySelector('.precio-producto').textContent
                    .replace(/[^0-9.,]/g, '')
                    .replace(',', '.')
                )
            };
            agregarAlCarrito(producto);
        }
    });

    vaciarCarritoBtn.addEventListener('click', () => {
        if(confirm('¿Quieres vaciar el carrito?')) {
            carrito = [];
            actualizarCarritoUI();
        }
    });

    finalizarCompraBtn.addEventListener('click', () => {
        alert('Funcionalidad de finalizar compra aún no implementada.');
    });

    // Inicializar UI carrito vacío
    actualizarCarritoUI();
</script>
</body>
</html>
