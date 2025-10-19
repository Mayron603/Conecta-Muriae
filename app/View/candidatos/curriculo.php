<?php
// Extrai as variáveis para facilitar o uso na view
$usuario = $dados['usuario'] ?? [];
$curriculum = $dados['curriculum'] ?? [];
$escolaridades = $dados['escolaridades'] ?? [];
$experiencias = $dados['experiencias'] ?? [];
$qualificacoes = $dados['qualificacoes'] ?? [];
$niveis_escolaridade = $dados['niveis_escolaridade'] ?? [];
$cargos = $dados['cargos'] ?? []; // <-- Variável com a lista de cargos

// Verifica se o currículo já existe para habilitar/desabilitar botões
$curriculoId = $curriculum['curriculum_id'] ?? null;
$isCurriculoPendente = empty($curriculoId);

// Inclui o cabeçalho da área do candidato
// Idealmente, o CSS do Cropper.js deve ser colocado no cabeçalho
include_once __DIR__ . "/comuns/candidato_cabecalho.php";
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="row">
        <?php
        include_once __DIR__ . "/comuns/sidebar.php";
        ?>

        <div class="col-lg-9">
            <?php
            if ($msg = \Core\Library\Session::get('mensagem_sucesso')) {
                echo "<div class='alert alert-success alert-dismissible fade show'>{$msg}<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                \Core\Library\Session::destroy('mensagem_sucesso');
            }
            if ($msg = \Core\Library\Session::get('mensagem_erro')) {
                echo "<div class='alert alert-danger alert-dismissible fade show'>{$msg}<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                \Core\Library\Session::destroy('mensagem_erro');
            }
            ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Meu Currículo</h5>
                </div>
                <div class="card-body">
                    <form action="<?= baseUrl() ?>candidatos/salvarCurriculo" method="post">
                        <input type="hidden" name="curriculum_id" value="<?= $curriculoId ?>">
                        
                        <h6 class="text-primary mb-3">Dados Pessoais</h6>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nome Completo</label><input type="text" class="form-control" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" disabled></div>
                            <div class="col-md-6"><label class="form-label">CPF</label><input type="text" class="form-control" value="<?= htmlspecialchars($usuario['cpf'] ?? '') ?>" disabled></div>
                            <div class="col-md-4"><label class="form-label">Data de Nascimento</label><input type="date" class="form-control" name="dataNascimento" value="<?= htmlspecialchars($curriculum['dataNascimento'] ?? '') ?>"></div>
                            <div class="col-md-4"><label class="form-label">Sexo</label><select class="form-select" name="sexo"><option value="">Selecione...</option><option value="M" <?= ($curriculum['sexo'] ?? '') == 'M' ? 'selected' : '' ?>>Masculino</option><option value="F" <?= ($curriculum['sexo'] ?? '') == 'F' ? 'selected' : '' ?>>Feminino</option></select></div>
                            <div class="col-md-4"><label class="form-label">Celular</label><input type="text" class="form-control" name="celular" value="<?= htmlspecialchars($curriculum['celular'] ?? '') ?>"></div>
                        </div>

                        <hr class="my-4">

                        <h6 class="text-primary mb-3">Endereço</h6>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">CEP</label><input type="text" class="form-control" name="cep" value="<?= htmlspecialchars($curriculum['cep'] ?? '') ?>"></div>
                            <div class="col-md-8"><label class="form-label">Logradouro</label><input type="text" class="form-control" name="logradouro" value="<?= htmlspecialchars($curriculum['logradouro'] ?? '') ?>"></div>
                            <div class="col-md-4"><label class="form-label">Número</label><input type="text" class="form-control" name="numero" value="<?= htmlspecialchars($curriculum['numero'] ?? '') ?>"></div>
                            <div class="col-md-8"><label class="form-label">Complemento</label><input type="text" class="form-control" name="complemento" value="<?= htmlspecialchars($curriculum['complemento'] ?? '') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Bairro</label><input type="text" class="form-control" name="bairro" value="<?= htmlspecialchars($curriculum['bairro'] ?? '') ?>"></div>
                            <div class="col-md-4"><label class="form-label">Cidade</label><input type="text" class="form-control" name="cidade" value="<?= htmlspecialchars($curriculum['cidade'] ?? '') ?>" required></div>
                            <div class="col-md-2"><label class="form-label">UF</label><input type="text" class="form-control" name="uf" value="<?= htmlspecialchars($curriculum['uf'] ?? '') ?>" maxlength="2" required></div>
                        </div>

                        <hr class="my-4">

                        <h6 class="text-primary mb-3">Apresentação Pessoal</h6>
                        <div class="row g-3"><div class="col-12"><textarea class="form-control" name="apresentacaoPessoal" rows="5" placeholder="Fale um pouco sobre você, suas habilidades e objetivos..."><?= htmlspecialchars($curriculum['apresentacaoPessoal'] ?? '') ?></textarea></div></div>

                        <div class="mt-4"><button type="submit" class="btn btn-primary">Salvar Dados Principais</button></div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light"><h5 class="mb-0">Foto de Perfil</h5></div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <?php
                            $fotoUrl = baseUrl() . 'uploads/fotos_perfil/default.png';
                            if (!empty($curriculum['foto']) && $curriculum['foto'] !== 'default.png') {
                                $fotoUrl = baseUrl() . 'uploads/fotos_perfil/' . $curriculum['foto'];
                            }
                            ?>
                            <img src="<?= $fotoUrl ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle" style="width:150px; height:150px; object-fit:cover;">
                        </div>
                        <div class="col-md-9">
                            <label for="inputFoto" class="form-label">Alterar foto de perfil</label>
                            <input class="form-control d-none" type="file" id="inputFoto" accept="image/png, image/jpeg, image/gif">
                            
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('inputFoto').click();" <?= $isCurriculoPendente ? 'disabled' : '' ?>>
                                <i class="fas fa-upload me-2"></i> Escolher Nova Foto
                            </button>
                            <div class="form-text mt-2">Você poderá recortar a imagem antes de salvar.</div>
                            
                            <?php if ($isCurriculoPendente): ?>
                                <p class="text-danger fst-italic mt-2">Salve seus dados principais para poder enviar uma foto.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Escolaridade</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEscolaridade" <?= $isCurriculoPendente ? 'disabled' : '' ?>><i class="fas fa-plus me-1"></i> Adicionar Nova</button>
                </div>
                <div class="card-body">
                    <?php if ($isCurriculoPendente): ?>
                        <p class="text-muted fst-italic">Salve seus dados principais para adicionar sua escolaridade.</p>
                    <?php elseif (empty($escolaridades)): ?>
                        <p class="text-muted">Nenhuma formação acadêmica cadastrada.</p>
                    <?php else: ?>
                        <?php foreach ($escolaridades as $index => $item): ?>
                            <form action="<?= baseUrl() ?>candidatos/salvarEscolaridade" method="post">
                                <input type="hidden" name="curriculum_escolaridade_id" value="<?= $item['curriculum_escolaridade_id'] ?>">
                                <div class="p-3 mb-3 border rounded">
                                    <div class="row g-3">
                                        <div class="col-md-12"><label class="form-label fw-bold">Nível</label><input type="text" class="form-control" value="<?= htmlspecialchars($item['nivel_descricao']) ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label fw-bold">Curso</label><input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($item['descricao']) ?>" required></div>
                                        <div class="col-md-6"><label class="form-label fw-bold">Instituição</label><input type="text" name="instituicao" class="form-control" value="<?= htmlspecialchars($item['instituicao']) ?>" required></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Mês Início</label><input type="number" name="inicioMes" class="form-control" value="<?= htmlspecialchars($item['inicioMes']) ?>" required></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Ano Início</label><input type="number" name="inicioAno" class="form-control" value="<?= htmlspecialchars($item['inicioAno']) ?>" required></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Mês Fim</label><input type="number" name="fimMes" class="form-control" value="<?= htmlspecialchars($item['fimMes'] ?? '') ?>" placeholder="Cursando"></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Ano Fim</label><input type="number" name="fimAno" class="form-control" value="<?= htmlspecialchars($item['fimAno'] ?? '') ?>" placeholder="Cursando"></div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Atualizar</button>
                                        <a href="<?= baseUrl() ?>candidatos/excluirEscolaridade/<?= $item['curriculum_escolaridade_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta formação?')">Excluir</a>
                                    </div>
                                </div>
                            </form>
                            <?php if ($index < count($escolaridades) - 1): ?><hr><?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Experiência Profissional</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalExperiencia" <?= $isCurriculoPendente ? 'disabled' : '' ?>><i class="fas fa-plus me-1"></i> Adicionar Nova</button>
                </div>
                <div class="card-body">
                    <?php if ($isCurriculoPendente): ?>
                        <p class="text-muted fst-italic">Salve seus dados principais para adicionar suas experiências.</p>
                    <?php elseif (empty($experiencias)): ?>
                        <p class="text-muted">Nenhuma experiência profissional cadastrada.</p>
                    <?php else: ?>
                        <?php foreach ($experiencias as $index => $item): ?>
                            <form action="<?= baseUrl() ?>candidatos/salvarExperiencia" method="post">
                                <input type="hidden" name="curriculum_experiencia_id" value="<?= $item['curriculum_experiencia_id'] ?>">
                                <div class="p-3 mb-3 border rounded">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Cargo</label>
                                            <select name="cargo_id" class="form-select" onchange="toggleOutroCargo(this, 'outroCargoEdit_<?= $item['curriculum_experiencia_id'] ?>')">
                                                <option value="">Selecione um cargo...</option>
                                                <?php foreach ($cargos as $cargo): ?>
                                                    <option value="<?= $cargo['cargo_id'] ?>" <?= ($item['cargo_id'] == $cargo['cargo_id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($cargo['descricao']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                                <option value="" <?= empty($item['cargo_id']) ? 'selected' : '' ?>>Outro...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Empresa</label>
                                            <input type="text" name="estabelecimento" class="form-control" value="<?= htmlspecialchars($item['estabelecimento']) ?>" required>
                                        </div>
                                        <div class="col-md-12" id="outroCargoEdit_<?= $item['curriculum_experiencia_id'] ?>" style="<?= empty($item['cargo_id']) ? 'display:block;' : 'display:none;' ?>">
                                            <label class="form-label fw-bold">Qual Cargo?</label>
                                            <input type="text" name="cargoDescricao" class="form-control" value="<?= htmlspecialchars($item['cargoDescricao']) ?>" placeholder="Digite o cargo">
                                        </div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Mês Início</label><input type="number" name="inicioMes" class="form-control" value="<?= htmlspecialchars($item['inicioMes']) ?>" required></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Ano Início</label><input type="number" name="inicioAno" class="form-control" value="<?= htmlspecialchars($item['inicioAno']) ?>" required></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Mês Fim</label><input type="number" name="fimMes" class="form-control" value="<?= htmlspecialchars($item['fimMes'] ?? '') ?>" placeholder="Atual"></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Ano Fim</label><input type="number" name="fimAno" class="form-control" value="<?= htmlspecialchars($item['fimAno'] ?? '') ?>" placeholder="Atual"></div>
                                        <div class="col-12"><label class="form-label fw-bold">Atividades Exercidas</label><textarea class="form-control" name="atividadesExercidas" rows="3"><?= htmlspecialchars($item['atividadesExercidas'] ?? '') ?></textarea></div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Atualizar</button>
                                        <a href="<?= baseUrl() ?>candidatos/excluirExperiencia/<?= $item['curriculum_experiencia_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</a>
                                    </div>
                                </div>
                            </form>
                            <?php if ($index < count($experiencias) - 1): ?><hr><?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Qualificações e Cursos</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalQualificacao" <?= $isCurriculoPendente ? 'disabled' : '' ?>><i class="fas fa-plus me-1"></i> Adicionar Novo</button>
                </div>
                <div class="card-body">
                    <?php if ($isCurriculoPendente): ?>
                        <p class="text-muted fst-italic">Salve seus dados principais para adicionar suas qualificações.</p>
                    <?php elseif (empty($qualificacoes)): ?>
                        <p class="text-muted">Nenhuma qualificação ou curso cadastrado.</p>
                    <?php else: ?>
                        <?php foreach ($qualificacoes as $index => $item): ?>
                            <form action="<?= baseUrl() ?>candidatos/salvarQualificacao" method="post">
                                <input type="hidden" name="curriculum_qualificacao_id" value="<?= $item['curriculum_qualificacao_id'] ?>">
                                <div class="p-3 mb-3 border rounded">
                                    <div class="row g-3">
                                        <div class="col-md-12"><label class="form-label fw-bold">Curso/Descrição</label><input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($item['descricao']) ?>" required></div>
                                        <div class="col-md-5"><label class="form-label fw-bold">Instituição</label><input type="text" name="estabelecimento" class="form-control" value="<?= htmlspecialchars($item['estabelecimento']) ?>" required></div>
                                        <div class="col-md-2"><label class="form-label fw-bold">Carga (h)</label><input type="number" name="cargaHoraria" class="form-control" value="<?= htmlspecialchars($item['cargaHoraria']) ?>" required></div>
                                        <div class="col-md-2"><label class="form-label fw-bold">Mês Conclusão</label><input type="number" name="mes" class="form-control" value="<?= htmlspecialchars($item['mes']) ?>"></div>
                                        <div class="col-md-3"><label class="form-label fw-bold">Ano Conclusão</label><input type="number" name="ano" class="form-control" value="<?= htmlspecialchars($item['ano']) ?>"></div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Atualizar</button>
                                        <a href="<?= baseUrl() ?>candidatos/excluirQualificacao/<?= $item['curriculum_qualificacao_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta qualificação?')">Excluir</a>
                                    </div>
                                </div>
                            </form>
                            <?php if ($index < count($qualificacoes) - 1): ?><hr><?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEscolaridade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= baseUrl() ?>candidatos/salvarEscolaridade" method="post">
                <div class="modal-header"><h5 class="modal-title">Adicionar Nova Formação Acadêmica</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nível de Escolaridade</label><select name="escolaridade_id" class="form-select" required><option value="">Selecione o nível</option><?php foreach ($niveis_escolaridade as $nivel): ?><option value="<?= $nivel['escolaridade_id'] ?>"><?= htmlspecialchars($nivel['descricao']) ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Curso</label><input type="text" name="descricao" class="form-control" required placeholder="Ex: Bacharelado em Ciência da Computação"></div>
                    <div class="mb-3"><label class="form-label">Instituição de Ensino</label><input type="text" name="instituicao" class="form-control" required placeholder="Ex: Universidade Federal de..."></div>
                    <div class="row mb-3"><div class="col-md-8"><label class="form-label">Cidade da Instituição</label><input type="text" name="cidade" class="form-control" required></div><div class="col-md-4"><label class="form-label">UF</label><input type="text" name="uf" class="form-control" maxlength="2" required></div></div>
                    <div class="row"><label class="form-label">Período</label><div class="col-sm-3"><input type="number" name="inicioMes" class="form-control" placeholder="Mês Início" min="1" max="12" required></div><div class="col-sm-3"><input type="number" name="inicioAno" class="form-control" placeholder="Ano Início" required></div><div class="col-sm-3"><input type="number" name="fimMes" class="form-control" placeholder="Mês Fim" min="1" max="12"></div><div class="col-sm-3"><input type="number" name="fimAno" class="form-control" placeholder="Ano Fim"></div><div class="form-text">Preencha Mês/Ano de Fim apenas se já concluiu.</div></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Adicionar Formação</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExperiencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= baseUrl() ?>candidatos/salvarExperiencia" method="post">
                <div class="modal-header"><h5 class="modal-title">Adicionar Nova Experiência</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cargo</label>
                            <select name="cargo_id" class="form-select" onchange="toggleOutroCargo(this, 'outroCargoAdd')">
                                <option value="">Selecione um cargo...</option>
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?= $cargo['cargo_id'] ?>"><?= htmlspecialchars($cargo['descricao']) ?></option>
                                <?php endforeach; ?>
                                <option value="">Outro...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <input type="text" name="estabelecimento" class="form-control" required>
                        </div>
                        <div class="col-md-12" id="outroCargoAdd" style="display:none;">
                            <label class="form-label">Qual Cargo?</label>
                            <input type="text" name="cargoDescricao" class="form-control" placeholder="Digite o cargo">
                        </div>
                        <div class="col-md-3"><label class="form-label">Mês Início</label><input type="number" name="inicioMes" class="form-control" placeholder="Mês" min="1" max="12" required></div>
                        <div class="col-md-3"><label class="form-label">Ano Início</label><input type="number" name="inicioAno" class="form-control" placeholder="Ano" required></div>
                        <div class="col-md-3"><label class="form-label">Mês Fim</label><input type="number" name="fimMes" class="form-control" placeholder="Mês" min="1" max="12"></div>
                        <div class="col-md-3"><label class="form-label">Ano Fim</label><input type="number" name="fimAno" class="form-control" placeholder="Ano"></div>
                        <div class="col-12"><label class="form-label">Atividades Exercidas</label><textarea class="form-control" name="atividadesExercidas" rows="3" placeholder="Descreva suas responsabilidades..."></textarea></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Adicionar</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQualificacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= baseUrl() ?>candidatos/salvarQualificacao" method="post">
                <div class="modal-header"><h5 class="modal-title">Adicionar Nova Qualificação</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Curso/Descrição</label><input type="text" name="descricao" class="form-control" required></div>
                        <div class="col-md-8"><label class="form-label">Instituição</label><input type="text" name="estabelecimento" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Carga Horária (horas)</label><input type="number" name="cargaHoraria" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Mês de Conclusão</label><input type="number" name="mes" class="form-control" placeholder="Mês" min="1" max="12"></div>
                        <div class="col-md-6"><label class="form-label">Ano de Conclusão</label><input type="number" name="ano" class="form-control" placeholder="Ano"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Adicionar</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCropFoto" tabindex="-1" aria-labelledby="modalCropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCropLabel">Recortar Foto de Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <img id="imageToCrop" src="" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="cropAndUpload">Salvar Foto</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
function toggleOutroCargo(selectElement, targetId) {
    const targetDiv = document.getElementById(targetId);
    if (selectElement.value === "") {
        targetDiv.style.display = 'block';
    } else {
        targetDiv.style.display = 'none';
        const input = targetDiv.querySelector('input[name="cargoDescricao"]');
        if (input) {
            input.value = ''; // Limpa o campo de texto se uma opção for selecionada
        }
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFoto = document.getElementById('inputFoto');
    const modalCropFoto = new bootstrap.Modal(document.getElementById('modalCropFoto'));
    const imageToCrop = document.getElementById('imageToCrop');
    const cropAndUploadBtn = document.getElementById('cropAndUpload');
    let cropper;

    // 1. Quando um arquivo é escolhido no input
    inputFoto.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                modalCropFoto.show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // 2. Quando o modal é exibido, inicializa o Cropper.js
    document.getElementById('modalCropFoto').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1 / 1, // Força um corte quadrado
            viewMode: 1,
            dragMode: 'move',
            background: false,
        });
    });

    // 3. Quando o modal é fechado, destrói a instância do Cropper
    document.getElementById('modalCropFoto').addEventListener('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
        inputFoto.value = ''; // Limpa o input
    });

    // 4. Quando o botão "Salvar Foto" é clicado
    cropAndUploadBtn.addEventListener('click', function () {
        this.disabled = true;
        this.innerHTML = 'Enviando...';

        cropper.getCroppedCanvas({
            width: 400, // Largura da imagem final
            height: 400, // Altura da imagem final
        }).toBlob(function (blob) {
            const formData = new FormData();
            // O nome 'foto' deve ser o mesmo esperado no seu controller PHP
            formData.append('foto', blob, 'foto_perfil.jpg'); 
            
            // Envia a imagem para o servidor
            fetch('<?= baseUrl() ?>candidatos/salvarFoto', { // Rota do seu controller para salvar
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload(); // Recarrega a página em caso de sucesso
                } else {
                    alert(data.message || 'Ocorreu um erro ao enviar a foto.');
                    cropAndUploadBtn.disabled = false;
                    cropAndUploadBtn.innerHTML = 'Salvar Foto';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro de comunicação com o servidor.');
                cropAndUploadBtn.disabled = false;
                cropAndUploadBtn.innerHTML = 'Salvar Foto';
            });

        }, 'image/jpeg');
    });
});
</script>

<?php
include_once __DIR__ . "/comuns/candidato_rodape.php";
?>