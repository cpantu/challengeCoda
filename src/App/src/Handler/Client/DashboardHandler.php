<?php

namespace App\Handler\Client;

/**
 * Description of DashboardHandler
 * 
 * @OA\Get(
 *     path="/client/dashboard",
 *     summary="Client dashboard",
 *     tags={"Client"},
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(
 *                      property="day",
 *                      type="date",
 *                      description="The date of the created clients.",
 *                      example="2022-02-15"
 *                  ),
 *                  @OA\Property(
 *                      property="clients_created",
 *                      type="integer",
 *                      description="The count of client created on that date.",
 *                      example="6"
 *                  ),
 *             )
 *         )
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     },
 * )
 *
 * @author cpantuso
 */
class DashboardHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface 
    {
        $configure = new \Mia\Database\Query\Configure($this, $request);

        $rows = \App\Repository\ClientRepository::getDashboard($configure);

        return new \Mia\Core\Diactoros\MiaJsonResponse($rows->toArray());
    }
}
