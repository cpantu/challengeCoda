<?php

namespace App\Repository;

use DateInterval;
use DateTime;

/**
 * Description of ClientRepository
 *
 * @author cpantuso
 */
class ClientRepository
{
    /**
     * 
     * @param \Mia\Database\Query\Configure $configure
     * @return \Illuminate\Pagination\Paginator
     */
    public static function fetchByConfigure(\Mia\Database\Query\Configure $configure)
    {
        $query = \App\Model\Client::select('client.*');
        
        if(!$configure->hasOrder()){
            $query->orderByRaw('id DESC');
        }
        $search = $configure->getSearch();
        if($search != ''){
            $values = $search . '|' . implode('|', explode(' ', $search));
            $query->whereRaw('(firstname REGEXP ? OR lastname REGEXP ? OR email REGEXP ?)', [$values, $values, $values]);
        }
        
        // Procesar parametros
        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }

    /**
     *
     * @param \Mia\Database\Query\Configure $configure
     * @return \Illuminate\Pagination\Paginator
     */
    public static function getDashboard(\Mia\Database\Query\Configure $configure)
    {
        $date_limit = new DateTime('now');
        $date_limit->sub(new DateInterval('P5D'));

        $query = \App\Model\Client::selectRaw('DATE_FORMAT(created_at, \'%Y-%m-%d\') AS day, count(*) as clients_created')
            ->where('created_at', '>=', $date_limit->format('Y-m-d'))
            ->groupBy('day');

        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }
}
