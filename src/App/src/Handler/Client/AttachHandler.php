<?php

namespace App\Handler\Client;

/**
 * Description of AttachHandler
 * 
 * @OA\Post(
 *     path="/client/save",
 *     summary="Client Save",
 *     tags={"Client"},
 *     @OA\RequestBody(
 *         description="Object to assign",
 *         required=false,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(
 *                      property="project_id",
 *                      type="integer",
 *                      description="The id of project to assign",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="client_id",
 *                      type="integer",
 *                      description="The id of client to assing the project",
 *                      example=""
 *                  ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Project")
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     },
 * )
 *
 * @author cpantuso
 */
class AttachHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface 
    {
        $user = $this->getUser($request);

        $client_id = $this->getParam($request, 'client_id', '');
        $client = \App\Model\Client::find($client_id);
        if($client === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-4, 'Client not found');
        }

        $project_id = $this->getParam($request, 'project_id', '');
        $project = \App\Model\Project::find($project_id);
        if($project === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-5, 'Project not found');
        }

        try {
            $client->projects()->sync($project_id);
        } catch (\Exception $exc) {
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-3, $exc->getMessage());
        }

        return new \Mia\Core\Diactoros\MiaJsonResponse($project->toArray());
    }
}
