1. Clone the repo: git clone git@github.com:menvil/neo4jfriends.git

2. Install Laravel: composer install --prefer-dist

3. Change your database settings in app/config/database.php at the neo4j section

4. Seed your database: php artisan db:seed --class=UserSeeder

5. Make php artisan key:generate

6. Make sure that "public" directory is the root directory

7. chmod -R 0777 bootstrap/cache

8. chmod -R 0777 storage/

Routes

1. GET http://example.loc/users/  -- check user info

2. GET http://example.loc/users/1/friends/3 -- check users for 3rd level

3. GET http://example.loc/users/1/friends -- check users friends

4. DELETE http://example.loc/users/1/friends/4 -- user 1 remove from friends user 4

5. GET http://example.loc/users/1/requests/my -- show my friends requests

6. GET http://example.loc/users/1/requests/me -- show friends requests to me

7. PUT http://example.loc/users/1/requests/4 -- user 1 send friend request to user 4 (if they both have make friend requests to each other they become friends)

8. DELETE http://example.loc/users/1/requests/4 -- user 1 remote friend request to user 4