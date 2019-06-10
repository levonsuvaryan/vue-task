To run project.

1) install php dependencies.
2) copy .env.example file to .env and configure it.
3) generate new app key.
4) run migrations.
5) then run commands
    1) php artisan passport:install , and put Client secret with id 2, to your .env file  
    2) php artisan storage:link
    3) php artisan config:cache