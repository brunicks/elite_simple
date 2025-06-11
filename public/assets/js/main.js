// script.js - JavaScript para interatividade

// Aguardar carregamento do DOM
document.addEventListener('DOMContentLoaded', function() {
    initializeComponents();
});

// Inicializar componentes
function initializeComponents() {
    initAuthTabs();
    initPriceFormatting();
    initCurrencyMasks(); // Inicializar máscaras de moeda
    initImagePreview();
    initConfirmDialogs();
    initFormValidation();
    autoHideMessages();
    checkSessionMessages(); // Nova função para verificar mensagens da sessão
    initCollapsibleFilters(); // Initialize collapsible filters
}

// Sistema de abas para autenticação
function initAuthTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.textContent.toLowerCase().includes('login') ? 'login' : 'register';
            showTab(targetTab);
        });
    });
}

function showTab(tabName) {
    // Remover classe active de todos os botões e conteúdos
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Adicionar classe active ao botão e conteúdo corretos
    const activeButton = document.querySelector(`.tab-btn:nth-child(${tabName === 'login' ? '1' : '2'})`);
    const activeContent = document.getElementById(`${tabName}-tab`);
    
    if (activeButton) activeButton.classList.add('active');
    if (activeContent) activeContent.classList.add('active');
}

// Formatação automática de preço
function initPriceFormatting() {
    const priceInputs = document.querySelectorAll('input[name="preco"]');
    
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatPrice(this);
        });
        
        input.addEventListener('blur', function() {
            validatePrice(this);
        });
    });
}

function formatPrice(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    
    if (value.length > 0) {
        value = value.padStart(3, '0'); // Garantir pelo menos 3 dígitos
        value = value.replace(/(\d)(\d{2})$/, '$1,$2'); // Adicionar vírgula para centavos
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'); // Adicionar pontos para milhares
    }
    
    input.value = value;
}

function validatePrice(input) {
    const value = parseFloat(input.value.replace(/\./g, '').replace(',', '.'));
    
    if (isNaN(value) || value <= 0) {
        showFieldError(input, 'Preço deve ser um valor válido maior que zero');
    } else {
        clearFieldError(input);
    }
}

// Preview de imagem
function initImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            previewImage(this);
        });
    });
}

function previewImage(input) {
    const file = input.files[0];
    
    if (file) {
        // Validar tipo de arquivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        
        if (!allowedTypes.includes(file.type)) {
            showFieldError(input, 'Formato de arquivo inválido. Use JPG, PNG ou GIF.');
            input.value = '';
            return;
        }
        
        // Validar tamanho (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showFieldError(input, 'Arquivo muito grande. Máximo 5MB.');
            input.value = '';
            return;
        }
        
        clearFieldError(input);
        
        // Criar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            createImagePreview(input, e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function createImagePreview(input, imageSrc) {
    // Remover preview anterior se existir
    const existingPreview = input.parentNode.querySelector('.image-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    // Criar novo preview
    const preview = document.createElement('div');
    preview.className = 'image-preview';
    preview.innerHTML = `
        <p>Preview da imagem:</p>
        <img src="${imageSrc}" alt="Preview" style="max-width: 150px; height: auto; border-radius: 6px; border: 2px solid #ecf0f1;">
        <button type="button" onclick="removeImagePreview(this)" style="margin-left: 10px; padding: 5px 10px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Remover</button>
    `;
    
    input.parentNode.appendChild(preview);
}

function removeImagePreview(button) {
    const preview = button.closest('.image-preview');
    const input = preview.parentNode.querySelector('input[type="file"]');
    
    preview.remove();
    input.value = '';
}

// Confirmação para ações destrutivas
function initConfirmDialogs() {
    // Não precisa fazer nada aqui, a função deleteCar já implementa a confirmação
}

// Função para deletar carro com confirmação (soft delete)
function deleteCar(carId) {
    if (confirm('Tem certeza que deseja desativar este carro? Ele não aparecerá mais no catálogo.')) {
        // Usar AJAX POST em vez de GET
        fetch(window.BASE_URL + 'car/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + carId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao processar a solicitação');
        });
    }
}

