<!-- Menú lateral -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>Menú</h2>
    </div>

    <nav class="sidebar-menu">
        <a href="vistadashboardusuario.php" class="menu-item <?= ($pageActive === 'inicio') ? 'active' : '' ?>">
            <span class="menu-text">Inicio</span>
        </a>

        <!-- Opción cliente desplegable -->
        <div class="menu-dropdown">
            <a class="menu-item dropdown-toggle d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#clienteSubmenu" role="button" aria-expanded="false" aria-controls="clienteSubmenu">
                <span class="menu-text">Cliente</span>
            </a>
            <div class="collapse submenu-container" id="clienteSubmenu">
                <a href="vistaaltacliente.php" class="submenu-item">Alta Cliente</a>
                <a href="vistacliente.php" class="submenu-item">Vista Cliente</a>
                <a href="vistadepositocorte.php" class="submenu-item">Depósito Corte</a>
            </div>
        </div>
    </nav>
</aside>