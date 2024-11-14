<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/2/2017
 * Time: 10:22 AM
 */


namespace App;

use Log;


class Link
{
    function __construct($title,$controller_name,$action_name,$rout,$sub_links = null,$icon = null)
    {
        $this->title = $title;
        $this->controller_name = $controller_name;
        $this->action_name = $action_name;
        $this->rout = $rout;
        $this->sub_links = $sub_links;
        $this->icon=$icon;
        if($this->sub_links == null)
            $this->sub_links = [];
    }

    public function add_SubLink($subLink)
    {
        $this->sub_links[] = $subLink;
    }

}