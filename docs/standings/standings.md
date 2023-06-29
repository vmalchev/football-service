# Standings in Football API

- Standings is the current ranking based on points (and sometimes other criteria).
- Standings are available for stages which **do not have a knockout format**
- The parent entity of a standing in football-api is either **STAGE** or **GROUP**

# Examples

The type of standing can be determined by checking the stage metadata. This section lists the possible cases.

**NOTE: GET endpoints for v2 standings are not yet available**

## Knockout stage

This stage type will never have a standing. This means it can be excluded in all frontends showing standings.

### V1

Determined by checking if `cup` is `true`

```json
{
  "id": 16551,
  "name": "Champions League Qualification",
  "cup": true,
  "tournament_season_id": 6890,
  "tournament_id": 27,
  "country": {
    "id": 101,
    "name": "International",
    "url_flag": "https://football.api.sportal365.com/assets/country/flag/101-International-flag.png"
  },
  "start_date": "2021-07-06",
  "end_date": "2021-08-25",
  "live": true
}
```

### V2

Determined by checking if `type` is `KNOCK_OUT`

```json
{
  "id": "13089",
  "name": "Европейско Първенство - Финал",
  "type": "KNOCK_OUT",
  "start_date": "2021-06-26",
  "end_date": "2021-07-11",
  "order_in_season": 2,
  "coverage": "LIVE"
}
```

## League stage

A league stage will have the standing attached directly to the stage in v1. The league stage will not have any groups.

This is the classic case for domestic leagues: Premier League in England, Serie A in Italy, etc.

### V1

https://football.api.sportal365.com/tournaments/seasons/stages/16574?expand=standing

Check for `cup` is `false` and `stage_groups` is `null` (or not present)

**The standing array is empty in the doc for brevity.**

```json
{
  "id": 16574,
  "name": "First Professional League",
  "cup": false,
  "tournament_season_id": 6919,
  "tournament_id": 1,
  "country": {
    "id": 14,
    "name": "Bulgaria",
    "url_flag": "https://football.api.sportal365.com/assets/country/flag/14-Bulgaria-flag.png"
  },
  "start_date": "2021-07-23",
  "end_date": "2022-04-26",
  "live": true,
  "standing": []
}
```

### V2

Check for `type` is `LEAGUE`

```json
{
  "id": "16720",
  "name": "First Professional League",
  "type": "LEAGUE",
  "start_date": "2021-07-23",
  "end_date": "2022-04-26",
  "order_in_season": 1,
  "coverage": "LIVE"
}
```


## Group Stage

A group stage is where the teams participating are split into 2 or more groups. Examples are Champions League, World Cup group stages.

### V1

https://football.api.sportal365.com/tournaments/seasons/stages/16872?expand=standing

Determine by checking `cup` is `false` and `stage_groups` is not null

The standing is contained in each group of the stage. 

**The standing array is empty in the doc for brevity.**

```json
{
  "id": 16872,
  "name": "Champions League Group Stage",
  "cup": false,
  "tournament_season_id": 6890,
  "tournament_id": 27,
  "country": {
    "id": 101,
    "name": "International",
    "url_flag": "https://football.api.sportal365.com/assets/country/flag/101-International-flag.png"
  },
  "start_date": "2021-09-14",
  "end_date": "2021-12-08",
  "live": true,
  "stage_groups": 8,
  "groups": [
    {
      "id": 967,
      "name": "A",
      "standing": []
    },
    {
      "id": 968,
      "name": "B",
      "standing": []
    }
  ]
}
```

### V2

Determine by checking if `type` is `GROUP`

```json
{
      "id": "16968",
      "name": "Champions League Group Stage",
      "type": "GROUP",
      "start_date": "2021-09-14",
      "end_date": "2021-12-08",
      "order_in_season": 3,
      "coverage": "LIVE"
}
```