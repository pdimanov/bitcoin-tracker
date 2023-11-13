# Bitcoin tracker

The idea of this project is to create a bitcoin tracker.

The API which is used for gathering the information is Bitfinex.

A cron fetches the data live every 15 seconds while a user can subscribe when:
- A price passes a fixed price
- A price has changed (increased/decreased) its price by a % during a specified period

(A user can also subscribe through an API endpoint)

In order to avoid spamming, an email will be sent every 6 hours if a subscription condition continues to pass the check.

Supported currencies are USD and Euro

There is a Chartjs graph which shows the past prices. (Still work in progress) The API endpoint is working though. You can choose between two periods (weekly/daily), choose your currencies and also the amount of time you want to get back to.

## Local setup & usage
1. Run ``cp .env.example .env``
2. Run
  ```
   docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php82-composer:latest \
   composer install --ignore-platform-reqs
   ```
This will install composer dependencies in php's container filesystem and make sail commands available
3. Run ``vendor/bin/sail up -d``. This will build all needed images and start their containers.
4. Run ``composer setup``. This will install dependencies (npm/composer), generate a new app key and run the migrations
5. Run ``composer bash`` which will start a bash session inside your PHP container. Then you can ``php artisan schedule:work`` which will start the cron job which fetches the current Bitcoin price.
6. Run ``composer bash`` and then ``php artisan queue:work`` which will start handling the queued jobs. This is where the processing of the subscriptions and email sending happens.

The app is available at http://localhost/. You can check http://localhost:8025/ which will load Mailpit which fetches the sent emails.

Running ``composer test`` will execute the feature and unit tests.

## API documentation

- ### ``POST`` ``/api/subscription/store`` - Subscribe for notifications
  1. Subscribe for fixed price
    - ``price`` (required) - positive integer
    - ``email`` (required) - valid email
    - ``currency`` (required) - either EUR or USD (case-insensitive)
    - ``isPercentageBased`` (required) - must be 0

  2. Subscribe for percentage-based difference
  - ``percentage`` (required) - float
  - ``interval`` (required) - 1, 6 or 24 (hours)
  - ``email`` (required) - valid email
  - ``currency`` (required) - either EUR or USD (case-insensitive)
  - ``isPercentageBased`` (required) - must be 1



    
- ### ``GET`` ``/api/price/history-period`` - Get past bitcoin prices grouped by period + previous/next URLs
    - ``timeShift`` (optional) - The amount of days/weeks to go back in time. Default is 0, which means the last amount will be as of now. 2 would result as getting the info 2 weeks/days before
    - ``timePeriod`` (optional) - The type of period to group the data. Options are ``day`` and ``week``. Default is ``day``.
    - ``currencies`` (optional) - The range of currencies to retrieve back. You can choose between ``EUR`` and ``USD`` or both, comma separated. Default is ``EUR,USD``.
    
### Major things to improve
- Finish Vue's functionality for Chartjs
- When fetching the history data, make sure to first check the database. If we don't have data for the chosen period, then fallback to the API and save the result in the database so we can fill the gap for future cases.
- Improve the setup process
