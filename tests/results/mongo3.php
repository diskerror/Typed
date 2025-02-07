TestClasses\Tweet::__set_state(array(
   '_map' => 
  array (
    'id' => '_id',
  ),
   '_id' => 
  Diskerror\Typed\Scalar\TInteger::__set_state(array(
     '_value' => 0,
     '_allowNull' => false,
     '_defaultValue' => 0,
  )),
   'retweeted_status' => 
  TestClasses\Tweet\ReTweet::__set_state(array(
     'id' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     '_map' => 
    array (
    ),
     '_calledClass' => 'TestClasses\\Tweet\\ReTweet',
     '_defaultValues' => 
    array (
      'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'created_at' => 
      Diskerror\TypedBSON\DateTime::__set_state(array(
         'date' => '2018-07-18 17:10:28.000000',
         'timezone_type' => 3,
         'timezone' => 'UTC',
      )),
      'contributors' => 
      Diskerror\Typed\Scalar\TAnything::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'entities' => 
      TestClasses\Tweet\Entity::__set_state(array(
         'hashtags' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
         'urls' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Url',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
         'user_mentions' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\Entity',
         '_defaultValues' => 
        array (
          'hashtags' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
          'urls' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\Url',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
          'user_mentions' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
        ),
         '_publicNames' => 
        array (
          0 => 'hashtags',
          1 => 'urls',
          2 => 'user_mentions',
        ),
         '_count' => 3,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
      'extended_entities' => 
      TestClasses\Tweet\ExtendedEntities::__set_state(array(
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\ExtendedEntities',
         '_defaultValues' => 
        array (
        ),
         '_publicNames' => 
        array (
        ),
         '_count' => 0,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
      'favorite_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'filter_level' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'low',
         '_allowNull' => false,
         '_defaultValue' => 'low',
      )),
      'in_reply_to_screen_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'in_reply_to_status_id_str' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'in_reply_to_user_id_str' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'is_quote_status' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'lang' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'en',
         '_allowNull' => false,
         '_defaultValue' => 'en',
      )),
      'place' => 
      TestClasses\Tweet\Place::__set_state(array(
         'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'place_type' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'full_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'country_code' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'country' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\Place',
         '_defaultValues' => 
        array (
          'id' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'url' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'place_type' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'full_name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'country_code' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'country' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
        ),
         '_publicNames' => 
        array (
          0 => 'id',
          1 => 'url',
          2 => 'place_type',
          3 => 'name',
          4 => 'full_name',
          5 => 'country_code',
          6 => 'country',
        ),
         '_count' => 7,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
      'possibly_sensitive' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'retweet_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'source' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'text' => 
      Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'truncated' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'user' => 
      TestClasses\Tweet\User::__set_state(array(
         'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'screen_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'location' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'contributors_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'created_at' => 
        Diskerror\TypedBSON\DateTime::__set_state(array(
           'date' => '2018-07-18 17:10:28.000000',
           'timezone_type' => 3,
           'timezone' => 'UTC',
        )),
         'description' => 
        Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'favourites_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'followers_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'friends_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'geo_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'is_translator' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'lang' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => 'en',
           '_allowNull' => false,
           '_defaultValue' => 'en',
        )),
         'listed_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'protected' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'statuses_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'time_zone' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'verified' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\User',
         '_defaultValues' => 
        array (
          'id' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'screen_name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'location' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'contributors_enabled' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'created_at' => 
          Diskerror\TypedBSON\DateTime::__set_state(array(
             'date' => '2018-07-18 17:10:28.000000',
             'timezone_type' => 3,
             'timezone' => 'UTC',
          )),
          'description' => 
          Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'favourites_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'followers_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'friends_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'geo_enabled' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'is_translator' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'lang' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => 'en',
             '_allowNull' => false,
             '_defaultValue' => 'en',
          )),
          'listed_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'protected' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'statuses_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'time_zone' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'url' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'verified' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
        ),
         '_publicNames' => 
        array (
          0 => 'id',
          1 => 'name',
          2 => 'screen_name',
          3 => 'location',
          4 => 'contributors_enabled',
          5 => 'created_at',
          6 => 'description',
          7 => 'favourites_count',
          8 => 'followers_count',
          9 => 'friends_count',
          10 => 'geo_enabled',
          11 => 'is_translator',
          12 => 'lang',
          13 => 'listed_count',
          14 => 'protected',
          15 => 'statuses_count',
          16 => 'time_zone',
          17 => 'url',
          18 => 'verified',
        ),
         '_count' => 19,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
    ),
     '_publicNames' => 
    array (
      0 => 'id',
      1 => 'created_at',
      2 => 'contributors',
      3 => 'entities',
      4 => 'extended_entities',
      5 => 'favorite_count',
      6 => 'filter_level',
      7 => 'in_reply_to_screen_name',
      8 => 'in_reply_to_status_id_str',
      9 => 'in_reply_to_user_id_str',
      10 => 'is_quote_status',
      11 => 'lang',
      12 => 'place',
      13 => 'possibly_sensitive',
      14 => 'retweet_count',
      15 => 'source',
      16 => 'text',
      17 => 'truncated',
      18 => 'user',
    ),
     '_count' => 19,
     'toArrayOptions' => 
    Diskerror\Typed\ArrayOptions::__set_state(array(
       '_options' => 12,
    )),
     'toJsonOptions' => 
    Diskerror\Typed\JsonOptions::__set_state(array(
       '_options' => 9,
    )),
     '_optionList' => 
    array (
      0 => 'toArrayOptions',
      1 => 'toJsonOptions',
    ),
     'toBsonOptions' => 
    Diskerror\TypedBSON\BsonOptions::__set_state(array(
       '_options' => 57,
    )),
     'created_at' => 
    Diskerror\TypedBSON\DateTime::__set_state(array(
       'date' => '2018-07-18 17:10:28.000000',
       'timezone_type' => 3,
       'timezone' => 'UTC',
    )),
     'contributors' => 
    Diskerror\Typed\Scalar\TAnything::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'entities' => 
    TestClasses\Tweet\Entity::__set_state(array(
       'hashtags' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'urls' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\Url',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'user_mentions' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\Entity',
       '_defaultValues' => 
      array (
        'hashtags' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'urls' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Url',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'user_mentions' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'hashtags',
        1 => 'urls',
        2 => 'user_mentions',
      ),
       '_count' => 3,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
     'extended_entities' => 
    TestClasses\Tweet\ExtendedEntities::__set_state(array(
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\ExtendedEntities',
       '_defaultValues' => 
      array (
      ),
       '_publicNames' => 
      array (
      ),
       '_count' => 0,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
     'favorite_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'filter_level' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => 'low',
       '_allowNull' => false,
       '_defaultValue' => 'low',
    )),
     'in_reply_to_screen_name' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'in_reply_to_status_id_str' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'in_reply_to_user_id_str' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'is_quote_status' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'lang' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => 'en',
       '_allowNull' => false,
       '_defaultValue' => 'en',
    )),
     'place' => 
    TestClasses\Tweet\Place::__set_state(array(
       'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'url' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'place_type' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'full_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'country_code' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'country' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\Place',
       '_defaultValues' => 
      array (
        'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'place_type' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'full_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'country_code' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'country' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'id',
        1 => 'url',
        2 => 'place_type',
        3 => 'name',
        4 => 'full_name',
        5 => 'country_code',
        6 => 'country',
      ),
       '_count' => 7,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
     'possibly_sensitive' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'retweet_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'source' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'text' => 
    Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'truncated' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'user' => 
    TestClasses\Tweet\User::__set_state(array(
       'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'screen_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'location' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'contributors_enabled' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'created_at' => 
      Diskerror\TypedBSON\DateTime::__set_state(array(
         'date' => '2018-07-18 17:10:28.000000',
         'timezone_type' => 3,
         'timezone' => 'UTC',
      )),
       'description' => 
      Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'favourites_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'followers_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'friends_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'geo_enabled' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'is_translator' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'lang' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'en',
         '_allowNull' => false,
         '_defaultValue' => 'en',
      )),
       'listed_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'protected' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'statuses_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'time_zone' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'url' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'verified' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\User',
       '_defaultValues' => 
      array (
        'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'screen_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'location' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'contributors_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'created_at' => 
        Diskerror\TypedBSON\DateTime::__set_state(array(
           'date' => '2018-07-18 17:10:28.000000',
           'timezone_type' => 3,
           'timezone' => 'UTC',
        )),
        'description' => 
        Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'favourites_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'followers_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'friends_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'geo_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'is_translator' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'lang' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => 'en',
           '_allowNull' => false,
           '_defaultValue' => 'en',
        )),
        'listed_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'protected' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'statuses_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'time_zone' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'verified' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'id',
        1 => 'name',
        2 => 'screen_name',
        3 => 'location',
        4 => 'contributors_enabled',
        5 => 'created_at',
        6 => 'description',
        7 => 'favourites_count',
        8 => 'followers_count',
        9 => 'friends_count',
        10 => 'geo_enabled',
        11 => 'is_translator',
        12 => 'lang',
        13 => 'listed_count',
        14 => 'protected',
        15 => 'statuses_count',
        16 => 'time_zone',
        17 => 'url',
        18 => 'verified',
      ),
       '_count' => 19,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
  )),
   '_calledClass' => 'TestClasses\\Tweet',
   '_defaultValues' => 
  array (
    '_id' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
    'retweeted_status' => 
    TestClasses\Tweet\ReTweet::__set_state(array(
       'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\ReTweet',
       '_defaultValues' => 
      array (
        'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'created_at' => 
        Diskerror\TypedBSON\DateTime::__set_state(array(
           'date' => '2018-07-18 17:10:28.000000',
           'timezone_type' => 3,
           'timezone' => 'UTC',
        )),
        'contributors' => 
        Diskerror\Typed\Scalar\TAnything::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'entities' => 
        TestClasses\Tweet\Entity::__set_state(array(
           'hashtags' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
           'urls' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\Url',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
           'user_mentions' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
           '_map' => 
          array (
          ),
           '_calledClass' => 'TestClasses\\Tweet\\Entity',
           '_defaultValues' => 
          array (
            'hashtags' => 
            Diskerror\TypedBSON\TypedArray::__set_state(array(
               '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
               '_container' => 
              array (
              ),
               'toArrayOptions' => 
              Diskerror\Typed\ArrayOptions::__set_state(array(
                 '_options' => 12,
              )),
               'toJsonOptions' => 
              Diskerror\Typed\JsonOptions::__set_state(array(
                 '_options' => 9,
              )),
               '_optionList' => 
              array (
                0 => 'toArrayOptions',
                1 => 'toJsonOptions',
              ),
               'toBsonOptions' => 
              Diskerror\TypedBSON\BsonOptions::__set_state(array(
                 '_options' => 57,
              )),
            )),
            'urls' => 
            Diskerror\TypedBSON\TypedArray::__set_state(array(
               '_type' => 'TestClasses\\Tweet\\Entity\\Url',
               '_container' => 
              array (
              ),
               'toArrayOptions' => 
              Diskerror\Typed\ArrayOptions::__set_state(array(
                 '_options' => 12,
              )),
               'toJsonOptions' => 
              Diskerror\Typed\JsonOptions::__set_state(array(
                 '_options' => 9,
              )),
               '_optionList' => 
              array (
                0 => 'toArrayOptions',
                1 => 'toJsonOptions',
              ),
               'toBsonOptions' => 
              Diskerror\TypedBSON\BsonOptions::__set_state(array(
                 '_options' => 57,
              )),
            )),
            'user_mentions' => 
            Diskerror\TypedBSON\TypedArray::__set_state(array(
               '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
               '_container' => 
              array (
              ),
               'toArrayOptions' => 
              Diskerror\Typed\ArrayOptions::__set_state(array(
                 '_options' => 12,
              )),
               'toJsonOptions' => 
              Diskerror\Typed\JsonOptions::__set_state(array(
                 '_options' => 9,
              )),
               '_optionList' => 
              array (
                0 => 'toArrayOptions',
                1 => 'toJsonOptions',
              ),
               'toBsonOptions' => 
              Diskerror\TypedBSON\BsonOptions::__set_state(array(
                 '_options' => 57,
              )),
            )),
          ),
           '_publicNames' => 
          array (
            0 => 'hashtags',
            1 => 'urls',
            2 => 'user_mentions',
          ),
           '_count' => 3,
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'extended_entities' => 
        TestClasses\Tweet\ExtendedEntities::__set_state(array(
           '_map' => 
          array (
          ),
           '_calledClass' => 'TestClasses\\Tweet\\ExtendedEntities',
           '_defaultValues' => 
          array (
          ),
           '_publicNames' => 
          array (
          ),
           '_count' => 0,
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'favorite_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'filter_level' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => 'low',
           '_allowNull' => false,
           '_defaultValue' => 'low',
        )),
        'in_reply_to_screen_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'in_reply_to_status_id_str' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'in_reply_to_user_id_str' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'is_quote_status' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'lang' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => 'en',
           '_allowNull' => false,
           '_defaultValue' => 'en',
        )),
        'place' => 
        TestClasses\Tweet\Place::__set_state(array(
           'id' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'url' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'place_type' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'full_name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'country_code' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'country' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           '_map' => 
          array (
          ),
           '_calledClass' => 'TestClasses\\Tweet\\Place',
           '_defaultValues' => 
          array (
            'id' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'url' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'place_type' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'name' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'full_name' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'country_code' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'country' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
          ),
           '_publicNames' => 
          array (
            0 => 'id',
            1 => 'url',
            2 => 'place_type',
            3 => 'name',
            4 => 'full_name',
            5 => 'country_code',
            6 => 'country',
          ),
           '_count' => 7,
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'possibly_sensitive' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'retweet_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'source' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'text' => 
        Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'truncated' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'user' => 
        TestClasses\Tweet\User::__set_state(array(
           'id' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'screen_name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'location' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'contributors_enabled' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
           'created_at' => 
          Diskerror\TypedBSON\DateTime::__set_state(array(
             'date' => '2018-07-18 17:10:28.000000',
             'timezone_type' => 3,
             'timezone' => 'UTC',
          )),
           'description' => 
          Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'favourites_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'followers_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'friends_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'geo_enabled' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
           'is_translator' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
           'lang' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => 'en',
             '_allowNull' => false,
             '_defaultValue' => 'en',
          )),
           'listed_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'protected' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
           'statuses_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
           'time_zone' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'url' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
           'verified' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
           '_map' => 
          array (
          ),
           '_calledClass' => 'TestClasses\\Tweet\\User',
           '_defaultValues' => 
          array (
            'id' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'name' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'screen_name' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'location' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'contributors_enabled' => 
            Diskerror\Typed\Scalar\TBoolean::__set_state(array(
               '_value' => false,
               '_allowNull' => false,
               '_defaultValue' => false,
            )),
            'created_at' => 
            Diskerror\TypedBSON\DateTime::__set_state(array(
               'date' => '2018-07-18 17:10:28.000000',
               'timezone_type' => 3,
               'timezone' => 'UTC',
            )),
            'description' => 
            Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'favourites_count' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'followers_count' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'friends_count' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'geo_enabled' => 
            Diskerror\Typed\Scalar\TBoolean::__set_state(array(
               '_value' => false,
               '_allowNull' => false,
               '_defaultValue' => false,
            )),
            'is_translator' => 
            Diskerror\Typed\Scalar\TBoolean::__set_state(array(
               '_value' => false,
               '_allowNull' => false,
               '_defaultValue' => false,
            )),
            'lang' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => 'en',
               '_allowNull' => false,
               '_defaultValue' => 'en',
            )),
            'listed_count' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'protected' => 
            Diskerror\Typed\Scalar\TBoolean::__set_state(array(
               '_value' => false,
               '_allowNull' => false,
               '_defaultValue' => false,
            )),
            'statuses_count' => 
            Diskerror\Typed\Scalar\TInteger::__set_state(array(
               '_value' => 0,
               '_allowNull' => false,
               '_defaultValue' => 0,
            )),
            'time_zone' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'url' => 
            Diskerror\Typed\Scalar\TString::__set_state(array(
               '_value' => '',
               '_allowNull' => false,
               '_defaultValue' => '',
            )),
            'verified' => 
            Diskerror\Typed\Scalar\TBoolean::__set_state(array(
               '_value' => false,
               '_allowNull' => false,
               '_defaultValue' => false,
            )),
          ),
           '_publicNames' => 
          array (
            0 => 'id',
            1 => 'name',
            2 => 'screen_name',
            3 => 'location',
            4 => 'contributors_enabled',
            5 => 'created_at',
            6 => 'description',
            7 => 'favourites_count',
            8 => 'followers_count',
            9 => 'friends_count',
            10 => 'geo_enabled',
            11 => 'is_translator',
            12 => 'lang',
            13 => 'listed_count',
            14 => 'protected',
            15 => 'statuses_count',
            16 => 'time_zone',
            17 => 'url',
            18 => 'verified',
          ),
           '_count' => 19,
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'id',
        1 => 'created_at',
        2 => 'contributors',
        3 => 'entities',
        4 => 'extended_entities',
        5 => 'favorite_count',
        6 => 'filter_level',
        7 => 'in_reply_to_screen_name',
        8 => 'in_reply_to_status_id_str',
        9 => 'in_reply_to_user_id_str',
        10 => 'is_quote_status',
        11 => 'lang',
        12 => 'place',
        13 => 'possibly_sensitive',
        14 => 'retweet_count',
        15 => 'source',
        16 => 'text',
        17 => 'truncated',
        18 => 'user',
      ),
       '_count' => 19,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
       'created_at' => 
      Diskerror\TypedBSON\DateTime::__set_state(array(
         'date' => '2018-07-18 17:10:28.000000',
         'timezone_type' => 3,
         'timezone' => 'UTC',
      )),
       'contributors' => 
      Diskerror\Typed\Scalar\TAnything::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'entities' => 
      TestClasses\Tweet\Entity::__set_state(array(
         'hashtags' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
         'urls' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Url',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
         'user_mentions' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\Entity',
         '_defaultValues' => 
        array (
          'hashtags' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
          'urls' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\Url',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
          'user_mentions' => 
          Diskerror\TypedBSON\TypedArray::__set_state(array(
             '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
             '_container' => 
            array (
            ),
             'toArrayOptions' => 
            Diskerror\Typed\ArrayOptions::__set_state(array(
               '_options' => 12,
            )),
             'toJsonOptions' => 
            Diskerror\Typed\JsonOptions::__set_state(array(
               '_options' => 9,
            )),
             '_optionList' => 
            array (
              0 => 'toArrayOptions',
              1 => 'toJsonOptions',
            ),
             'toBsonOptions' => 
            Diskerror\TypedBSON\BsonOptions::__set_state(array(
               '_options' => 57,
            )),
          )),
        ),
         '_publicNames' => 
        array (
          0 => 'hashtags',
          1 => 'urls',
          2 => 'user_mentions',
        ),
         '_count' => 3,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'extended_entities' => 
      TestClasses\Tweet\ExtendedEntities::__set_state(array(
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\ExtendedEntities',
         '_defaultValues' => 
        array (
        ),
         '_publicNames' => 
        array (
        ),
         '_count' => 0,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'favorite_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'filter_level' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'low',
         '_allowNull' => false,
         '_defaultValue' => 'low',
      )),
       'in_reply_to_screen_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'in_reply_to_status_id_str' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'in_reply_to_user_id_str' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'is_quote_status' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'lang' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'en',
         '_allowNull' => false,
         '_defaultValue' => 'en',
      )),
       'place' => 
      TestClasses\Tweet\Place::__set_state(array(
         'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'place_type' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'full_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'country_code' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'country' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\Place',
         '_defaultValues' => 
        array (
          'id' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'url' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'place_type' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'full_name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'country_code' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'country' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
        ),
         '_publicNames' => 
        array (
          0 => 'id',
          1 => 'url',
          2 => 'place_type',
          3 => 'name',
          4 => 'full_name',
          5 => 'country_code',
          6 => 'country',
        ),
         '_count' => 7,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'possibly_sensitive' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'retweet_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'source' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'text' => 
      Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'truncated' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'user' => 
      TestClasses\Tweet\User::__set_state(array(
         'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'screen_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'location' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'contributors_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'created_at' => 
        Diskerror\TypedBSON\DateTime::__set_state(array(
           'date' => '2018-07-18 17:10:28.000000',
           'timezone_type' => 3,
           'timezone' => 'UTC',
        )),
         'description' => 
        Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'favourites_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'followers_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'friends_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'geo_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'is_translator' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'lang' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => 'en',
           '_allowNull' => false,
           '_defaultValue' => 'en',
        )),
         'listed_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'protected' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         'statuses_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
         'time_zone' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
         'verified' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
         '_map' => 
        array (
        ),
         '_calledClass' => 'TestClasses\\Tweet\\User',
         '_defaultValues' => 
        array (
          'id' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'screen_name' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'location' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'contributors_enabled' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'created_at' => 
          Diskerror\TypedBSON\DateTime::__set_state(array(
             'date' => '2018-07-18 17:10:28.000000',
             'timezone_type' => 3,
             'timezone' => 'UTC',
          )),
          'description' => 
          Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'favourites_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'followers_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'friends_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'geo_enabled' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'is_translator' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'lang' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => 'en',
             '_allowNull' => false,
             '_defaultValue' => 'en',
          )),
          'listed_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'protected' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
          'statuses_count' => 
          Diskerror\Typed\Scalar\TInteger::__set_state(array(
             '_value' => 0,
             '_allowNull' => false,
             '_defaultValue' => 0,
          )),
          'time_zone' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'url' => 
          Diskerror\Typed\Scalar\TString::__set_state(array(
             '_value' => '',
             '_allowNull' => false,
             '_defaultValue' => '',
          )),
          'verified' => 
          Diskerror\Typed\Scalar\TBoolean::__set_state(array(
             '_value' => false,
             '_allowNull' => false,
             '_defaultValue' => false,
          )),
        ),
         '_publicNames' => 
        array (
          0 => 'id',
          1 => 'name',
          2 => 'screen_name',
          3 => 'location',
          4 => 'contributors_enabled',
          5 => 'created_at',
          6 => 'description',
          7 => 'favourites_count',
          8 => 'followers_count',
          9 => 'friends_count',
          10 => 'geo_enabled',
          11 => 'is_translator',
          12 => 'lang',
          13 => 'listed_count',
          14 => 'protected',
          15 => 'statuses_count',
          16 => 'time_zone',
          17 => 'url',
          18 => 'verified',
        ),
         '_count' => 19,
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
    )),
    'created_at' => 
    Diskerror\TypedBSON\DateTime::__set_state(array(
       'date' => '2018-07-18 17:10:28.000000',
       'timezone_type' => 3,
       'timezone' => 'UTC',
    )),
    'contributors' => 
    Diskerror\Typed\Scalar\TAnything::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
    'entities' => 
    TestClasses\Tweet\Entity::__set_state(array(
       'hashtags' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'urls' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\Url',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       'user_mentions' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\Entity',
       '_defaultValues' => 
      array (
        'hashtags' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'urls' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\Url',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
        'user_mentions' => 
        Diskerror\TypedBSON\TypedArray::__set_state(array(
           '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
           '_container' => 
          array (
          ),
           'toArrayOptions' => 
          Diskerror\Typed\ArrayOptions::__set_state(array(
             '_options' => 12,
          )),
           'toJsonOptions' => 
          Diskerror\Typed\JsonOptions::__set_state(array(
             '_options' => 9,
          )),
           '_optionList' => 
          array (
            0 => 'toArrayOptions',
            1 => 'toJsonOptions',
          ),
           'toBsonOptions' => 
          Diskerror\TypedBSON\BsonOptions::__set_state(array(
             '_options' => 57,
          )),
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'hashtags',
        1 => 'urls',
        2 => 'user_mentions',
      ),
       '_count' => 3,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
    'extended_entities' => 
    TestClasses\Tweet\ExtendedEntities::__set_state(array(
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\ExtendedEntities',
       '_defaultValues' => 
      array (
      ),
       '_publicNames' => 
      array (
      ),
       '_count' => 0,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
    'favorite_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
    'filter_level' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => 'low',
       '_allowNull' => false,
       '_defaultValue' => 'low',
    )),
    'in_reply_to_screen_name' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
    'in_reply_to_status_id_str' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
    'in_reply_to_user_id_str' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
    'is_quote_status' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
    'lang' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => 'en',
       '_allowNull' => false,
       '_defaultValue' => 'en',
    )),
    'place' => 
    TestClasses\Tweet\Place::__set_state(array(
       'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'url' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'place_type' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'full_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'country_code' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'country' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\Place',
       '_defaultValues' => 
      array (
        'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'place_type' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'full_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'country_code' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'country' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'id',
        1 => 'url',
        2 => 'place_type',
        3 => 'name',
        4 => 'full_name',
        5 => 'country_code',
        6 => 'country',
      ),
       '_count' => 7,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
    'possibly_sensitive' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
    'retweet_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
    'source' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
    'text' => 
    Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
    'truncated' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
    'user' => 
    TestClasses\Tweet\User::__set_state(array(
       'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'screen_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'location' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'contributors_enabled' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'created_at' => 
      Diskerror\TypedBSON\DateTime::__set_state(array(
         'date' => '2018-07-18 17:10:28.000000',
         'timezone_type' => 3,
         'timezone' => 'UTC',
      )),
       'description' => 
      Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'favourites_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'followers_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'friends_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'geo_enabled' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'is_translator' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'lang' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'en',
         '_allowNull' => false,
         '_defaultValue' => 'en',
      )),
       'listed_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'protected' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       'statuses_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
       'time_zone' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'url' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
       'verified' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
       '_map' => 
      array (
      ),
       '_calledClass' => 'TestClasses\\Tweet\\User',
       '_defaultValues' => 
      array (
        'id' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'screen_name' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'location' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'contributors_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'created_at' => 
        Diskerror\TypedBSON\DateTime::__set_state(array(
           'date' => '2018-07-18 17:10:28.000000',
           'timezone_type' => 3,
           'timezone' => 'UTC',
        )),
        'description' => 
        Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'favourites_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'followers_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'friends_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'geo_enabled' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'is_translator' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'lang' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => 'en',
           '_allowNull' => false,
           '_defaultValue' => 'en',
        )),
        'listed_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'protected' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
        'statuses_count' => 
        Diskerror\Typed\Scalar\TInteger::__set_state(array(
           '_value' => 0,
           '_allowNull' => false,
           '_defaultValue' => 0,
        )),
        'time_zone' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'url' => 
        Diskerror\Typed\Scalar\TString::__set_state(array(
           '_value' => '',
           '_allowNull' => false,
           '_defaultValue' => '',
        )),
        'verified' => 
        Diskerror\Typed\Scalar\TBoolean::__set_state(array(
           '_value' => false,
           '_allowNull' => false,
           '_defaultValue' => false,
        )),
      ),
       '_publicNames' => 
      array (
        0 => 'id',
        1 => 'name',
        2 => 'screen_name',
        3 => 'location',
        4 => 'contributors_enabled',
        5 => 'created_at',
        6 => 'description',
        7 => 'favourites_count',
        8 => 'followers_count',
        9 => 'friends_count',
        10 => 'geo_enabled',
        11 => 'is_translator',
        12 => 'lang',
        13 => 'listed_count',
        14 => 'protected',
        15 => 'statuses_count',
        16 => 'time_zone',
        17 => 'url',
        18 => 'verified',
      ),
       '_count' => 19,
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
  ),
   '_publicNames' => 
  array (
    0 => '_id',
    1 => 'retweeted_status',
    2 => 'created_at',
    3 => 'contributors',
    4 => 'entities',
    5 => 'extended_entities',
    6 => 'favorite_count',
    7 => 'filter_level',
    8 => 'in_reply_to_screen_name',
    9 => 'in_reply_to_status_id_str',
    10 => 'in_reply_to_user_id_str',
    11 => 'is_quote_status',
    12 => 'lang',
    13 => 'place',
    14 => 'possibly_sensitive',
    15 => 'retweet_count',
    16 => 'source',
    17 => 'text',
    18 => 'truncated',
    19 => 'user',
  ),
   '_count' => 20,
   'toArrayOptions' => 
  Diskerror\Typed\ArrayOptions::__set_state(array(
     '_options' => 12,
  )),
   'toJsonOptions' => 
  Diskerror\Typed\JsonOptions::__set_state(array(
     '_options' => 9,
  )),
   '_optionList' => 
  array (
    0 => 'toArrayOptions',
    1 => 'toJsonOptions',
  ),
   'toBsonOptions' => 
  Diskerror\TypedBSON\BsonOptions::__set_state(array(
     '_options' => 57,
  )),
   'created_at' => 
  Diskerror\TypedBSON\DateTime::__set_state(array(
     'date' => '2018-07-18 17:10:28.000000',
     'timezone_type' => 3,
     'timezone' => 'UTC',
  )),
   'contributors' => 
  Diskerror\Typed\Scalar\TAnything::__set_state(array(
     '_value' => '',
     '_allowNull' => false,
     '_defaultValue' => '',
  )),
   'entities' => 
  TestClasses\Tweet\Entity::__set_state(array(
     'hashtags' => 
    Diskerror\TypedBSON\TypedArray::__set_state(array(
       '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
       '_container' => 
      array (
      ),
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
     'urls' => 
    Diskerror\TypedBSON\TypedArray::__set_state(array(
       '_type' => 'TestClasses\\Tweet\\Entity\\Url',
       '_container' => 
      array (
      ),
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
     'user_mentions' => 
    Diskerror\TypedBSON\TypedArray::__set_state(array(
       '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
       '_container' => 
      array (
      ),
       'toArrayOptions' => 
      Diskerror\Typed\ArrayOptions::__set_state(array(
         '_options' => 12,
      )),
       'toJsonOptions' => 
      Diskerror\Typed\JsonOptions::__set_state(array(
         '_options' => 9,
      )),
       '_optionList' => 
      array (
        0 => 'toArrayOptions',
        1 => 'toJsonOptions',
      ),
       'toBsonOptions' => 
      Diskerror\TypedBSON\BsonOptions::__set_state(array(
         '_options' => 57,
      )),
    )),
     '_map' => 
    array (
    ),
     '_calledClass' => 'TestClasses\\Tweet\\Entity',
     '_defaultValues' => 
    array (
      'hashtags' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\Hashtag',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
      'urls' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\Url',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
      'user_mentions' => 
      Diskerror\TypedBSON\TypedArray::__set_state(array(
         '_type' => 'TestClasses\\Tweet\\Entity\\UserMention',
         '_container' => 
        array (
        ),
         'toArrayOptions' => 
        Diskerror\Typed\ArrayOptions::__set_state(array(
           '_options' => 12,
        )),
         'toJsonOptions' => 
        Diskerror\Typed\JsonOptions::__set_state(array(
           '_options' => 9,
        )),
         '_optionList' => 
        array (
          0 => 'toArrayOptions',
          1 => 'toJsonOptions',
        ),
         'toBsonOptions' => 
        Diskerror\TypedBSON\BsonOptions::__set_state(array(
           '_options' => 57,
        )),
      )),
    ),
     '_publicNames' => 
    array (
      0 => 'hashtags',
      1 => 'urls',
      2 => 'user_mentions',
    ),
     '_count' => 3,
     'toArrayOptions' => 
    Diskerror\Typed\ArrayOptions::__set_state(array(
       '_options' => 12,
    )),
     'toJsonOptions' => 
    Diskerror\Typed\JsonOptions::__set_state(array(
       '_options' => 9,
    )),
     '_optionList' => 
    array (
      0 => 'toArrayOptions',
      1 => 'toJsonOptions',
    ),
     'toBsonOptions' => 
    Diskerror\TypedBSON\BsonOptions::__set_state(array(
       '_options' => 57,
    )),
  )),
   'extended_entities' => 
  TestClasses\Tweet\ExtendedEntities::__set_state(array(
     '_map' => 
    array (
    ),
     '_calledClass' => 'TestClasses\\Tweet\\ExtendedEntities',
     '_defaultValues' => 
    array (
    ),
     '_publicNames' => 
    array (
    ),
     '_count' => 0,
     'toArrayOptions' => 
    Diskerror\Typed\ArrayOptions::__set_state(array(
       '_options' => 12,
    )),
     'toJsonOptions' => 
    Diskerror\Typed\JsonOptions::__set_state(array(
       '_options' => 9,
    )),
     '_optionList' => 
    array (
      0 => 'toArrayOptions',
      1 => 'toJsonOptions',
    ),
     'toBsonOptions' => 
    Diskerror\TypedBSON\BsonOptions::__set_state(array(
       '_options' => 57,
    )),
  )),
   'favorite_count' => 
  Diskerror\Typed\Scalar\TInteger::__set_state(array(
     '_value' => 0,
     '_allowNull' => false,
     '_defaultValue' => 0,
  )),
   'filter_level' => 
  Diskerror\Typed\Scalar\TString::__set_state(array(
     '_value' => 'low',
     '_allowNull' => false,
     '_defaultValue' => 'low',
  )),
   'in_reply_to_screen_name' => 
  Diskerror\Typed\Scalar\TString::__set_state(array(
     '_value' => '',
     '_allowNull' => false,
     '_defaultValue' => '',
  )),
   'in_reply_to_status_id_str' => 
  Diskerror\Typed\Scalar\TString::__set_state(array(
     '_value' => '',
     '_allowNull' => false,
     '_defaultValue' => '',
  )),
   'in_reply_to_user_id_str' => 
  Diskerror\Typed\Scalar\TString::__set_state(array(
     '_value' => '',
     '_allowNull' => false,
     '_defaultValue' => '',
  )),
   'is_quote_status' => 
  Diskerror\Typed\Scalar\TBoolean::__set_state(array(
     '_value' => false,
     '_allowNull' => false,
     '_defaultValue' => false,
  )),
   'lang' => 
  Diskerror\Typed\Scalar\TString::__set_state(array(
     '_value' => 'en',
     '_allowNull' => false,
     '_defaultValue' => 'en',
  )),
   'place' => 
  TestClasses\Tweet\Place::__set_state(array(
     'id' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'url' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'place_type' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'name' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'full_name' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'country_code' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'country' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     '_map' => 
    array (
    ),
     '_calledClass' => 'TestClasses\\Tweet\\Place',
     '_defaultValues' => 
    array (
      'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'url' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'place_type' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'full_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'country_code' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'country' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
    ),
     '_publicNames' => 
    array (
      0 => 'id',
      1 => 'url',
      2 => 'place_type',
      3 => 'name',
      4 => 'full_name',
      5 => 'country_code',
      6 => 'country',
    ),
     '_count' => 7,
     'toArrayOptions' => 
    Diskerror\Typed\ArrayOptions::__set_state(array(
       '_options' => 12,
    )),
     'toJsonOptions' => 
    Diskerror\Typed\JsonOptions::__set_state(array(
       '_options' => 9,
    )),
     '_optionList' => 
    array (
      0 => 'toArrayOptions',
      1 => 'toJsonOptions',
    ),
     'toBsonOptions' => 
    Diskerror\TypedBSON\BsonOptions::__set_state(array(
       '_options' => 57,
    )),
  )),
   'possibly_sensitive' => 
  Diskerror\Typed\Scalar\TBoolean::__set_state(array(
     '_value' => false,
     '_allowNull' => false,
     '_defaultValue' => false,
  )),
   'retweet_count' => 
  Diskerror\Typed\Scalar\TInteger::__set_state(array(
     '_value' => 0,
     '_allowNull' => false,
     '_defaultValue' => 0,
  )),
   'source' => 
  Diskerror\Typed\Scalar\TString::__set_state(array(
     '_value' => '',
     '_allowNull' => false,
     '_defaultValue' => '',
  )),
   'text' => 
  Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
     '_value' => '',
     '_allowNull' => false,
     '_defaultValue' => '',
  )),
   'truncated' => 
  Diskerror\Typed\Scalar\TBoolean::__set_state(array(
     '_value' => false,
     '_allowNull' => false,
     '_defaultValue' => false,
  )),
   'user' => 
  TestClasses\Tweet\User::__set_state(array(
     'id' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'name' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'screen_name' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'location' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'contributors_enabled' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'created_at' => 
    Diskerror\TypedBSON\DateTime::__set_state(array(
       'date' => '2018-07-18 17:10:28.000000',
       'timezone_type' => 3,
       'timezone' => 'UTC',
    )),
     'description' => 
    Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'favourites_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'followers_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'friends_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'geo_enabled' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'is_translator' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'lang' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => 'en',
       '_allowNull' => false,
       '_defaultValue' => 'en',
    )),
     'listed_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'protected' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     'statuses_count' => 
    Diskerror\Typed\Scalar\TInteger::__set_state(array(
       '_value' => 0,
       '_allowNull' => false,
       '_defaultValue' => 0,
    )),
     'time_zone' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'url' => 
    Diskerror\Typed\Scalar\TString::__set_state(array(
       '_value' => '',
       '_allowNull' => false,
       '_defaultValue' => '',
    )),
     'verified' => 
    Diskerror\Typed\Scalar\TBoolean::__set_state(array(
       '_value' => false,
       '_allowNull' => false,
       '_defaultValue' => false,
    )),
     '_map' => 
    array (
    ),
     '_calledClass' => 'TestClasses\\Tweet\\User',
     '_defaultValues' => 
    array (
      'id' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'screen_name' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'location' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'contributors_enabled' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'created_at' => 
      Diskerror\TypedBSON\DateTime::__set_state(array(
         'date' => '2018-07-18 17:10:28.000000',
         'timezone_type' => 3,
         'timezone' => 'UTC',
      )),
      'description' => 
      Diskerror\Typed\Scalar\TStringNormalize::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'favourites_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'followers_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'friends_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'geo_enabled' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'is_translator' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'lang' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => 'en',
         '_allowNull' => false,
         '_defaultValue' => 'en',
      )),
      'listed_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'protected' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
      'statuses_count' => 
      Diskerror\Typed\Scalar\TInteger::__set_state(array(
         '_value' => 0,
         '_allowNull' => false,
         '_defaultValue' => 0,
      )),
      'time_zone' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'url' => 
      Diskerror\Typed\Scalar\TString::__set_state(array(
         '_value' => '',
         '_allowNull' => false,
         '_defaultValue' => '',
      )),
      'verified' => 
      Diskerror\Typed\Scalar\TBoolean::__set_state(array(
         '_value' => false,
         '_allowNull' => false,
         '_defaultValue' => false,
      )),
    ),
     '_publicNames' => 
    array (
      0 => 'id',
      1 => 'name',
      2 => 'screen_name',
      3 => 'location',
      4 => 'contributors_enabled',
      5 => 'created_at',
      6 => 'description',
      7 => 'favourites_count',
      8 => 'followers_count',
      9 => 'friends_count',
      10 => 'geo_enabled',
      11 => 'is_translator',
      12 => 'lang',
      13 => 'listed_count',
      14 => 'protected',
      15 => 'statuses_count',
      16 => 'time_zone',
      17 => 'url',
      18 => 'verified',
    ),
     '_count' => 19,
     'toArrayOptions' => 
    Diskerror\Typed\ArrayOptions::__set_state(array(
       '_options' => 12,
    )),
     'toJsonOptions' => 
    Diskerror\Typed\JsonOptions::__set_state(array(
       '_options' => 9,
    )),
     '_optionList' => 
    array (
      0 => 'toArrayOptions',
      1 => 'toJsonOptions',
    ),
     'toBsonOptions' => 
    Diskerror\TypedBSON\BsonOptions::__set_state(array(
       '_options' => 57,
    )),
  )),
))