<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

interface UserController
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Listar todos os usuários",
     *     description="Retorna a lista de usuários cadastrados",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */

    public function index(): array;

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Exibir um usuário específico",
     *     description="Retorna os detalhes de um usuário",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function show(int $id): array;

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Criar um novo usuário",
     *     description="Cria um novo usuário com validação de CPF",
     *     tags={"Usuários"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","cpf","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Foo Bar"),
     *             @OA\Property(property="cpf", type="string", example="987.654.321-00"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="foo.bar@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password"),
     *             @OA\Property(property="password_confirmation", type="string", minLength=8, example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Foo Bar"),
     *             @OA\Property(property="email", type="string", format="email", example="foo.bar@example.com"),
     *             @OA\Property(property="cpf", type="string", example="987.654.321-00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function store(array $data): array;

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualizar um usuário",
     *     description="Atualiza os dados de um usuário existente",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Foo Bar"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="foo.bar@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password"),
     *             @OA\Property(property="password_confirmation", type="string", example="password"),
     *             @OA\Property(property="cpf", type="string", example="987.654.321-00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     * @OA\Patch(
     *     path="/api/users/{id}",
     *     summary="Atualizar parcialmente um usuário",
     *     description="Atualiza apenas alguns dados de um usuário existente",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Foo Bar"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="foo.bar@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password"),
     *             @OA\Property(property="password_confirmation", type="string", example="password"),
     *             @OA\Property(property="cpf", type="string", example="987.654.321-00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado parcialmente com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */

    public function update(int $id, array $data): array;

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Excluir um usuário",
     *     description="Remove um usuário do sistema",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuário deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function destroy(int $id): void;

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Obter informações do usuário autenticado",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário autenticado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */

    public function me();

    /**
     * @OA\Get(
     *     path="/api/users/search",
     *     summary="Buscar usuário pelo e-mail",
     *     description="Verifica se um usuário existe a partir do e-mail e retorna dados públicos (nome, avatar, etc.)",
     *     tags={"Usuários"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="E-mail do usuário a ser pesquisado",
     *         @OA\Schema(type="string", format="email", example="john.doe@example.com")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *                 @OA\Property(property="avatar", type="string", example="https://cdn.example.com/avatars/12.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     )
     * )
     */
    public function search();
}
