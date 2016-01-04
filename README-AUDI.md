# Document Audi Daily Doc API

### GET /auxiliary/models
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |

200 OK
```javascript
{
    "category": "models",
    "date": "2015-10-08",
    "items": [
        {
            "carline": "A3 Cab",
            "groups": "Cars",
            "model_code": "A3CAB18FWD",
            "model_detail": "A3 Cab 1.8 FWD"
        },
        ...
    ]
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /auxiliary/dealers
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |

200 OK
```javascript
{
    "category": "dealers",
    "date": "2015-10-08",
    "items": [
        {
            "area": "90",
            "area_name": "90 Chicago / St. Louis",
            "dealer_code": "402A01",
            "dealer_name": "Audi Hoffman Estates",
            "region": "Central",
            "subarea": "90a",
            "subarea_name": "90a Chicago"
        },
        {
            "area": "90",
            "area_name": "90 Chicago / St. Louis",
            "dealer_code": "402A32",
            "dealer_name": "Audi Morton Grove",
            "region": "Central",
            "subarea": "90a",
            "subarea_name": "90a Chicago"
        },
        ...
    ]
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /auxiliary/filter
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |

200 OK
```javascript
var variantTree =
{
    "A3 Cab": {
        "A3CAB18FWD": [
            "A3CAB18FWD"
        ],
        "A3CAB20Q": [
            "A3CAB20Q"
        ]
    },
    ...
}

var dealerTree =
{
    "Central": {
        "90": {
            "90a": [
                "402A01",
                "402A16",
                "402A20",
                ...
            ],
            "90b": [
                "402A04",
                "402A24",
                "402A58",
                ...
            ],
            "": [
                "403I81"
            ]
        },
        ...
    },
    ...
}

isArrayEquals = function (array1, array2) {
    return true;
}

getGeography = function (dealers) {
...
}

getModelsDetails = function (models) {
...
}

concatItems = function (value, array) {
...
}

stringifyFilterCode = function (type, models, dealers) {
...
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /auxiliary/latest_available_date
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |

200 OK
```javascript
{
    "latest_available_date": "2015-10-08"
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/pipeline
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

200 OK
```javascript
{
    "category": "pipeline",
    "date": "2015-10-08",
    "extended_cols": [],
    "filter": "^R,CENTRAL",
    "keys": {
        "key": "carline",
        "subkey": {
            "key": "variant",
            "subkey": null
        }
    },
    "layers": [
        {
            "index": 0,
            "key": "carline",
            "rows": [
                {
                    "items": [
                        {
                            "key": "carline",
                            "value": "A3 Cab"
                        },
                        {
                            "key": "s10",
                            "value": 0
                        },
                        {
                            "key": "s10_Changeable",
                            "value": 0
                        },
                        ...
                    ]
                },
                ...
                {
                    "is_total_row": 1,
                    "items": [
                        {
                            "key": "carline",
                            "value": "Total"
                        },
                        {
                            "key": "s10",
                            "value": 68
                        },
                        {
                            "key": "s10_Changeable",
                            "value": 25
                        },
                        ...
                    ]
                }
            ]
        },
        {
            "index": 1,
            "key": "variant",
            "rows": [
                {
                    "items": [
                        {
                            "key": "carline",
                            "value": "A3 Sportback"
                        },
                        {
                            "key": "variant",
                            "value": "A3 1.4 FWD e-tron"
                        },
                        {
                            "key": "s10",
                            "value": 4
                        },
                        ...
                    ]
                },
                ...
            }
            ]
        }
    ],
    "regular_cols": [
        {
            "key": "s10",
            "subcols": [
                {
                    "key": "s10_Changeable",
                    "subcols": [
                        {
                            "key": "s10_Changeable_15_46",
                            "subcols": [],
                            "value": "15-46"
                        },
                        {
                            "key": "s10_Changeable_15_47",
                            "subcols": [],
                            "value": "15-47"
                        },
                        {
                            "key": "s10_Changeable_15_48",
                            "subcols": [],
                            "value": "15-48"
                        },
                        ...
                    ],
                    "value": "Changeable"
                },
                ...
            ],
            "value": "10"
        },
        ...
    ],
    "soldOnly": false
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/pipeline_chart
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| sale_type    | no        | New, CPO             |   new        |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

200 OK
```javascript
{
    "category": "pipeline_chart",
    "charts": [
        {
            "cols": [
                {
                    "name": "10-13",
                    "value": 11
                },
                {
                    "name": "10-23",
                    "value": 19
                }
            ],
            "desc": "On the Water",
            "index": 0
        },
        {
            "cols": [
                {
                    "name": "At Port",
                    "value": 7
                },
                {
                    "name": "RTC",
                    "value": 5
                }
            ],
            "desc": "Port",
            "index": 1
        },
        {
            "cols": [
                {
                    "name": "05",
                    "value": 0
                },
                {
                    "name": "10",
                    "value": 68
                },
                ...
            ],
            "desc": "Pipeline",
            "index": 2
        }
    ],
    "date": "2015-10-08",
    "filter": "^R,CENTRAL",
    "soldOnly": false
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/aoa
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| type         | YES       | period (mtd,qtd,ytd) |   mtd        |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

200 OK
```javascript
{
    "category": "aoa",
    "date": "2015-10-08",
    "filter": "^R,CENTRAL",
    "keys": [
        {
            "desc": "New",
            "key": "sales_new_td",
            "value": 8
        },
        {
            "desc": "Task",
            "key": "percent_td_task",
            "lights": true,
            "value": 1.6
        },
        {
            "desc": "BPO",
            "key": "percent_td_bpo",
            "lights": true,
            "value": 1.6
        },
        {
            "desc": "YoY",
            "key": "percent_yoy",
            "value": 0.333
        },
        {
            "desc": "CPO",
            "key": "sales_cpo_td",
            "value": 6
        },
        {
            "desc": "BPO",
            "key": "percent_td_cpobpo",
            "lights": true,
            "value": 1.5
        },
        {
            "desc": "YoY",
            "key": "percent_cpo_yoy",
            "value": 0.2
        }
    ],
    "type": "mtd"
}

```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/area
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| type         | YES       | period (mtd,qtd,ytd) |   mtd        |
| sale_type    | no        | New, CPO             |   new        |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

Totals row is always presents in result of this method (on area level)

200 OK
```javascript
{
    "date": "2015-10-08",
    "category": "area",
    "type": "mtd",
    "sale_type": "new",
    "filter": "^R,CENTRAL",
    "keys": {
        "key": "area",
        "subkey": {
            "key": "subarea",
            "subkey": null
        }
    },
    "regular_cols": [
        {
            "key": "sales_new_yesterday",
            "value": "7-Oct",
            "default_visible": 1,
            "default_sort": null
        },
        {
            "key": "pure_retail_mtd",
            "value": "Sales",
            "default_visible": 1,
            "default_sort": null
        },
        ...
    ],
    "extended_cols": [],
    "layers": [
        {
            "index": 0,
            "key": "area",
            "rows": [
                {
                    "is_total_row": 0,
                    "items": [
                        {
                            "key": "area",
                            "value": "90",
                            "desc": "90 Chicago / St. Louis"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        ...
                    ]
                },
                ...
                {
                    "is_total_row": 1,
                    "items": [
                        {
                            "key": "area",
                            "value": "Total"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        ...
                    ]
                }
            ]
        },
        {
            "index": 1,
            "key": "subarea",
            "rows": [
                {
                    "items": [
                        {
                            "key": "area",
                            "value": "90"
                        },
                        {
                            "key": "subarea",
                            "value": "90a",
                            "desc": "90a Chicago"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        ...
                    ]
                }
            ]
        }
    ]
}
```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/region
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| type         | YES       | period (mtd,qtd,ytd) |   mtd        |
| sale_type    | no        | New, CPO             |   new        |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

Totals row is always presents in result of this method

200 OK
```javascript
{
    "date": "2015-10-08",
    "category": "region",
    "type": "mtd",
    "sale_type": "new",
    "filter": "^R,CENTRAL",
    "keys": {
        "key": "region",
        "subkey": null
    },
    "regular_cols": [
        {
            "key": "sales_new_yesterday",
            "value": "7-Oct",
            "default_visible": 1,
            "default_sort": null
        },
        {
            "key": "pure_retail_mtd",
            "value": "Sales",
            "default_visible": 1,
            "default_sort": null
        },
        {
            "key": "task_pace_mtd",
            "value": "Task",
            "default_visible": 1,
            "default_sort": "desc"
        },
        ...
    ],
    "layers": [
        {
            "index": 0,
            "key": "region",
            "rows": [
                {
                    "is_total_row": 0,
                    "items": [
                        {
                            "key": "region",
                            "value": "Central"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        {
                            "key": "pure_retail_mtd",
                            "value": 8
                        },
                        ...
                    ]
                },
                {
                    "is_total_row": 1,
                    "items": [
                        {
                            "key": "region",
                            "value": "Total"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        {
                            "key": "pure_retail_mtd",
                            "value": 8
                        },
                        ...
                    ]
                }
            ]
        }
    ]
}
```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/carline
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| type         | YES       | period (mtd,qtd,ytd) |   mtd        |
| sale_type    | no        | New, CPO             |   new        |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

Totals row is always presents in result of this method (on carline level)

200 OK
```javascript
{
    "date": "2015-10-08",
    "category": "carline",
    "type": "mtd",
    "sale_type": "new",
    "filter": "^R,CENTRAL",
    "keys": {
        "key": "carline",
        "subkey": {
            "key": "variant",
            "subkey": null
        }
    },
    "regular_cols": [
        {
            "key": "sales_new_yesterday",
            "value": "7-Oct",
            "default_visible": 1,
            "default_sort": null
        },
        {
            "key": "pure_retail_mtd",
            "value": "Sales",
            "default_visible": 1,
            "default_sort": null
        },
        {
            "key": "task_pace_mtd",
            "value": "Task",
            "default_visible": 1,
            "default_sort": "desc"
        },
        ...
    ],
    "extended_cols": [],
    "layers": [
        {
            "index": 0,
            "key": "carline",
            "rows": [
                {
                    "is_total_row": 0,
                    "items": [
                        {
                            "key": "carline",
                            "value": "A3 Cab",
                            "desc": "A3 Cab"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        {
                            "key": "pure_retail_mtd",
                            "value": 0
                        },
                        ...
                    ]
                },
                ...
                {
                    "is_total_row": 1,
                    "items": [
                        {
                            "key": "carline",
                            "value": "Total"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        {
                            "key": "pure_retail_mtd",
                            "value": 8
                        },
                        ...
                    ]
                }
            ]
        },
        {
            "index": 1,
            "key": "variant",
            "rows": [
                {
                    "items": [
                        {
                            "key": "carline",
                            "value": "A3 Sportback"
                        },
                        {
                            "key": "variant",
                            "value": "A3 1.4 FWD e-tron",
                            "desc": "A3 1.4 FWD e-tron"
                        },
                        {
                            "key": "sales_new_yesterday",
                            "value": 0
                        },
                        ...
                    ]
                },
                ...
            ]
        }
    ]
}
```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
```

### GET /report/history_chart
| Param        | Mandatory | Description          | Sample       |
| -------------|:---------:|:--------------------:| ------------:|
| date         | YES       | date                 |   2015-10-08 |
| filter       | no        | geo,models,dealer    |   ^R,CENTRAL |

Totals row is always presents in result of this method (on carline level)

200 OK
```javascript
{
    "date": "2015-10-08",
    "category": "history_chart",
    "filter": "^R,CENTRAL",
    "chart_types": [
        {
            "type": "sales_new",
            "desc": "New Sales",
            "charts": [
                {
                    "index": 0,
                    "desc": "Central",
                    "cols": [
                        {
                            "name": "2004",
                            "value": 430
                        },
                        {
                            "name": "2005",
                            "value": 504
                        },
                        {
                            "name": "2006",
                            "value": 508
                        },
                        ...
                    ]
                }
            ]
        },
        ...
    ]
}
```
422 Unprocessable Entity
```javascript
{
    "error": "Date shouldn't be empty"
}
