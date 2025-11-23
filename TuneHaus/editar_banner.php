<?php
session_start();
require_once __DIR__ . '/../src/conexao-bd.php';

if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    header("Location: home.php");
    exit;
}

$mostrar_alerta = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES['banner']['name'])) {

        $arquivo = $_FILES['banner'];

        $pasta = "uploads/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $nomeFinal = uniqid() . "-" . basename($arquivo['name']);
        $caminho = $pasta . $nomeFinal;

        move_uploaded_file($arquivo['tmp_name'], $caminho);

        $sql = $pdo->prepare("UPDATE home_banner SET imagem = ? WHERE id = 1");
        $sql->execute([$nomeFinal]);

        $mostrar_alerta = true;
    }
}

$stmt = $pdo->query("SELECT imagem FROM home_banner WHERE id = 1");
$banner = $stmt->fetch();
$caminho_atual = $banner && $banner['imagem']
    ? "uploads/" . $banner['imagem']
    : "../img/fundoinicial.jpg";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Banner</title>
    <link rel="stylesheet" href="css/editar-home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<header>
    <div class="cabecalho">TUNEHAUS <img src="img/logopng.png" class="logo"></div>
</header>

<main>
    <section class="editar-container">

        <div class="titulo-imagens">
            <img src="img/notasmusicais.png" class="notas-musicais">
            <h2>EDITAR<br>BANNER</h2>
            <img src="img/notasmusicais2.png" class="notas-musicais-dois">
        </div>

        <div class="img-preview">
            <p>Banner atual:</p>
            <img src="<?= $caminho_atual ?>">
        </div>

        <form method="POST" enctype="multipart/form-data" class="form-banner">
            <label>Escolher nova imagem</label>
            <input type="file" name="banner" required>

            <button class="botao">SALVAR ALTERAÇÕES</button>
            <a href="home.php" class="botao-voltar">Voltar</a>
        </form>
    </section>
</main>

<?php if ($mostrar_alerta): ?>
<script>
Swal.fire({
    title: "Atualizado!",
    text: "O banner foi atualizado com sucesso.",
    icon: "success",
    background: "rgba(233, 195, 255, 1)",
    color: "#292929",
    confirmButtonColor: "#6a1b9a",
    confirmButtonText: "OK"
}).then(() => {
    window.location.href = "home.php";
});
</script>
<?php endif; ?>

</html>