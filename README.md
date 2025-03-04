# Events Management 
By [Ismail Ait El Kamel](https://github.com/ismaelaek)

## Requirements
- **PHP** 8.2 or higher
- **Composer** 2.x
- **Database** MySQL 8.x or higher

## Installation
1. **Clone the repository**:

```bash
git clone https://github.com/ismaelaek/events-backend.git
```
2. **Create the atabase**:

Create a new database named `events_management` and ensure that the MySQL `root` user has an empty password enabled in your local environment.

3. **Install dependencies**:

Run only this command, and everything will be set up in your local environment:

```bash
cd events-management
composer setup
```
### Note
in case you have issue and can't set up the project using `composer setup`, use the traditional way:

1. Create `.env` file,
```bash
copy .env.example .env
# if you are on UNIX 
cp .env.example .env
```
2. Run the following commands
```bash
composer install --ansi

php artisan key:generate

php artisan migrate:fresh --seed # enter yes if you haven't created database yet

```
