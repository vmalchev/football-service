# Knockout scheme endpoint

The knockout scheme endpoint returns a list of schemes for knockout stages in a given season

## Input params

- `season_id`: valid season, required 

## EXAMPLE

GET `/v2/knockout-scheme?season_id=123`

```json
[
  {
    "start_round": {
      "name": "1/8"
    },
    "end_round": {
      "name": "Final"
    },
    "stage": {
      "id": "13089",
      "name": "Европейско Първенство - Финална Фаза",
      "type": "KNOCK_OUT",
      "start_date": "2021-06-26",
      "end_date": "2021-07-11"
    },
    "rounds": [
      {
        "name": "1/8",
        "groups": [
          {
            "id": "276652",
            "order": 1,
            "teams": [
              {
                "id": "1871",
                "name": "1B",
                "type": "placeholder"
              },
              {
                "id": "941364",
                "name": "3ADEF",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767770",
                "kickoff_time": "2021-06-27T19:00:00Z"
              }
            ],
            "child_group_id": "276648"
          },
          {
            "id": "276653",
            "order": 2,
            "teams": [
              {
                "id": "1870",
                "name": "1A",
                "type": "placeholder"
              },
              {
                "id": "1880",
                "name": "2C",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767768",
                "kickoff_time": "2021-06-26T19:00:00Z"
              }
            ],
            "child_group_id": "276648"
          },
          {
            "id": "276654",
            "order": 3,
            "teams": [
              {
                "id": "1875",
                "name": "1F",
                "type": "placeholder"
              },
              {
                "id": "298,662",
                "name": "3ABC",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767773",
                "kickoff_time": "2021-06-28T19:00:00Z"
              }
            ],
            "child_group_id": "276649"
          },
          {
            "id": "276655",
            "order": 4,
            "teams": [
              {
                "id": "1881",
                "name": "2D",
                "type": "placeholder"
              },
              {
                "id": "1882",
                "name": "2E",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767772",
                "kickoff_time": "2021-06-28T16:00:00Z"
              }
            ],
            "child_group_id": "276649"
          },
          {
            "id": "276656",
            "order": 5,
            "teams": [
              {
                "id": "1874",
                "name": "1E",
                "type": "placeholder"
              },
              {
                "id": "941362",
                "name": "3ABCD",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767774",
                "kickoff_time": "2021-06-29T19:00:00Z"
              }
            ],
            "child_group_id": "276650"
          },
          {
            "id": "276657",
            "order": 6,
            "teams": [
              {
                "id": "1873",
                "name": "1D",
                "type": "placeholder"
              },
              {
                "id": "1883",
                "name": "2F",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767775",
                "kickoff_time": "2021-06-29T16:00:00Z"
              }
            ],
            "child_group_id": "276650"
          },
          {
            "id": "276659",
            "order": 7,
            "teams": [
              {
                "id": "1872",
                "name": "1C",
                "type": "placeholder"
              },
              {
                "id": "941363",
                "name": "3DEF",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767771",
                "kickoff_time": "2021-06-27T16:00:00Z"
              }
            ],
            "child_group_id": "276651"
          },
          {
            "id": "276658",
            "order": 8,
            "teams": [
              {
                "id": "1878",
                "name": "2A",
                "type": "placeholder"
              },
              {
                "id": "1879",
                "name": "2B",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767769",
                "kickoff_time": "2021-06-26T16:00:00Z"
              }
            ],
            "child_group_id": "276651"
          }
        ]
      },
      {
        "name": "Quarter Finals",
        "groups": [
          {
            "id": "276648",
            "order": 1,
            "teams": [
              {
                "id": "941366",
                "name": "1B/3ADEF",
                "type": "placeholder"
              },
              {
                "id": "941367",
                "name": "1A/2C",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767866",
                "kickoff_time": "2021-07-02T19:00:00Z"
              }
            ],
            "child_group_id": "276646"
          },
          {
            "id": "276649",
            "order": 2,
            "teams": [
              {
                "id": "941368",
                "name": "1F/3ABC",
                "type": "placeholder"
              },
              {
                "id": "941369",
                "name": "2D/2E",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767865",
                "kickoff_time": "2021-07-02T16:00:00Z"
              }
            ],
            "child_group_id": "276646"
          },
          {
            "id": "276650",
            "order": 3,
            "teams": [
              {
                "id": "941370",
                "name": "1E/3ABCD",
                "type": "placeholder"
              },
              {
                "id": "941371",
                "name": "1D/2F",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767868",
                "kickoff_time": "2021-07-03T19:00:00Z"
              }
            ],
            "child_group_id": "276647"
          },
          {
            "id": "276651",
            "order": 4,
            "teams": [
              {
                "id": "941372",
                "name": "2A/2B",
                "type": "placeholder"
              },
              {
                "id": "941373",
                "name": "1C/3DEF",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767867",
                "kickoff_time": "2021-07-03T16:00:00Z"
              }
            ],
            "child_group_id": "276647"
          }
        ]
      },
      {
        "name": "Semi Finals",
        "groups": [
          {
            "id": "276646",
            "order": 1,
            "teams": [
              {
                "id": "2036",
                "name": "Победител Четвъртфинал 1",
                "type": "placeholder"
              },
              {
                "id": "2037",
                "name": "Победител Четвъртфинал 2",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767870",
                "kickoff_time": "2021-07-06T19:00:00Z"
              }
            ],
            "child_group_id": "276645"
          },
          {
            "id": "276647",
            "order": 2,
            "teams": [
              {
                "id": "2038",
                "name": "Победител Четвъртфинал 3",
                "type": "placeholder"
              },
              {
                "id": "2039",
                "name": "Победител Четвъртфинал 4",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767871",
                "kickoff_time": "2021-07-07T19:00:00Z"
              }
            ],
            "child_group_id": "276645"
          }
        ]
      },
      {
        "name": "Final",
        "groups": [
          {
            "id": "276645",
            "order": 1,
            "teams": [
              {
                "id": "1983",
                "name": "Победител Полуфинал 1",
                "type": "placeholder"
              },
              {
                "id": "1984",
                "name": "Победител Полуфинал 2",
                "type": "placeholder"
              }
            ],
            "matches": [
              {
                "id": "2767869",
                "kickoff_time": "2021-07-11T19:00:00Z"
              }
            ],
            "child_group_id": null
          }
        ]
      }
    ]
  }
]
```