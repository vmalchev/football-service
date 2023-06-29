# Use cases

- Add groups to a stage
- Edit group names
- Reorder groups inside a stage
- Delete wrongly added groups

**Adding should only be allowed on stages without a link to a data provider**

# Endpoints

## DELETE `/v2/groups/{group_id}`

- Not allowed for groups with a link to a data provider
- Not allowed for groups associated with a match

## GET `/v2/stages/{stage_id}/groups`

```json
[
  {
    "id": "123",
    "name": "South",
    "order_in_stage": 1
  },
  {
    "id": "123",
    "name": "West",
    "order_in_stage": 2
  },
  {
    "id": "123",
    "name": "East",
    "order_in_stage": 3
  }
]

```

## POST `/v2/stages/{stage_id}/groups`

```json
[
  {
    "id": null,
    "name": "South",
    "order_in_stage": 1
  },
  {
    "id": "123",
    "name": "West",
    "order_in_stage": 2
  },
  {
    "id": "123",
    "name": "East",
    "order_in_stage": 3
  }
]
```

### Notes

- The convention is that the name field does not include things like 'Group' or 'Група'. The plan is to add another field for this in the future (if the
  requirement comes).
- `order_in_stage` is added for presentation purposes (which group to be shown first)

### Behaviour

- Sending an object with `id: null` will create a new group
- Sending an object with an existing `id` will replace the data for the group
- It is not required to add all groups in the array: only the ones edited can be added.
  **This is important as only the edited groups get blacklisted**
- `order_in_stage` has to be unique within the `stage`
- `name` has to be unique within the stage
- **New groups (`id:null`) can only be added to a stage which does not have a link to a data provider**
