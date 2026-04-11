<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grative - Cadastro de Usuário</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            background-color: #ffffff;
            color: #333;
        }

        .profile-pic-container {
            width: 100px; 
            height: 100px;
            cursor: pointer;
            margin: 0 auto;
        }

        .profile-pic-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-pic-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
        }

        .profile-pic-container:hover .profile-pic-overlay {
            opacity: 1;
        }

        #profile-pic-input {
            display: none;
        }

        .form-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.3rem;
        }

        .input-group-text {
            background-color: #fff;
            border-right: none;
            color: #888;
        }
        
        .form-control {
            border-left: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }
        
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #3e7c41; 
        }

        /* --- BOTÕES --- */
        .btn-grative {
            background-color: #3e7c41;
            color: white;
            border: none;
            padding: 12px;
        }
        .btn-grative:hover {
            background-color: #326535;
            color: white;
        }
        .btn-cancel {
            background-color: #f0f0f0;
            color: #666;
            border: none;
            padding: 12px;
        }
        .btn-cancel:hover {
            background-color: #e4e4e4;
        }
    </style>
</head>
<body class="d-flex flex-column align-items-center justify-content-center min-vh-100 py-5">

    <div class="w-100 px-3" style="max-width: 450px;">
        
        <div class="mb-4 text-center">
            <label for="profile-pic-input" class="profile-pic-container position-relative rounded-circle shadow-sm" title="Clique para alterar a foto">
                <img src="https://via.placeholder.com/100?text=%F0%9F%91%A4" alt="Foto de Perfil" class="profile-pic-preview rounded-circle" id="pic-preview">
                <div class="profile-pic-overlay d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-camera fs-5 mb-1"></i>
                    <span style="font-size: 12px;">Alterar</span>
                </div>
            </label>
            <input type="file" id="profile-pic-input" accept="image/*">
        </div>

        <form id="cadastro-form" onsubmit="event.preventDefault(); alert('Formulário enviado com sucesso!');">
            
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <div class="input-group rounded-2">
                    <span class="input-group-text rounded-start-2"><i class="far fa-user"></i></span>
                    <input type="text" class="form-control rounded-end-2" placeholder="Digite seu nome" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nome de Usuário</label>
                <div class="input-group rounded-2">
                    <span class="input-group-text rounded-start-2"><i class="far fa-user"></i></span>
                    <input type="text" class="form-control rounded-end-2" placeholder="Digite seu nome de Usuário" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group rounded-2">
                    <span class="input-group-text rounded-start-2"><i class="far fa-envelope"></i></span>
                    <input type="email" class="form-control rounded-end-2" placeholder="Digite seu email" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Data de Nascimento</label>
                <div class="input-group rounded-2">
                    <span class="input-group-text rounded-start-2"><i class="far fa-calendar-alt"></i></span>
                    <input type="date" class="form-control rounded-end-2" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <div class="input-group rounded-2">
                    <span class="input-group-text rounded-start-2"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control rounded-end-2" placeholder="Digite sua senha" required minlength="6">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirmar Senha</label>
                <div class="input-group rounded-2">
                    <span class="input-group-text rounded-start-2"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control rounded-end-2" placeholder="Confirme sua senha" required minlength="6">
                </div>
            </div>

            <div class="d-flex flex-column gap-2 mt-4">
                <button type="submit" class="btn btn-grative w-100 rounded-2">Concluir Cadastro</button>
                <button type="button" class="btn btn-cancel w-100 rounded-2" onclick="alert('Operação cancelada.');">Cancelar</button>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.css"></script>

    <script>
        const fileInput = document.getElementById('profile-pic-input');
        const imgPreview = document.getElementById('pic-preview');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>