<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 2/12/17
 * Time: 4:57 PM
 */

namespace Model\Repository;
use Library\EntityRepository;
use Library\Request;

class AdverRepository extends EntityRepository{

    public function getAdversBoth($limit){
        $adverLeft = $this->getAdvers($limit, 'left');
        $adverRight= $this->getAdvers($limit, 'right');
        return array($adverLeft, $adverRight);
    }

    public function getAdvers($limit, $side){
        $sql = "SELECT * FROM adver
                WHERE side = '{$side}' AND active = 1
                ORDER BY priority
                DESC LIMIT $limit";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAll(){
        $sql = "SELECT * FROM adver
                ORDER BY id DESC";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function save(Request $request){
        if(!$id = $request->post('id')){
            $this->createAdvert($request);
        }

        $sql = "UPDATE adver
                SET service = :service, price = :price, company = :company, active = :active,
                      sale_product = :product, side = :side, priority = :priority
                WHERE id = :id";
        $sth = $this->pdo->prepare($sql);

        return $sth->execute(array('id' => $request->post('id'), 'service' => $request->post('service'),
                'price' => $request->post('price'), 'company' => $request->post('company'), 'active' => $request->post('active', 0),
                'product' => $request->post('sale_product'), 'side' => $request->post('side'), 'priority' => $request->post('priority')));
    }

    public function createAdvert(Request $request){
        $sql = "INSERT INTO adver
                SET service = :service, price = :price, company = :company, active = :active,
                      sale_product = :product, side = :side, priority = :priority";
        $sth = $this->pdo->prepare($sql);

        return $sth->execute(array('service' => $request->post('service'),
                'price' => $request->post('price'), 'company' => $request->post('company'), 'active' => $request->post('active',0),
                'product' => $request->post('sale_product'), 'side' => $request->post('side'), 'priority' => $request->post('priority')));
    }
} 