function hardDeleteCar(carId) {
    if (confirm('ATENÇÃO: Esta ação é irreversível! Tem certeza que deseja deletar permanentemente este carro?')) {
        if (confirm('ÚLTIMA CONFIRMAÇÃO: O carro será deletado para sempre. Continuar?')) {
            // Usar AJAX POST em vez de GET
            fetch(window.BASE_URL + 'car/hardDelete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + carId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao processar a solicitação');
            });
        }
    }
}

function deleteUser(userId) {
    if (confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) {
        window.location.href = window.BASE_URL + 'user/delete/' + userId;
    }
}

// Função para reativar carro
function reactivateCar(carId) {
    if (confirm('Tem certeza que deseja reativar este carro?')) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        // Mostrar loading
        button.disabled = true;
        button.innerHTML = '⏳ Reativando...';
        
        // Fazer requisição AJAX
        const baseUrl = window.BASE_URL || '/';
        fetch(baseUrl + 'car/reactivate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${carId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recarregar a página para mostrar o carro reativado
                showSuccessModal(data.message, () => {
                    window.location.reload();
                });
            } else {
                showErrorModal(data.message || 'Erro ao reativar carro');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showErrorModal('Erro de conexão');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
}

// Função para deletar carro permanentemente
function hardDeleteCar(carId) {
    if (confirm('⚠️ ATENÇÃO: Esta ação irá deletar o carro PERMANENTEMENTE do banco de dados.\n\nEsta ação NÃO PODE ser desfeita. Tem certeza absoluta?')) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        // Mostrar loading
        button.disabled = true;
        button.innerHTML = '⏳ Deletando...';
        
        // Fazer requisição AJAX
        const baseUrl = window.BASE_URL || '/';
        fetch(baseUrl + 'car/hardDelete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${carId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remover card do carro com animação
                const carCard = button.closest('.car-card');
                carCard.style.transform = 'scale(0.8)';
                carCard.style.opacity = '0';
                
                setTimeout(() => {
                    carCard.remove();
                    showSuccessModal('Carro deletado permanentemente!');
                }, 300);
            } else {
                showErrorModal(data.message || 'Erro ao deletar carro');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showErrorModal('Erro de conexão');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
}

// Validação de formulários
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
        
        // Validação em tempo real
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('input[required], select[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    // Validação de campo obrigatório
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'Este campo é obrigatório';
    }
    
    // Validações específicas por tipo
    if (value && field.type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Email inválido';
        }
    }
    
    if (value && field.type === 'number') {
        const numValue = parseFloat(value);
        if (isNaN(numValue)) {
            isValid = false;
            errorMessage = 'Valor numérico inválido';
        } else if (field.hasAttribute('min') && numValue < parseFloat(field.getAttribute('min'))) {
            isValid = false;
            errorMessage = `Valor mínimo: ${field.getAttribute('min')}`;
        } else if (field.hasAttribute('max') && numValue > parseFloat(field.getAttribute('max'))) {
            isValid = false;
            errorMessage = `Valor máximo: ${field.getAttribute('max')}`;
        }
    }
    
    if (isValid) {
        clearFieldError(field);
    } else {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.cssText = 'color: #e74c3c; font-size: 12px; margin-top: 5px;';
    errorDiv.textContent = message;
    
    field.style.borderColor = '#e74c3c';
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    field.style.borderColor = '#ecf0f1';
}

// Sistema de notificações toast
function showToast(message, type = 'info') {
    // Remover toast anterior se existir
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        padding: 15px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Mostrar com animação
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Esconder automaticamente após 3 segundos
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Auto-esconder mensagens após 5 segundos
function autoHideMessages() {
    const messages = document.querySelectorAll('.message');
    
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (message.parentNode) {
                    message.remove();
                }
            }, 300);
        }, 5000);
    });
}

