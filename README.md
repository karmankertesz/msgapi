POST /api/login - send a JSON object of {"username":"user123",
"password":"pass123"} to get a token.

These require Bearer token authentication:

POST  /api/message - send a JSON object {"message": "text here to
store"} to store a new message for the user

GET /api/history - returns the message history of the user, including
the number of retrievals and total count of messages