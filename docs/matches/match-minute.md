# Match minute implementation

The match minute represents the minutes since the start of the current match phase.

Match minute is available only if the match is in one of the following statuses

- `1st_half`
- `2nd_half`
- `extra_time_1st_half`
- `extra_time_2nd_half`

If you are watching a football match on TV and the match is in one of the above phases, there will be a minute countdown on the screen.

The goal is to recreate this countdown on the API.

## Match minute rules

The match minute can only take certain values:

- regular and injury minute are always > 0
- in `1st_half` the minute is between 1 and 45
- in `2nd_half` the minute is between 46 and 90
- in `extra_time_1st_half` the minute is between 91 and 105
- in `extra_time_2nd_half` the minute is between 106 and 120

If the minute is above the maximum in each of the phases, the injury minute is incremented as the phase continues

**Note:** The injury minute is currently only available on v2 endpoints

## Match minute computation

To compute the match minute the `phase_started_at` v2 field must be specified either via provider or manual data.

The current minute is computed by taking the minute diff from `now` - `phase_started_at` and applying the rules above

The computation is done dynamically upon user request, the current minute is not stored

The data should be available on `/events`, `/matches` and `/v2/matches`