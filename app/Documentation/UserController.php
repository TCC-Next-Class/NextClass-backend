<?php 

namespace App\Documentation;

use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *    title="API de Usuários",
 *    version="1.0.0",
 *    description="API para gerenciamento de usuários com validação de CPF",
 * )
 */

interface UserController
{

    public function index(): array;
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
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
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
     *             @OA\Property(property="name", type="string", maxLength=255, example="Matheus Duarte"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="matheus.duarte@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="novaSenha123"),
     *             @OA\Property(property="password_confirmation", type="string", example="novaSenha123"),
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
}