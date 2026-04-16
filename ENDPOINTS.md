# Documentacao de Endpoints

Guia rapido dos endpoints da API Livros.

## Base URL

Em ambiente Docker local, a API fica em:

```text
http://localhost:8080
```

Observacao: no `index.php` existe um `str_replace("/api-livros", "", $uri)`, entao dependendo do ambiente a aplicacao pode estar esperando esse prefixo na URL.

## Headers

### Endpoints publicos

Usam apenas:

```http
Content-Type: application/json
```

### Endpoints autenticados

Usam:

```http
Content-Type: application/json
Authorization: Bearer <token>
X-User-UUID: <uuid-do-usuario>
```

## Resposta padrao

Muitas rotas seguem este formato:

### Sucesso

```json
{
  "success": true,
  "detail": {}
}
```

### Erro

```json
{
  "success": false,
  "mensagem": "Descricao do erro"
}
```

## 1. Autenticacao

### POST `/register`

Cadastra um novo usuario.

Autenticacao: nao

Body:

```json
{
  "nome": "Luiz Felipe",
  "email": "luiz@email.com",
  "senha": "Senha@123"
}
```

Validacoes identificadas:

- `nome` obrigatorio
- `email` obrigatorio e valido
- `senha` obrigatoria
- minimo de 8 caracteres
- deve conter letra maiuscula
- deve conter letra minuscula
- deve conter numero
- deve conter caractere especial

Resposta de sucesso `201`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Usuario registrado com sucesso"
  }
}
```

Erros comuns:

- `400`: email ja cadastrado ou dados invalidos
- `500`: erro ao cadastrar usuario

### POST `/login`

Realiza login e retorna token JWT.

Autenticacao: nao

Body:

```json
{
  "email": "luiz@email.com",
  "senha": "Senha@123"
}
```

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Login realizado com sucesso",
    "access_token": "jwt-token",
    "UUID": "uuid-do-usuario",
    "nome": "Luiz Felipe",
    "email": "luiz@email.com",
    "foto_perfil": null
  }
}
```

Erros comuns:

- `400`: dados invalidos
- `401`: email ou senha invalidos

## 2. Usuario

### GET `/usuario`

Retorna os dados do usuario autenticado.

Autenticacao: sim

Body: nao possui

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Usuario encontrado",
    "usuario": {
      "id_usuario": 1,
      "nome": "Luiz Felipe",
      "email": "luiz@email.com"
    }
  }
}
```

Erros comuns:

- `401`: nao autenticado
- `404`: usuario nao encontrado

### PUT `/usuario/editar`

Edita os dados do usuario autenticado.

Autenticacao: sim

Body:

```json
{
  "nome": "Novo Nome",
  "email": "novo@email.com"
}
```

Campos aceitos:

- `nome` opcional
- `email` opcional

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Usuario atualizado com sucesso",
    "usuario": {
      "id_usuario": 1
    }
  }
}
```

Erros comuns:

- `401`: nao autenticado
- `409`: email ja esta em uso
- `500`: erro ao atualizar usuario

### PATCH `/usuario/foto`

Atualiza a foto de perfil do usuario autenticado.

Autenticacao: sim

Body:

```json
{
  "url_foto": "https://site.com/minha-foto.jpg"
}
```

Validacoes identificadas:

- `url_foto` obrigatoria
- `url_foto` deve ser uma URL valida

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Foto de perfil atualizada com sucesso",
    "foto_perfil": "https://site.com/minha-foto.jpg"
  }
}
```

Erros comuns:

- `400`: URL vazia ou invalida
- `401`: nao autenticado
- `500`: erro ao atualizar foto

### DELETE `/usuario/deletar`

Remove o usuario autenticado.

Autenticacao: sim

Body: nao possui

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Usuario deletado com sucesso"
  }
}
```

Erros comuns:

- `401`: nao autenticado
- `500`: erro ao deletar usuario

## 3. Livros

### POST `/livros`

Cria um novo livro para o usuario autenticado.

Autenticacao: sim

Body:

```json
{
  "titulo": "Dom Casmurro",
  "autor": "Machado de Assis",
  "ano": 1899,
  "genero": "Romance",
  "status": "quero_ler",
  "avaliacao": 5,
  "anotacoes": "Quero reler este ano"
}
```

Campos e validacoes:

