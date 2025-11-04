<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['alert'])) return;

$alert = $_SESSION['alert'];
unset($_SESSION['alert']); // evita repetir no refresh

$acoes = [
    'cadastrar' => [
        'titulo' => '🎶 Sucesso!',
        'texto' => 'Produto cadastrado com sucesso!',
        'icone' => 'success'
    ],
    'editar' => [
        'titulo' => '✏️ Alterado!',
        'texto' => 'Produto atualizado com sucesso!',
        'icone' => 'success'
    ],
    'excluir' => [
        'titulo' => '🗑️ Excluído!',
        'texto' => 'O produto foi removido.',
        'icone' => 'warning'
    ],
    'erro' => [
        'titulo' => '⚠️ Ocorreu um erro',
        'texto' => 'Não foi possível realizar a ação. Tente novamente.',
        'icone' => 'error'
    ]
];

// Se ação não existir, não mostra nada
if (!isset($acoes[$alert['acao']])) return;

$d = $acoes[$alert['acao']];
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    title: '<?= $d['titulo'] ?>',
    text: '<?= $d['texto'] ?>',
    icon: '<?= $d['icone'] ?>',
    background: '#121212',
    color: '#e6e6e6',
    confirmButtonColor: '#6a1b9a'
});
</script>
