<?php
header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'];
$arquivo = 'tarefas.json';

if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$tarefas = json_decode(file_get_contents($arquivo), true);
if (!is_array($tarefas)) {
    $tarefas = [];
    file_put_contents($arquivo, json_encode($tarefas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function salvar(&$tarefas, $arquivo)
{
    file_put_contents($arquivo, json_encode($tarefas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

switch ($metodo) {

    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            foreach ($tarefas as $t) {
                if ($t['id'] === $id) {
                    echo json_encode($t, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            http_response_code(404);
            echo json_encode(["erro" => "Tarefa não encontrada."]);
        } else {
            echo json_encode($tarefas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':

        $dados = json_decode(file_get_contents('php://input'), true);
        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(["erro" => "JSON inválido ou não enviado."]);
            exit;
        }

        if (empty($dados['titulo'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Campo 'titulo' é obrigatório."]);
            exit;
        }
        $ids = array_column($tarefas, 'id');
        $nextId = $ids ? max($ids) + 1 : 1;

        $nova = [
            "id" => $nextId,
            "titulo" => $dados['titulo'],
            "descricao" => $dados['descricao'] ?? "",
            "concluida" => $dados['concluida'] ?? false,
            "criada_em" => date('c')
        ];

        $tarefas[] = $nova;
        salvar($tarefas, $arquivo);

        echo json_encode([
            "mensagem" => "Tarefa criada com sucesso!",
            "tarefa" => $nova
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'PUT':
        $dados = json_decode(file_get_contents('php://input'), true);
        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(["erro" => "JSON inválido ou não enviado."]);
            exit;
        }

        if (!isset($dados['id'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Campo 'id' é obrigatório para atualização."]);
            exit;
        }
        $atualizado = false;
        foreach ($tarefas as &$t) {
            if ($t['id'] === intval($dados['id'])) {
                if (isset($dados['titulo']))
                    $t['titulo'] = $dados['titulo'];
                if (isset($dados['descricao']))
                    $t['descricao'] = $dados['descricao'];
                if (isset($dados['concluida']))
                    $t['concluida'] = $dados['concluida'];
                $t['atualizada_em'] = date('c');
                $atualizado = true;
                $retorno = $t;
                break;
            }
        }
        unset($t);
        if (!$atualizado) {
            http_response_code(404);
            echo json_encode(["erro" => "Tarefa não encontrada."]);
            exit;
        }
        salvar($tarefas, $arquivo);
        echo json_encode([
            "mensagem" => "Tarefa atualizada com sucesso!",
            "tarefa" => $retorno
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Parâmetro 'id' é obrigatório para exclusão."]);
            exit;
        }
        $id = intval($_GET['id']);
        $before = count($tarefas);
        $tarefas = array_filter($tarefas, fn($t) => $t['id'] !== $id);
        if (count($tarefas) === $before) {
            http_response_code(404);
            echo json_encode(["erro" => "Tarefa não encontrada."]);
            exit;
        }
        $tarefas = array_values($tarefas);
        salvar($tarefas, $arquivo);
        echo json_encode(["mensagem" => "Tarefa excluída com sucesso!"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não suportado."], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
