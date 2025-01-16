# Stock API Iterator

## Description
This Laravel application demonstrates how to use the Iterator pattern to fetch and process paginated stock market data from a simulated API (JSON files in this case). It's designed to handle scenarios where data is retrieved in chunks, and provides an efficient way to iterate over potentially large datasets.

## Setup

1.  Clone the repository:

    ```bash
    git clone <repository-url>
    cd stock-api-iterator
    ```
2. Install composer dependencies

    ```bash
    composer install
    ```

3. Copy `.env.example` to `.env` and add your APP_URL:

    ```
    cp .env.example .env
    ```
     Set the `APP_URL` to `http://localhost` in your `.env` file.
4.  Install Sail:

    ```bash
    composer require laravel/sail --dev
    php artisan sail:install
    ```
   * Select **0** `pgsql`
   * Select **ENTER** to finish installation
5.  Start Sail:

    ```bash
    ./vendor/bin/sail up -d
    ```
6. Generate an app key:
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

7.  Run Migrations
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

8. Install Node Packages
    ```bash
    npm install
    ```

9. Run the development server for the front-end:

    ```bash
    npm run dev
    ```
10. Access your Application via http://localhost

## Usage

### Access Stock Data

Visit `/stocks/{symbol}` to see stock data for the specified ticker symbol. Example: `/stocks/IBM`.

### Access Quote Data
Visit `/quote/{symbol}` to see quote data for the specified ticker symbol. Example: `/quote/IBM`.
