        <!-- Modern Footer -->
    <footer class="footer-modern">
        <div class="container">
            <!-- Footer Content -->
            <div class="footer-content">
                <!-- Company Section -->
                <div class="footer-section company-section">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">🏆</div>
                        <div class="footer-logo-text">
                            <span class="footer-logo-main">EliteMotors</span>
                            <span class="footer-logo-sub">Premium Collection</span>
                        </div>
                    </div>
                    <p class="company-description">
                        Excelência em cada detalhe, luxo em cada quilômetro. 
                        Sua concessionária premium de veículos de alto padrão.
                    </p>
                    <div class="company-badges">
                        <span class="badge premium">Premium</span>
                        <span class="badge quality">Qualidade</span>
                        <span class="badge trust">Confiança</span>
                    </div>
                </div>

                <!-- Navigation Section -->
                <div class="footer-section">
                    <h4 class="footer-title">Navegação</h4>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Início</a></li>
                        <li><a href="<?= BASE_URL ?>car"><i class="fas fa-car"></i> Catálogo</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
                                <li><a href="<?= BASE_URL ?>dashboard"><i class="fas fa-crown"></i> Admin</a></li>
                                <li><a href="<?= BASE_URL ?>user"><i class="fas fa-users"></i> Usuários</a></li>
                            <?php else: ?>
                                <li><a href="<?= BASE_URL ?>dashboard/user"><i class="fas fa-user"></i> Dashboard</a></li>
                                <li><a href="<?= BASE_URL ?>favorite"><i class="fas fa-heart"></i> Favoritos</a></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li><a href="<?= BASE_URL ?>auth"><i class="fas fa-user-plus"></i> Entre na Elite</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="footer-section">
                    <h4 class="footer-title">Contato Premium</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>elite@elitemotors.com.br</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>(11) 3456-7890</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Av. Paulista, 1000<br>São Paulo - SP</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Seg-Sex: 8h-18h<br>Sáb: 8h-16h</span>
                        </div>
                    </div>
                </div>

                <!-- Social Section -->
                <div class="footer-section">
                    <h4 class="footer-title">Conecte-se</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/elitemotors" target="_blank" class="social-link facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/5511987654321" target="_blank" class="social-link whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.instagram.com/elitemotors" target="_blank" class="social-link instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/elitemotors" target="_blank" class="social-link linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <div class="newsletter">
                        <h5>Newsletter Elite</h5>
                        <p>Receba ofertas exclusivas</p>
                        <div class="newsletter-form">
                            <input type="email" placeholder="seu@email.com">
                            <button type="button"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        <p>&copy; <?= date('Y') ?> EliteMotors. Todos os direitos reservados.</p>
                        <p class="legal-text">CNPJ: 12.345.678/0001-90 | Desenvolvido com excelência</p>
                    </div>
                    <div class="footer-badges">
                        <span class="security-badge">🔒 Site Seguro</span>
                        <span class="quality-badge">⭐ Qualidade Premium</span>
                    </div>
                </div>
            </div>
        </div>    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" style="position: fixed; bottom: 30px; right: 30px; background: #3498db; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer; opacity: 0; transition: all 0.3s ease; z-index: 1000; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);" onclick="scrollToTop()">
        <i class="fas fa-arrow-up" style="font-size: 1.2rem;"></i>
    </button>

    <script>
        // Scroll to top functionality
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.pageYOffset > 300) {
                scrollBtn.style.opacity = '1';
            } else {
                scrollBtn.style.opacity = '0';
            }
        });

        // Footer links hover effect
        document.querySelectorAll('.footer-section a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.color = '#3498db';
            });
            link.addEventListener('mouseleave', function() {
                this.style.color = '#ecf0f1';
            });
        });

        // Social media icons hover effect
        document.querySelectorAll('.footer-section a[href="#"]').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });        });
    </script>

    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
