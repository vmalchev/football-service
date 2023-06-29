# Presidents v2

**Note**

Presidents are only present as a manual data entry, there is no support for presidents from the current provider (as of Jan 2021)

## Adding and Creating

### POST `/v2/presidents` or PUT `/v2/presidents/{president_id}`

```json
{
  "name": "Test President"
}
```

**Note** `name` is unique and required

### Output
```json
{
  "id": "1",
  "name": "Test President"
}
```