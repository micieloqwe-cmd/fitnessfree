<?php // ── FOOTER PARTIAL ── ?>
</div><!-- .page-wrapper -->

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-logo">
            <span class="logo-icon">⬡</span>
            <span class="logo-text">ELEV<em>8</em></span>
        </div>
        <p class="footer-tagline">Elevate your body. Elevate your life.</p>
        <p class="footer-copy">&copy; <?= date('Y') ?> ELEV8 Fitness. All rights reserved.</p>
    </div>
</footer>

<script src="<?= APP_URL ?>/assets/js/main.js"></script>
<?= $extraScript ?? '' ?>
</body>
</html>
