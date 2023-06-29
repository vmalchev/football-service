# Season Details V2

### GET https://{api}/v2/seasons/details - provides all relevant information to a season in one place

### Input parameters
1. season_id - valid ID of a season. Get a previous, current or future (if available) season
2. tournament_id - valid ID of a tournament, can only be used with the parameter/value: status=CURRENT. Get the current season
for a tournament.

## Example Output

```json
{
    "season": {
        "id": "6415",
        "name": "2020/2021",
        "tournament": {
            "id": "1",
            "name": "First Professional League",
            "country": {
                "id": "14",
                "name": "Bulgaria",
                "code": null,
                "assets": {
                    "flag": {
                        "url": "https://football.api.integration.sportal365.com/assets/country/flag/14-Bulgaria-flag.png"
                    }
                }
            },
            "gender": "MALE",
            "type": "LEAGUE",
            "region": "DOMESTIC",
            "assets": {
                "logo": {
                    "url": "https://football.api.integration.sportal365.com/assets/tournament/logo/1-First-Professional-League-logo-1.png"
                }
            }
        },
        "status": "INACTIVE"
    },
    "stages": [
        {
            "stage": {
                "id": "15197",
                "name": "First Professional League",
                "type": "LEAGUE",
                "start_date": "2020-08-07",
                "end_date": "2021-04-26",
                "order_in_season": 1,
                "coverage": "LIVE",
                "status": "INACTIVE"
            },
            "rounds": [
                {
                    "start_date": "2020-08-07",
                    "end_date": "2020-08-11",
                    "status": "INACTIVE",
                    "id": "2",
                    "key": "1",
                    "name": "1",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-08-14",
                    "end_date": "2020-08-17",
                    "status": "INACTIVE",
                    "id": "10",
                    "key": "2",
                    "name": "2",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-08-21",
                    "end_date": "2020-08-24",
                    "status": "INACTIVE",
                    "id": "11",
                    "key": "3",
                    "name": "3",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-08-28",
                    "end_date": "2020-08-30",
                    "status": "INACTIVE",
                    "id": "8",
                    "key": "4",
                    "name": "4",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-09-11",
                    "end_date": "2020-09-14",
                    "status": "INACTIVE",
                    "id": "12",
                    "key": "5",
                    "name": "5",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-09-18",
                    "end_date": "2020-09-21",
                    "status": "INACTIVE",
                    "id": "13",
                    "key": "6",
                    "name": "6",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-09-25",
                    "end_date": "2020-09-28",
                    "status": "INACTIVE",
                    "id": "14",
                    "key": "7",
                    "name": "7",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-10-02",
                    "end_date": "2020-12-17",
                    "status": "INACTIVE",
                    "id": "15",
                    "key": "8",
                    "name": "8",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-10-17",
                    "end_date": "2020-10-20",
                    "status": "INACTIVE",
                    "id": "16",
                    "key": "9",
                    "name": "9",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-10-24",
                    "end_date": "2020-10-26",
                    "status": "INACTIVE",
                    "id": "17",
                    "key": "10",
                    "name": "10",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-10-30",
                    "end_date": "2020-12-02",
                    "status": "INACTIVE",
                    "id": "19",
                    "key": "11",
                    "name": "11",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-11-07",
                    "end_date": "2020-12-16",
                    "status": "INACTIVE",
                    "id": "5",
                    "key": "12",
                    "name": "12",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-11-21",
                    "end_date": "2020-12-20",
                    "status": "INACTIVE",
                    "id": "20",
                    "key": "13",
                    "name": "13",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-11-27",
                    "end_date": "2020-12-09",
                    "status": "INACTIVE",
                    "id": "18",
                    "key": "14",
                    "name": "14",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-12-04",
                    "end_date": "2020-12-07",
                    "status": "INACTIVE",
                    "id": "3",
                    "key": "15",
                    "name": "15",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2020-12-11",
                    "end_date": "2020-12-15",
                    "status": "INACTIVE",
                    "id": "7",
                    "key": "16",
                    "name": "16",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-02-12",
                    "end_date": "2021-02-15",
                    "status": "INACTIVE",
                    "id": "21",
                    "key": "17",
                    "name": "17",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-02-19",
                    "end_date": "2021-02-22",
                    "status": "INACTIVE",
                    "id": "22",
                    "key": "18",
                    "name": "18",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-02-26",
                    "end_date": "2021-02-28",
                    "status": "INACTIVE",
                    "id": "23",
                    "key": "19",
                    "name": "19",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-03-06",
                    "end_date": "2021-03-09",
                    "status": "INACTIVE",
                    "id": "24",
                    "key": "20",
                    "name": "20",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-03-12",
                    "end_date": "2021-03-15",
                    "status": "INACTIVE",
                    "id": "6",
                    "key": "21",
                    "name": "21",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-03-19",
                    "end_date": "2021-03-21",
                    "status": "INACTIVE",
                    "id": "37",
                    "key": "22",
                    "name": "22",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-04-08",
                    "end_date": "2021-04-12",
                    "status": "INACTIVE",
                    "id": "39",
                    "key": "23",
                    "name": "23",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-04-15",
                    "end_date": "2021-04-18",
                    "status": "INACTIVE",
                    "id": "32",
                    "key": "24",
                    "name": "24",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-04-20",
                    "end_date": "2021-04-22",
                    "status": "INACTIVE",
                    "id": "38",
                    "key": "25",
                    "name": "25",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-04-23",
                    "end_date": "2021-04-26",
                    "status": "ACTIVE",
                    "id": "28",
                    "key": "26",
                    "name": "26",
                    "type": "LEAGUE"
                }
            ]
        },
        {
            "stage": {
                "id": "16347",
                "name": "First Professional League Championship Playoff",
                "type": null,
                "start_date": "2021-05-03",
                "end_date": "2021-05-26",
                "order_in_season": 2,
                "coverage": "LIVE",
                "status": "INACTIVE"
            },
            "rounds": [
                {
                    "start_date": "2021-05-03",
                    "end_date": "2021-05-19",
                    "status": "INACTIVE",
                    "id": "41",
                    "key": "27",
                    "name": "27",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-07",
                    "end_date": "2021-05-22",
                    "status": "INACTIVE",
                    "id": "42",
                    "key": "28",
                    "name": "28",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-11",
                    "end_date": "2021-05-12",
                    "status": "INACTIVE",
                    "id": "31",
                    "key": "29",
                    "name": "29",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-15",
                    "end_date": "2021-05-16",
                    "status": "INACTIVE",
                    "id": "36",
                    "key": "30",
                    "name": "30",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-26",
                    "end_date": "2021-05-26",
                    "status": "ACTIVE",
                    "id": "29",
                    "key": "31",
                    "name": "31",
                    "type": "LEAGUE"
                }
            ]
        },
        {
            "stage": {
                "id": "16351",
                "name": "First Professional League ECL Group",
                "type": null,
                "start_date": "2021-05-05",
                "end_date": "2021-05-23",
                "order_in_season": 3,
                "coverage": "LIVE",
                "status": "INACTIVE"
            },
            "rounds": [
                {
                    "start_date": "2021-05-05",
                    "end_date": "2021-05-05",
                    "status": "INACTIVE",
                    "id": "41",
                    "key": "27",
                    "name": "27",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-09",
                    "end_date": "2021-05-09",
                    "status": "INACTIVE",
                    "id": "42",
                    "key": "28",
                    "name": "28",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-13",
                    "end_date": "2021-05-13",
                    "status": "INACTIVE",
                    "id": "31",
                    "key": "29",
                    "name": "29",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-17",
                    "end_date": "2021-05-17",
                    "status": "INACTIVE",
                    "id": "36",
                    "key": "30",
                    "name": "30",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-20",
                    "end_date": "2021-05-20",
                    "status": "INACTIVE",
                    "id": "29",
                    "key": "31",
                    "name": "31",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-23",
                    "end_date": "2021-05-23",
                    "status": "ACTIVE",
                    "id": "25",
                    "key": "32",
                    "name": "32",
                    "type": "LEAGUE"
                }
            ]
        },
        {
            "stage": {
                "id": "16350",
                "name": "First Professional League Relegation Group",
                "type": null,
                "start_date": "2021-05-01",
                "end_date": "2021-05-21",
                "order_in_season": 4,
                "coverage": "LIVE",
                "status": "INACTIVE"
            },
            "rounds": [
                {
                    "start_date": "2021-05-03",
                    "end_date": "2021-05-03",
                    "status": "INACTIVE",
                    "id": "41",
                    "key": "27",
                    "name": "27",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-06",
                    "end_date": "2021-05-06",
                    "status": "INACTIVE",
                    "id": "42",
                    "key": "28",
                    "name": "28",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-10",
                    "end_date": "2021-05-10",
                    "status": "INACTIVE",
                    "id": "31",
                    "key": "29",
                    "name": "29",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-14",
                    "end_date": "2021-05-14",
                    "status": "INACTIVE",
                    "id": "36",
                    "key": "30",
                    "name": "30",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-18",
                    "end_date": "2021-05-18",
                    "status": "INACTIVE",
                    "id": "29",
                    "key": "31",
                    "name": "31",
                    "type": "LEAGUE"
                },
                {
                    "start_date": "2021-05-21",
                    "end_date": "2021-05-21",
                    "status": "ACTIVE",
                    "id": "25",
                    "key": "32",
                    "name": "32",
                    "type": "LEAGUE"
                }
            ]
        },
        {
            "stage": {
                "id": "16449",
                "name": "First Professional League ECL Playoff",
                "type": "KNOCK_OUT",
                "start_date": "2021-05-30",
                "end_date": "2021-05-30",
                "order_in_season": 5,
                "coverage": "LIVE",
                "status": "ACTIVE"
            },
            "rounds": [
                {
                    "start_date": "2021-05-30",
                    "end_date": "2021-05-30",
                    "status": "ACTIVE",
                    "id": "4",
                    "key": "Final",
                    "name": "Final",
                    "type": "KNOCK_OUT"
                }
            ]
        }
    ]
}
```

