Gerenciador de Tarefas Documentation
StartFragment

API de Gerenciador de Tarefas em PHP Puro

Requisitos:

PHP 7+
Endpoints disponíveis:

TAREFAS:  
 • GET - /api.php
Retorna todas as tarefas cadastradas.
Plain Text
• GET - /api.php?id=ID
﻿
- Retorna a tarefa com o identificador especificado (substitua ID pelo número da tarefa).
﻿
﻿
• POST - /api.php
﻿
- Cria uma nova tarefa.
﻿
- Headers: Content-Type: application/json
﻿
- Body (raw JSON):  
﻿
    {  
﻿
    "titulo": "Descrição da tarefa",  
﻿
    "descricao": "Detalhes adicionais (opcional)",  
﻿
    "concluida": false  
﻿
    }
﻿
﻿
• PUT - /api.php
﻿
- Atualiza uma tarefa existente.
﻿
- Headers: Content-Type: application/json
﻿
- Body (raw JSON):  
﻿
    {  
﻿
    "id": 1,  
﻿
    "titulo": "Novo título",  
﻿
    "descricao": "Nova descrição",  
﻿
    "concluida": true  
﻿
    }
﻿
﻿
• DELETE - /api.php?id=ID
﻿
- Exclui a tarefa com o identificador especificado.
Exemplo de JSON para criar tarefa:  

{  

"titulo": "Comprar leite",  

"descricao": "Ir ao supermercado e pegar 2 litros de leite",  

"concluida": false  

}

Formato de data:

Os campos "criada_em" e "atualizada_em" são gerados automaticamente pelo servidor no padrão ISO 8601 (ex: 2025-05-26T20:00:00+00:00).
Observações:

Se o arquivo tarefas.json não existir, ele será criado automaticamente como um array vazio.
Para chamadas PUT e DELETE, use ferramentas que permitam especificar o método HTTP (Postman, cURL, etc.).
CORS está habilitado para permitir requisições de qualquer origem (caso necessário, remova ou restrinja o cabeçalho Access-Control-Allow-Origin).
EndFragment

GET
http://localhost/Gerenciador%20de%20Tarefas/api.php
http://localhost/Gerenciador%20de%20Tarefas/api.php
﻿

GET
http://localhost/Gerenciador%20de%20Tarefas/api.php?id=1
http://localhost/Gerenciador%20de%20Tarefas/api.php?id=1
﻿

Query Params
id
1
POST
http://localhost/Gerenciador%20de%20Tarefas/api.php
http://localhost/Gerenciador%20de%20Tarefas/api.php
﻿

Body
raw (json)
json
   {
        "id": 1,
        "titulo": "Comprar leite",
        "descricao": "Ir ao supermercado e pegar 2 litros de leite",
        "concluida": false,
        "criada_em": "2025-05-26T20:00:00+00:00"
    }
PUT
http://localhost/Gerenciador%20de%20Tarefas/api.php
http://localhost/Gerenciador%20de%20Tarefas/api.php
﻿

Body
raw (json)
json

    {
        "id": 2,
        "titulo": "Limpar a casa",
        "descricao": "",
        "concluida": true,
        "criada_em": "2025-05-25T15:30:00+00:00",
        "atualizada_em": "2025-05-26T10:00:00+00:00"
    }
    
DELETE
http://localhost/Gerenciador%20de%20Tarefas/api.php?id=1
http://localhost/Gerenciador%20de%20Tarefas/api.php?id=1
﻿

Query Params
id
1
