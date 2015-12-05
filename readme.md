Clone the repo: git clone git@github.com:menvil/neo4jfriends.git
Install Laravel: composer install --prefer-dist
Change your database settings in app/config/database.php at the neo4j section

Migrate your database: php artisan migrate
Seed your database: php artisan db:seed --class=UserSeeder
Make php artisan key:generate
Make sure that "public" directory is the root directory
chmod -R 0777 bootstrap/cache
chmod -R 0777 storage/

Routes
GET http://example.loc/users/  -- check user info
GET http://example.loc/users/1/friends/3 -- check users for 3rd level
GET http://example.loc/users/1/friends -- check users friends
DELETE http://example.loc/users/1/friends/4 -- user 1 remove from friends user 4
GET http://example.loc/users/1/requests/my -- show my friends requests
GET http://example.loc/users/1/requests/me -- show friends requests to me
PUT http://example.loc/users/1/requests/4 -- user 1 send friend request to user 4 (if they both have make friend requests to each other they become friends)
DELETE http://example.loc/users/1/requests/4 -- user 1 remote friend request to user 4