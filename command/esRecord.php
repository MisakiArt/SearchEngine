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