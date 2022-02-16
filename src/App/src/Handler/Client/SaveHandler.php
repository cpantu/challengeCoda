<?php

namespace App\Handler\Client;

/**
 * Description of SaveHandler
 * 
 * @OA\Post(
 *     path="/client/save",
 *     summary="Client Save",
 *     tags={"Client"},
*      @OA\RequestBody(
 *         description="Object",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/Client")
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Client")
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     },
 * )
 *
 * @author cpantuso
 */
class SaveHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface 
    {
        $user = $this->getUser($request);

        $item = $this->getForEdit($request);

        // TODO: add enum to MIARole in mia-auth-mezzio repo.
        if ($user->role != 2) {
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-2, 'The user must be an editor');
        }

        $item->firstname = $this->getParam($request, 'firstname', '');
        $item->lastname = $this->getParam($request, 'lastname', '');
        $item->email = $this->getParam($request, 'email', '');
        $item->phone = $this->getParam($request, 'phone', '');
        $item->photo = $this->getParam($request, 'photo', '');

        try {
            $item->save();
        } catch (\Exception $exc) {
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-3, $exc->getMessage());
        }

        return new \Mia\Core\Diactoros\MiaJsonResponse($item->toArray());
    }
    
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \App\Model\Client
     */
    protected function getForEdit(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $itemId = $this->getParam($request, 'id', '');

        $item = \App\Model\Client::find($itemId);

        if($item === null){
            return new \App\Model\Client();
        }

        return $item;
    }
}
