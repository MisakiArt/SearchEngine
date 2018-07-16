<?php
$str = '{
	"query": {
		"filtered": {
			"filter": {
				"bool": {
					"must": [{
						"term": {
							"mid": 121
						}
					}, {
						"term": {
							"tag_id": 11574
						}
					}, {
						"nested": {
							"path": "date_total_count",
							"query": {
								"bool": {
									"must": [{
										"range": {
											"date_total_count.created_date": {
												"lte": "20180711",
												"gte": "20180401"
											}
										}
									}]
								}
							}
						}
					}]
				}
			}
		}
	},
	"aggs": {
		"date_count": {
			"nested": {
				"path": "date_total_count"
			},
			"aggs": {
				"date": {
					"terms": {
						"field": "date_total_count.created_date",
						"order": {
							"_count": "desc"
						}
					}
				}
			}
		}
	}
}';

$esJson = array(
    "aggs" => array(
        "date_count"=>array(
            "nested"=>array(
                "path"=>"date_total_count",
            ),
            "aggs"=>array(
                "inner"=>array(
                    "filter"=>array(
                        "bool"=>array(
                            "must"=>array(
                                "range"=>array(
                                    "date_total_count.created_date"=>array(
                                        "gte"=>$start_intdate,
                                        "lte"=>$end_intdate
                                    )
                                )
                            )
                        )
                    ),
                    "aggs"=>array(
                        "date"=>array(
                            "terms"=>array(
                                "field"=>"date_total_count.created_date"
                            )
                        )
                    )
                )

            )
        )
    ),
    "query" => array(
        "filtered" => array(
            "filter" => array(
                "bool" => array(
                    "must" => array(
                        array("term" => array("mid" => $mid)),
                        array("term" => array("tag_id" => '5127')),
                        array("nested"=>array(
                            "path"=>"date_total_count",
                            "query"=>array(
                                "bool"=>array(
                                    "must"=>array(
                                        "range"=>array(
                                            "date_total_count.created_date"=>array(
                                                "gte"=>$start_intdate,
                                                "lte"=>$end_intdate
                                            )
                                        )
                                    )
                                )
                            )
                        )),
                        array(
                            "has_parent"=>array(
                                'parent_type'=>'wechat_customer',
                                "query"=>array(
                                    "bool"=>array(
                                        "must"=>array(
                                            array("term" => array("mid" => $mid)),
                                            array("term" => array("subscribe" => 1)),
                                            array("term" => array("source_channel_id" => 0)),
                                        )
                                    )
                                )
                            )
                        )
                    ),
                ),
            )
        ),
    )
);