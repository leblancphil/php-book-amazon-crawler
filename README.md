# Amazon Book Searcher

A simple API that search a book and retrieve a JSON result.

Just make a request with GET method with parameter name `bookName`. Like:

```
/book_search.php?bookName=CleanCode
```

## Response

If there is results, response code will be 200 and body:

```json
[
	{
		"id": 2455124,
		"name": "Some book name",
		"author": "Name Here",
		"aval": 4.3,
		"imageSrc": "http://somedestination/asasa1212.jpg"
	},
	{
		"id": 7312444896,
		"name": "Some other name",
		"author": "Other Here",
		"aval": 2.0,
		"imageSrc": "http://somedestination/1231s.jpg"
	}
]
```

If there is no results, response code will be 204 with empty body.

If some error happened, response code will be 404 with some info.

```json
{
	"info": "Invalid search"
}
```