## Output hierarachy breakdown

 ++ season  
 ++++ tournament  
 ++ stages  
 ++++ stage  
 ++++ rounds  
 ++++++ round

## ACTIVE/INACTIVE logic breakdown

### For the stages of a season

The logic for determining whether a stage is active or inactive is as follows:

1. Order stages by start_date.
2. Look for stages for which today is >= start_date and today is <= end+date + 2 days. Mark them as ACTIVE.  
2.1 If no active stages are found mark the single stage with the nearest start_date to today as ACTIVE.
3. Finally mark all stages which start before/after 7 days of the start_date of the first ACTIVE stage found in the list as ACTIVE as well.

Note 1: A season will always have AT LEAST one active stage. Its first stage if it's about to begin, or its
last stage if it has already ended.  
Note 2: A season may have more than one active stage, if several stages have overlapping times.

### For the rounds of each stage

The logic for determining whether a round is active or inactive is as follows:

1. Find the round with the nearest start time to current time. Nearest means the smallest absolute difference in seconds between now and start_time.
This would also mean, if a stage is yet to start, nearest round would be the first, and as such marked active, and on the other hand, if a stage has ended, its last round would be marked as active.  
1.1 If the current time is after end_time + 48 hours of the found nearest round and there is a next round, mark the next round as ACTIVE (regardless of how far it is ahead)  
1.2 In all other cases the nearest round is active  

Note:  
A stage doesn't need to be active for it to have an active round. Depending on
whether it's ended or it's about to begin, its last or first round would be active respectively. A stage will always have EXACTLY
one active round.