// Sistema de Modal para mensagens
function showModal(type, title, message, callback = null) {
    // Remover modal anterior se existir
    const existingModal = document.querySelector('.modal-overlay');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Determinar ícone baseado no tipo
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    
    const icon = icons[type] || icons.info;
    
    // Criar modal
    const modalHTML = `
        <div class="modal-overlay" id="messageModal">
            <div class="modal">
                <span class="modal-icon ${type}">${icon}</span>
                <h3>${title}</h3>
                <p>${message}</p>
                <div class="modal-actions">
                    <button class="modal-close" onclick="closeModal()">OK</button>
                </div>
            </div>
        </div>
    `;
    
    // Adicionar ao body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Mostrar modal com animação
    const modal = document.getElementById('messageModal');
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
    
    // Salvar callback se fornecido
    if (callback) {
        window.modalCallback = callback;
    }
    
    // Fechar com ESC
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            closeModal();
            document.removeEventListener('keydown', escHandler);
        }
    });
    
    // Fechar clicando fora do modal
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
}

function closeModal() {
    const modal = document.getElementById('messageModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.remove();
            // Executar callback se existir
            if (window.modalCallback) {
                window.modalCallback();
                window.modalCallback = null;
            }
        }, 300);
    }
}

// Função para mostrar modais de sucesso
function showSuccessModal(message, callback = null) {
    showModal('success', 'Sucesso!', message, callback);
}

// Função para mostrar modais de erro
function showErrorModal(message, callback = null) {
    showModal('error', 'Erro!', message, callback);
}

// Verificar mensagens PHP na sessão e exibir em modal
function checkSessionMessages() {
    // Esta função será chamada quando a página carregar
    // para verificar se existem mensagens da sessão para exibir
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-error');
    
    if (successMessage) {
        const message = successMessage.textContent.trim();
        successMessage.style.display = 'none'; // Esconder a mensagem original
        showSuccessModal(message);
    }
    
    if (errorMessage) {
        const message = errorMessage.textContent.trim();
        errorMessage.style.display = 'none'; // Esconder a mensagem original
        showErrorModal(message);
    }
}

// Função para formatação de números
function formatNumber(number, decimals = 0) {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}

// ============================================
// CURRENCY MASK FUNCTIONS
// ============================================

// Função para converter valor para formato de moeda brasileira
function formatCurrency(value) {
    if (typeof value === 'string') {
        // Remove tudo que não for dígito
        value = value.replace(/\D/g, '');
    }
    
    if (!value || value === '0' || value === '') {
        return '';
    }
    
    // Converte para número e divide por 100 para ter os centavos
    const numValue = parseInt(value) / 100;
    
    // Formata usando Intl.NumberFormat
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2
    }).format(numValue);
}

// Função para converter moeda formatada de volta para número
function currencyToNumber(currencyString) {
    if (!currencyString) return 0;
    
    // Remove símbolos de moeda e espaços, mantém apenas dígitos, vírgula e ponto
    const cleanValue = currencyString
        .replace(/[R$\s]/g, '')  // Remove R$ e espaços
        .replace(/\./g, '')      // Remove pontos (separadores de milhares)
        .replace(/,/g, '.');     // Substitui vírgula por ponto decimal
    
    return parseFloat(cleanValue) || 0;
}

// Função para aplicar máscara de moeda em tempo real
function applyCurrencyMask(input) {
    let value = input.value;
    
    // Remove tudo que não for dígito
    value = value.replace(/\D/g, '');
    
    // Se vazio, limpa o campo
    if (!value) {
        input.value = '';
        return;
    }
    
    // Aplica formatação
    input.value = formatCurrency(value);
}

// Função CORRIGIDA para aplicar máscara de moeda em tempo real (começando com 00,00)
function applyCurrencyMaskFixed(input) {
    let value = input.value;
    
    // Remove tudo que não for dígito
    value = value.replace(/\D/g, '');
    
    // Se vazio, mostra 00,00
    if (!value) {
        input.value = '00,00';
        return;
    }
    
    // Limita a 10 dígitos (até 99.999.999,99)
    if (value.length > 10) {
        value = value.substring(0, 10);
    }
    
    // Converte para número e formata corretamente
    const numValue = parseInt(value) / 100;
    
    // Usa o Intl.NumberFormat para formatação brasileira
    const formatted = new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(numValue);
    
    input.value = formatted;
}