- `titulo` obrigatorio
- `autor` obrigatorio
- `ano` obrigatorio e nao pode ser futuro
- `genero` opcional
- `status` obrigatorio: `lendo`, `lido` ou `quero_ler`
- `avaliacao` opcional entre `1` e `5`
- `anotacoes` opcional

Resposta de sucesso `201`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Livro criado com sucesso",
    "id": 10
  }
}
```

Erros comuns:

- `400`: dados invalidos
- `401`: nao autenticado
- `500`: erro ao criar livro

### GET `/livros`

Lista os livros do usuario autenticado com paginacao e filtros.

Autenticacao: sim

Query params suportados identificados no codigo:

- `page`
- `limit`
- `id_livro`
- `titulo`
- `autor`
- `ano`
- `genero`
- `status`
- `avaliacao`
- `anotacoes`

Exemplo:

```text
GET /livros?page=1&limit=10&status=lendo
```

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "livros": [],
    "paginacao": {}
  }
}
```

Erros comuns:

- `401`: nao autenticado
- `404`: nenhum livro encontrado

Observacao: a estrutura exata da lista depende do retorno de `Livro::encontrarLivro()`.

### PUT `/livro/editar`

Atualiza um livro existente do usuario autenticado.

Autenticacao: sim

Body:

```json
{
  "id_livro": 10,
  "titulo": "Dom Casmurro",
  "autor": "Machado de Assis",
  "ano": 1900,
  "genero": "Romance",
  "status": "lido",
  "avaliacao": 5,
  "anotacoes": "Atualizado"
}
```

Regras identificadas:

- `id_livro` obrigatorio
- ao menos um campo de atualizacao deve ser enviado
- `status` deve ser `lendo`, `lido` ou `quero_ler`
- `avaliacao` deve estar entre `1` e `5`
- `ano` nao pode ser futuro

Resposta de sucesso `200`:

```json
{
  "success": true,
  "detail": {
    "mensagem": "Livro atualizado com sucesso",
    "detail": {
      "livro": {
        "id": 10,
        "titulo": "Dom Casmurro",
        "autor": "Machado de Assis",
        "ano": 1900,
        "genero": "Romance",
        "status": "lido",
        "avaliacao": 5,
        "anotacoes": "Atualizado"
      }
    }
  }
}
```

Erros comuns:

- `400`: id ausente ou dados invalidos
- `401`: nao autenticado
- `404`: livro nao encontrado
- `500`: erro ao atualizar livro

### DELETE `/livro/deletar`

Remove um livro do usuario autenticado.

Autenticacao: sim

Body:

```json
{
  "id_livro": 10
}
```

Resposta de sucesso `204`:

Sem corpo de resposta.

Erros comuns:

- `400`: `id_livro` obrigatorio
- `401`: nao autenticado
- `404`: livro nao encontrado

## 4. Recuperacao de senha

### POST `/recuperar-senha`

Inicia o fluxo de recuperacao de senha por e-mail.

Autenticacao: nao

Body:

```json
{
  "email": "luiz@email.com"
}
```

Resposta de sucesso `200`:

```json
{
  "success": true,
  "mensagem": "Se o e-mail estiver cadastrado, voce recebera instrucoes para redefinir sua senha."
}
```

Erros comuns:

- `400`: e-mail obrigatorio
- `500`: erro ao gerar token ou enviar e-mail

### POST `/redefinir-senha`

Redefine a senha do usuario.

Autenticacao: no codigo atual existe inconsistencia. A rota esta marcada como publica em `routes/api.php`, mas o controller tenta acessar `$this->user`, o que sugere um problema de implementacao.

Body:

```json
{
  "token": "token-recebido-no-email",
  "senha": "NovaSenha@123"
}
```

Resposta esperada de sucesso `200`:

```json
{
  "success": true,
  "mensagem": "Senha redefinida com sucesso"
}
```

Erros comuns:

- `500`: nao foi possivel atualizar a senha

## Observacoes importantes da analise

- Endpoints autenticados exigem dois headers: JWT e `X-User-UUID`.
- O projeto tem documentacao Swagger, mas este arquivo foi escrito separadamente para leitura rapida.
- Existe uma divergencia no projeto: a rota de foto esta como `PATCH /usuario/foto`, mas a anotacao OpenAPI no controller usa `PUT`.
- Existe outra divergencia no fluxo de redefinicao de senha: a rota e publica, mas o metodo tenta ler usuario autenticado.
