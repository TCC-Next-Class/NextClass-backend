<?php

namespace App\Documentation;

/**
 *  @OA\Info(
 *    title="API NextClass",
 *    version="1.0.0",
 *    description="API do sistema NextClass",
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuário",
 *     required={"id","name","email","cpf"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Matheus Lopes"),
 *     @OA\Property(property="email", type="string", format="email", example="matheus@example.com"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-00")
 * )
 *
 * @OA\Schema(
 *     schema="Session",
 *     type="object",
 *     title="Sessão",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="device_name", type="string", example="PC Desktop"),
 *     @OA\Property(property="device_ip", type="string", example="192.168.0.100"),
 *     @OA\Property(property="device_agent", type="string", example="Mozilla/5.0")
 * )
 *
 * @OA\Schema(
 *     schema="SessionTokenResponse",
 *     type="object",
 *     title="Resposta da criação de sessão",
 *     @OA\Property(property="access_token", type="string", example="01994a21-8e00-7047-bc50-635f6f9262e6|N1E5DKLXjnApDw5ckFaTgRAIEwxs7uu2yaeQWE4X57d9263e"),
 *     @OA\Property(property="access_token_expires_at", type="string", format="date-time", nullable=true, example="2025-09-10T16:00:00Z")
 * )
 * 
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Schemas {}
