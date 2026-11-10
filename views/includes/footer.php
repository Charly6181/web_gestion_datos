</main> <!-- Cierre del main-content -->
</div> <!-- Cierre del dashboard-container -->
</div> <!-- Cierre del dashboard-wrapper -->

<!-- Contenedor exclusivo de olas fluidas en el fondo (sin elementos dentro) -->
<div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; z-index: 1; pointer-events: none; opacity: 0.4;">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(100% + 1.3px); height: 90px;">
        <path d="M0,0 C150,90 350,-40 500,50 C650,140 900,10 1200,40 L1200,120 L0,120 Z" fill="#00b4d8"></path>
    </svg>
</div>

<footer class="app-footer text-center py-3 w-100" style="position: relative; z-index: 5;">
    <span>&copy; FOOTER</span>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Scripts adicionales específicos si los hubiera -->
<?php if (isset($extraJs)) echo $extraJs; ?>
</body>

</html>