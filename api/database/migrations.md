## Migrations

to manage migrations & db changes this project uses [PHINX](https://phinx.org/) library

To create a new blank migration, run the following command:

`make create-migration`

To run the migrations run:

`make migrate`

When creating a new migration, a migration file will be created with a timestamp as filename containing a class named V{timestamp}. 
The rule here is to add some descriptive name to the file, like `20230504090242_add_this_column_to_this_table.php` and rename the class to more descriptive like `AddThisColumnToThisTable`.

