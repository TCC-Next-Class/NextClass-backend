<?php

namespace App\Documentation;

use OpenApi\Annotations as OA;

interface SessionController
{
    /**
     * @OA\Get(
     *     path="/api/sessions",
     *     summary="Listar sessões ativas do usuário autenticado",
     *     tags={"Sessões"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de sessões",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Session")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function index();

    /**
     * @OA\Post(
     *     path="/api/sessions",
     *     summary="Criar nova sessão (login)",
     *     tags={"Sessões"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="matheus@example.com"),
     *             @OA\Property(property="password", type="string", example="senha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sessão criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SessionTokenResponse")
     *     ),
     *     @OA\Response(response=422, description="Credenciais inválidas")
     * )
     */
    public function store();

    /**
     * @OA\Get(
     *     path="/api/sessions/{id}",
     *     summary="Exibir detalhes de uma sessão",
     *     tags={"Sessões"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da sessão",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados da sessão",
     *         @OA\JsonContent(ref="#/components/schemas/Session")
     *     ),
     *     @OA\Response(response=404, description="Sessão não encontrada")
     * )
     */
    public function show();

    /**
     * @OA\Delete(
     *     path="/api/sessions/{id}",
     *     summary="Deletar sessão específica",
     *     tags={"Sessões"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da sessão",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Sessão deletada"),
     *     @OA\Response(response=404, description="Sessão não encontrada")
     * )
     */
    public function destroy();

    /**
     * @OA\Post(
     *     path="/api/sessions/revoke",
     *     summary="Revogar sessão atual",
     *     tags={"Sessões"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Sessão atual revogada"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function revoke();
}
