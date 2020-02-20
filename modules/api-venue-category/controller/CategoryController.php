<?php
/**
 * CategoryController
 * @package api-venue-category
 * @version 0.0.1
 */

namespace ApiVenueCategory\Controller;

use LibFormatter\Library\Formatter;

use Venue\Model\Venue;
use VenueCategory\Model\VenueCategory as VCategory;
use VenueCategory\Model\VenueCategoryChain as VCChain;


class CategoryController extends \Api\Controller
{

    public function indexAction() {
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        list($page, $rpp) = $this->req->getPager();

        $cond = $this->req->getCond(['q']);

        $cats = VCategory::get($cond, $rpp, $page, ['name'=>'ASC']);
        $cats = !$cats ? [] : Formatter::formatMany('venue-category', $cats);

        foreach($cats as &$pg)
            unset($pg->content, $pg->meta, $pg->user);
        unset($pg);

        $this->resp(0, $cats, null, [
            'meta' => [
                'page'  => $page,
                'rpp'   => $rpp,
                'total' => VCategory::count([])
            ]
        ]);
    }

    public function singleAction() {
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        $identity = $this->req->param->identity;

        $cat = VCategory::getOne(['id'=>$identity]);
        if(!$cat)
            $cat = VCategory::getOne(['slug'=>$identity]);

        if(!$cat)
            return $this->resp(404);

        $cat = Formatter::format('venue-category', $cat);
        unset($cat->meta, $cat->user);

        $this->resp(0, $cat);
    }

    public function venueAction() {
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        $identity = $this->req->param->identity;

        list($page, $rpp) = $this->req->getPager();

        $cond = [
            'venue_category' => $identity
        ];
        // if($q = $this->req->getQuery('q'))
        //     $cond['venue.q'] = $q;

        $venues = VCChain::get($cond, $rpp, $page, ['id' => 'DESC']);
        if($venues){
            $venues = array_column($venues, 'venue');
            $venues = Venue::get(['id'=>$venues]);

            $formats = ['user','category'];
            if(module_exists('venue-facility'))
                $formats[] = 'facility';
            if(module_exists('venue-food'))
                $formats[] = 'food';

            $venues = !$venues ? [] : Formatter::formatMany('venue', $venues, $formats);
        }

        foreach($venues as &$pg)
            unset($pg->content, $pg->meta);
        unset($pg);

        $this->resp(0, $venues, null, [
            'meta' => [
                'page'  => $page,
                'rpp'   => $rpp,
                'total' => VCChain::count($cond)
            ]
        ]);
    }
}