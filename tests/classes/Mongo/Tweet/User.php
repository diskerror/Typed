<?php

namespace TestClasses\Mongo\Tweet;

use Diskerror\Typed\BSON\{DateTime, TypedClass};
use Diskerror\Typed\Scalar\{TBoolean, TIntegerUnsigned, TString, TStringNormalize};

class User extends TypedClass
{
    protected TIntegerUnsigned $id;
    protected TString          $name;
    protected TString          $screen_name;
    protected TString          $location;
    protected TBoolean         $contributors_enabled;
    protected DateTime         $created_at;
    protected TStringNormalize $description;
    protected TIntegerUnsigned $favourites_count;
    protected TIntegerUnsigned $followers_count;
    protected TIntegerUnsigned $friends_count;
    protected TBoolean         $geo_enabled;
    protected TBoolean         $is_translator;
    protected TString          $lang;
    protected TIntegerUnsigned $listed_count;
    protected TBoolean         $protected;
    protected TIntegerUnsigned $statuses_count;
    protected TString          $time_zone;
    protected TString          $url;
    protected TBoolean         $verified;

    public function __construct($in = null)
    {
        $this->created_at = new DateTime('2018-07-18 17:10:28');
        $this->lang       = new TString('en');
        parent::__construct($in);
    }

}