// Função para inicializar máscaras de moeda
function initCurrencyMasks() {
    const currencyInputs = document.querySelectorAll('input[data-mask="currency"]');
      currencyInputs.forEach(input => {
        // Aplicar máscara em tempo real
        input.addEventListener('input', function(e) {
            applyCurrencyMaskFixed(this);
        });
          // Aplicar máscara quando o campo perde o foco
        input.addEventListener('blur', function(e) {
            applyCurrencyMaskFixed(this);
        });
        
        // Aplicar máscara quando o campo ganha foco
        input.addEventListener('focus', function(e) {
            // Se o campo estiver vazio, não faz nada
            if (!this.value) return;
            
            // Remove formatação para permitir edição
            const numericValue = currencyToNumber(this.value);
            if (numericValue > 0) {
                // Converte para centavos para facilitar edição
                const centavos = Math.round(numericValue * 100).toString();
                this.value = centavos;
                this.select(); // Seleciona todo o texto
            }
        });
          // Reaplica formatação quando perde o foco
        input.addEventListener('blur', function(e) {
            if (this.value && !this.value.includes('R$')) {
                applyCurrencyMaskFixed(this);
            }
        });
          // Aplicar máscara inicial - se vazio, coloca 00,00, se já tem valor, aplica máscara
        if (input.value) {
            applyCurrencyMaskFixed(input);
        } else {
            input.value = '00,00';
        }
    });
}

// ============================================
// DETAIL PAGE FUNCTIONALITY
// ============================================

