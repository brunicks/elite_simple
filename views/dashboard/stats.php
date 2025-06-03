<?php
// views/dashboard/stats.php
$title = $title ?? 'Estatísticas - Concessionária';
include_once ROOT . '/views/layouts/header.php';
?>

<main class="container">
    <!-- Header das Estatísticas -->
    <div class="page-header" style="margin: 2rem 0; text-align: center;">
        <h1 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 700;">
            📊 Estatísticas da Concessionária
        </h1>
        <p style="font-size: 1.2rem; color: #7f8c8d; margin: 0;">
            Visão geral dos dados do sistema EliteMotors
        </p>    </div>

    <!-- Cards de Estatísticas Principais -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <!-- Total de Carros -->
        <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3); text-align: center; transition: transform 0.3s ease;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🚗</div>
            <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= number_format($stats['total'] ?? 0, 0, ',', '.') ?>
            </div>
            <div style="font-size: 1.1rem; opacity: 0.9;">Total de Carros Ativos</div>
        </div>

        <!-- Preço Médio -->
        <div style="background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(39, 174, 96, 0.3); text-align: center; transition: transform 0.3s ease;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">💰</div>
            <div style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                R$ <?= number_format($stats['avg_price'] ?? 0, 0, ',', '.') ?>
            </div>
            <div style="font-size: 1.1rem; opacity: 0.9;">Preço Médio</div>
        </div>

        <!-- Carro Mais Caro -->
        <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(231, 76, 60, 0.3); text-align: center; transition: transform 0.3s ease;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">💎</div>
            <div style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                R$ <?= number_format($stats['max_price'] ?? 0, 0, ',', '.') ?>
            </div>
            <div style="font-size: 1.1rem; opacity: 0.9;">Carro Mais Caro</div>
        </div>

        <!-- Carro Mais Barato -->
        <div style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(243, 156, 18, 0.3); text-align: center; transition: transform 0.3s ease;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🏷️</div>
            <div style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                R$ <?= number_format($stats['min_price'] ?? 0, 0, ',', '.') ?>
            </div>
            <div style="font-size: 1.1rem; opacity: 0.9;">Carro Mais Barato</div>
        </div>
    </div>

    <!-- Marcas Populares -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); margin-bottom: 3rem;">
        <h3 style="color: #2c3e50; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 600; text-align: center;">
            🏆 Top 5 Marcas Mais Populares
        </h3>
        
        <?php if (!empty($stats['popular_brands'])): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <?php foreach ($stats['popular_brands'] as $index => $brand): ?>
                    <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 12px; text-align: center; border-left: 4px solid <?= ['#3498db', '#27ae60', '#f39c12', '#e74c3c', '#9b59b6'][$index % 5] ?>; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                            <?= ['🥇', '🥈', '🥉', '🏅', '🏅'][$index] ?? '🏅' ?>
                        </div>
                        <div style="font-size: 1.3rem; font-weight: 700; color: #2c3e50; margin-bottom: 0.5rem;">
                            <?= htmlspecialchars($brand['marca']) ?>
                        </div>
                        <div style="font-size: 2rem; font-weight: 700; color: <?= ['#3498db', '#27ae60', '#f39c12', '#e74c3c', '#9b59b6'][$index % 5] ?>;">
                            <?= $brand['count'] ?>
                        </div>
                        <div style="font-size: 0.9rem; color: #7f8c8d;">
                            <?= $brand['count'] == 1 ? 'veículo' : 'veículos' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 2rem; color: #7f8c8d;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                <p>Nenhum dado de marca disponível ainda.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Resumo Financeiro -->
    <div style="background: linear-gradient(135deg, #2c3e50, #34495e); color: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(44, 62, 80, 0.3); margin-bottom: 3rem;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 600; text-align: center;">
            💼 Resumo Financeiro do Estoque
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;">
            <div>
                <div style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.5rem;">Valor Total do Estoque</div>
                <div style="font-size: 1.8rem; font-weight: 700;">
                    R$ <?= number_format(($stats['total'] ?? 0) * ($stats['avg_price'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>
            <div>
                <div style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.5rem;">Faixa de Preços</div>
                <div style="font-size: 1.2rem; font-weight: 600;">
                    R$ <?= number_format($stats['min_price'] ?? 0, 0, ',', '.') ?> - R$ <?= number_format($stats['max_price'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
            <div>
                <div style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.5rem;">Variação de Preços</div>
                <div style="font-size: 1.2rem; font-weight: 600;">
                    R$ <?= number_format(($stats['max_price'] ?? 0) - ($stats['min_price'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações -->
    <div style="text-align: center; margin: 3rem 0;">
        <a href="<?= BASE_URL ?>dashboard" class="btn btn-secondary" style="margin-right: 15px; padding: 12px 25px; font-size: 1.1rem;">
            ← Voltar ao Dashboard
        </a>
        <a href="<?= BASE_URL ?>car/add" class="btn btn-success" style="padding: 12px 25px; font-size: 1.1rem;">
            + Adicionar Novo Carro
        </a>
    </div>
</main>

<style>
/* Efeitos hover nos cards */
.container > div:nth-child(2) > div:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important;
}

/* Responsividade */
@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr 1fr !important;
    }
}

@media (max-width: 480px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr !important;
    }
    
    .page-header h1 {
        font-size: 2rem !important;
    }
    
    .page-header p {
        font-size: 1rem !important;
    }
}
</style>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
