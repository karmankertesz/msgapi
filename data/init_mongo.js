
let res = [
	db = db.getSiblingDB('msgapi'),
	db.users.drop(),
	db.users.insertMany([{"username":"john","password":"$2y$10$SF3ZHkDY1wS8/mC8q5GZPeVJwD/4IV2/Sw6O8eZmG0tzkk.RibLHm"},{"username":"jack","password":"$2y$10$nx/baexzlqIQeuqdlzE3YuIh0BK.e6b/oClCaYHX4FkkXFMbdfvXG"}])
]

printjson(res)