// Toggle favorite status
function toggleFavorite(carId, button) {
    if (!carId) {
        showErrorModal('ID do veículo não encontrado');
        return;
    }

    const originalText = button.innerHTML;
    const isFavorited = button.classList.contains('favorited');
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    
    // Make AJAX request
    const baseUrl = window.BASE_URL || '/';
    // const action = isFavorited ? 'remove' : 'add'; // No longer needed for toggle
    
    fetch(baseUrl + 'favorite/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `car_id=${carId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualiza apenas o ícone do coração, sem texto
            if (data.action === 'removed') {
                button.classList.remove('favorited');
                button.innerHTML = '<i class="fas fa-heart"></i>';
                button.title = 'Adicionar aos favoritos';
                showSuccessModal(data.message || 'Veículo removido dos favoritos!');
            } else if (data.action === 'added') {
                button.classList.add('favorited');
                button.innerHTML = '<i class="fas fa-heart"></i>';
                button.title = 'Remover dos favoritos';
                showSuccessModal(data.message || 'Veículo adicionado aos favoritos!');
            }
        } else {
            showErrorModal(data.message || 'Erro ao atualizar favoritos');
            button.innerHTML = originalText;
        }
        button.disabled = false;
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro de conexão');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Contact via WhatsApp
function contactWhatsApp() {
    // Get car information from the page
    const carTitle = document.querySelector('.hero-title')?.textContent || 'Veículo';
    const carPrice = document.querySelector('.price-value')?.textContent || 'Preço sob consulta';
    const pageUrl = window.location.href;
    
    // WhatsApp number (you should replace with the actual business number)
    const whatsappNumber = '5511999999999'; // Replace with actual number
    
    // Create message
    const message = `Olá! Tenho interesse no veículo ${carTitle}.
💰 Preço: ${carPrice}
🔗 Link: ${pageUrl}

Gostaria de mais informações sobre este veículo.`;
    
    // Encode message for URL
    const encodedMessage = encodeURIComponent(message);
    
    // Create WhatsApp URL
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
    
    // Open WhatsApp
    window.open(whatsappUrl, '_blank');
    
    // Show success message
    showSuccessModal('Redirecionando para WhatsApp...');
}

// Share vehicle
function shareVehicle() {
    const carTitle = document.querySelector('.hero-title')?.textContent || 'Veículo Elite Motors';
    const carPrice = document.querySelector('.price-value')?.textContent || '';
    const carImage = document.querySelector('.hero-image img')?.src || '';
    const pageUrl = window.location.href;
    
    // Check if Web Share API is supported
    if (navigator.share) {
        navigator.share({
            title: `${carTitle} - Elite Motors`,
            text: `Confira este veículo: ${carTitle} ${carPrice ? '- ' + carPrice : ''}`,
            url: pageUrl
        })
        .then(() => {
            showSuccessModal('Compartilhado com sucesso!');
        })
        .catch((error) => {
            console.error('Erro ao compartilhar:', error);
            fallbackShare(carTitle, carPrice, pageUrl);
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        fallbackShare(carTitle, carPrice, pageUrl);
    }
}

// Fallback share function
function fallbackShare(carTitle, carPrice, pageUrl) {
    // Try to copy to clipboard
    if (navigator.clipboard) {
        const shareText = `${carTitle} ${carPrice ? '- ' + carPrice : ''}\n${pageUrl}`;
        
        navigator.clipboard.writeText(shareText)
        .then(() => {
            showSuccessModal('Link copiado para a área de transferência!');
        })
        .catch(() => {
            showShareModal(carTitle, carPrice, pageUrl);
        });
    } else {
        showShareModal(carTitle, carPrice, pageUrl);
    }
}

// Show share modal with social media options
function showShareModal(carTitle, carPrice, pageUrl) {
    const shareText = encodeURIComponent(`${carTitle} ${carPrice ? '- ' + carPrice : ''}`);
    const shareUrl = encodeURIComponent(pageUrl);
    
    const modal = document.createElement('div');
    modal.className = 'share-modal';
    modal.innerHTML = `
        <div class="share-modal-content">
            <div class="share-modal-header">
                <h3><i class="fas fa-share-alt"></i> Compartilhar Veículo</h3>
                <button class="share-modal-close" onclick="closeShareModal()">&times;</button>
            </div>
            <div class="share-modal-body">
                <div class="share-options">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=${shareUrl}" target="_blank" class="share-option facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=${shareText}&url=${shareUrl}" target="_blank" class="share-option twitter">
                        <i class="fab fa-twitter"></i>
                        Twitter
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=${shareUrl}" target="_blank" class="share-option linkedin">
                        <i class="fab fa-linkedin-in"></i>
                        LinkedIn
                    </a>
                    <a href="https://api.whatsapp.com/send?text=${shareText}%20${shareUrl}" target="_blank" class="share-option whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
                <div class="share-url">
                    <label>Link direto:</label>
                    <div class="url-copy">
                        <input type="text" value="${pageUrl}" readonly id="shareUrlInput">
                        <button onclick="copyShareUrl()" class="copy-btn">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close share modal
function closeShareModal() {
    const modal = document.querySelector('.share-modal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

// Copy share URL
function copyShareUrl() {
    const input = document.getElementById('shareUrlInput');
    input.select();
    input.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        showSuccessModal('Link copiado para a área de transferência!');
        closeShareModal();
    } catch (err) {
        showErrorModal('Erro ao copiar link');
    }
}

// Show login modal for non-authenticated users
function showLoginModal() {
    const modal = document.createElement('div');
    modal.className = 'login-required-modal';
    modal.innerHTML = `
        <div class="login-modal-content">
            <div class="login-modal-header">
                <h3><i class="fas fa-lock"></i> Login Necessário</h3>
                <button class="login-modal-close" onclick="closeLoginModal()">&times;</button>
            </div>
            <div class="login-modal-body">
                <p>Para utilizar esta funcionalidade, você precisa fazer login ou criar uma conta.</p>
                <div class="login-modal-actions">
                    <a href="/auth" class="modern-btn modern-btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Fazer Login
                    </a>
                    <button onclick="closeLoginModal()" class="modern-btn modern-btn-secondary">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close login modal
function closeLoginModal() {
    const modal = document.querySelector('.login-required-modal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

// Enhanced collapsible filters with smooth animations
function initCollapsibleFilters() {
    const filterToggle = document.querySelector('.filter-toggle');
    const filterContent = document.querySelector('#filtersContainer');
    const filterIcon = document.querySelector('#filterToggleIcon');
    const filterText = document.querySelector('#filterToggleText');
    const activeCount = document.querySelector('.active-filters-count');
    
    if (filterToggle && filterContent) {
        // Set initial state (collapsed by default)
        filterContent.classList.remove('show');
        let animating = false;

        filterToggle.addEventListener('click', function() {
            if (animating) return;
            animating = true;
            const isVisible = filterContent.classList.contains('show');

            if (isVisible) {
                // Hide filters with animation
                filterContent.classList.remove('show');
                if (filterIcon) filterIcon.style.transform = 'rotate(0deg)';
                if (filterText) filterText.textContent = 'Mostrar Filtros Avançados';
                filterToggle.classList.remove('active');
            } else {
                // Show filters with animation
                filterContent.classList.add('show');
                if (filterIcon) filterIcon.style.transform = 'rotate(180deg)';
                if (filterText) filterText.textContent = 'Ocultar Filtros';
                filterToggle.classList.add('active');
            }
            setTimeout(() => {
                animating = false;
            }, 350); // tempo igual ao transition do CSS
        });
        
        // Update active filters count on page load
        updateActiveFiltersCount();
        
        // Listen for filter changes
        const filterInputs = filterContent.querySelectorAll('input, select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                updateActiveFiltersCount();
                // Add visual feedback for changed filters
                this.classList.add('filter-changed');
                setTimeout(() => {
                    this.classList.remove('filter-changed');
                }, 2000);
            });
        });
        
        // Add form submission enhancement
        const filtersForm = filterContent.querySelector('.filters-form');
        if (filtersForm) {
            filtersForm.addEventListener('submit', function() {
                this.classList.add('loading');
                // Show loading feedback
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtrando...';
                    submitBtn.disabled = true;
                }
            });
        }
    }
}

// Enhanced active filters count with better visual feedback
function updateActiveFiltersCount() {
    const activeCount = document.querySelector('.active-filters-count');
    const filterContent = document.querySelector('#filtersContainer');
    
    if (filterContent) {
        let count = 0;
        
        // Count non-empty text inputs
        const textInputs = filterContent.querySelectorAll('input[type="text"]');
        textInputs.forEach(input => {
            if (input.value.trim() !== '') count++;
        });
        
        // Count selected options (not default)
        const selects = filterContent.querySelectorAll('select');
        selects.forEach(select => {
            if (select.value !== '' && select.value !== select.options[0].value) {
                count++;
                // Add visual indicator for active filters
                select.classList.add('filter-active');
            } else {
                select.classList.remove('filter-active');
            }
        });
        
        // Count checked checkboxes
        const checkboxes = filterContent.querySelectorAll('input[type="checkbox"]:checked');
        count += checkboxes.length;
        
        // Update display with animation
        if (activeCount) {
            if (count > 0) {
                activeCount.textContent = count;
                activeCount.style.display = 'inline-flex';
                activeCount.classList.add('pulse'); // Add animation class
                setTimeout(() => {
                    activeCount.classList.remove('pulse');
                }, 500);
            } else {
                activeCount.style.display = 'none';
            }
        }
        
        // Update toggle button state based on active filters
        const filterToggle = document.querySelector('.filter-toggle');
        if (filterToggle) {
            if (count > 0) {
                filterToggle.classList.add('has-active-filters');
            } else {
                filterToggle.classList.remove('has-active-filters');
            }
        }
    }
}

// Add keyboard navigation for dropdowns
function initDropdownKeyboardNavigation() {
    const selects = document.querySelectorAll('.filter-select');
    
    selects.forEach(select => {
        select.addEventListener('keydown', function(e) {
            // Add better keyboard navigation if needed
            if (e.key === 'Escape') {
                this.blur();
            }
        });
        
        // Add focus/blur effects
        select.addEventListener('focus', function() {
            this.classList.add('focused');
        });
        
        select.addEventListener('blur', function() {
            this.classList.remove('focused');
        });
    });
}

// Initialize dropdown enhancements
document.addEventListener('DOMContentLoaded', function() {
    // ...existing code...
    initDropdownKeyboardNavigation();
});

//# sourceMappingURL=script.js.map
