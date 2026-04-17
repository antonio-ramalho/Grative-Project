<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Perfil - Grative</title>
    <style>
        .btn-grative {
            background-color: #3e7c41;
            color: white;
            border: none;
        }
        .btn-grative:hover {
            background-color: #326535;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center bg-light min-vh-100 py-3">
        <div style="max-width: 500px;" class="bg-white rounded-4 shadow-lg w-100 p-4">
            <div class="text-center mb-4">
                <h1 class="fs-3 fw-bold"><span style="color: #1a8853;">GR</span>ATIVE</h1>
                <h2 class="fs-5 text-secondary">Editar Meu Perfil</h2>
            </div>

            <form id="formEditarDoador" action="/api/doador/editar" method="POST">
                
                <div class="form-field-group nome_doador mb-3">
                    <label class="form-label text-muted small" for="nome_doador">Nome Completo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0" name="nome_doador" id="nome_doador" value="<?php echo htmlspecialchars($doador['nome_completo']); ?>" required>
                    </div>
                </div>

                <div class="form-field-group usuario_doador mb-3">
                    <label class="form-label text-muted small" for="usuario_doador">Nome de Usuário</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="bi bi-person-badge"></i></span>
                        <input type="text" class="form-control border-start-0" name="usuario_doador" id="usuario_doador" value="<?php echo htmlspecialchars($doador['usuario']); ?>" required>
                    </div>
                </div>

                <div class="form-field-group cpf_doador mb-3">
                    <label class="form-label text-muted small" for="cpf_doador">CPF</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="bi bi-card-text"></i></span>
                        <input type="text" class="form-control border-start-0" name="cpf_doador" id="cpf_doador" value="<?php echo htmlspecialchars($doador['cpf']); ?>" required>
                    </div>
                </div>

                <div class="form-field-group telefone_doador mb-3">
                    <label class="form-label text-muted small" for="telefone_doador">Telefone</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                        <input type="tel" class="form-control border-start-0" name="telefone_doador" id="telefone_doador" value="<?php echo htmlspecialchars($doador['telefone']); ?>" required>
                    </div>
                </div>

                <div class="form-field-group email_doador mb-3">
                    <label class="form-label text-muted small" for="email_doador">Email (Não editável)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 bg-light text-muted" name="email_doador" value="<?php echo htmlspecialchars($doador['email']); ?>" readonly>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <a href="/home_doador" class="btn btn-light border w-50">Cancelar</a>
                    <button type="submit" class="btn btn-grative w-50">Salvar</button>
                </div>

            </form>
        </div>
    </div>
    
    <script src="/js/editarDoador.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>