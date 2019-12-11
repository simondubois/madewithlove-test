
<p align="center">
    <img src="./screenshot.png">
</p>
<p align="center">
    Work assignment proposed by <a href="https://madewithlove.be/">madewithlove</a>.<br>
</p>
<p align="center">
    Demo available at https://madewithlove-test.dubandubois.com
</p>

## Original instructions

You’re working on an online shopping platform. The sales team wants to know which items were added to a basket, but removed before checkout. We will use this data later for targeted discounts.

Using PHP, build a shopping basket that helps you get this data.

**Minimal requirements**

* Use PHP

**Scope**

* Focus on API side, not on the interface

**Timing**

You have 3 weeks to accomplish the assignment. You decide yourself how much time and effort you invest in it, but one of our colleagues tends to say: "Make sure it is good" ;-).
Please ping us when you are done, on this endpoint ([Slack incoming webhooks](https://api.slack.com/incoming-webhooks)): `https://hooks.slack.com/services/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`. Please mention your name and a link to what we need to review.

## Features...

### ...for end users

- product boxes with `Add to cart` button.
- cart widget with ability to update quantities and remove products.
- fake checkout page to complete the order.
- bar charts to view, for a given product, how many carts also include each other product. Colors show if the cart status (paid or unpaid) and if the other product was removed from the cart.
- responsive design.

### ...for developers

- tested critical path (e.g. cart handling).
- API for cart handling (see below).
- business logic isolated in repositories.
- database seeding with fake data (run `php artisan db:seed`).
- consistent coding standard in PHP (run `composer php-check`).
- consistent coding standard in Vue.js (run `eslint --ext vue resources/js/`).
- consistent coding standard in plain javascript (run `eslint resources/js/`).

## Remaining tasks

- [ ] add pagination for products.
- [ ] trigger email sending on cart payment.
- [ ] test class `App\Repositories\CartCountRepository`.
- [ ] test route GET `/api/cart_counts/product/{product}`.
- [ ] protect route GET `/api/cart_counts/product/{product}` behind authentication.
- [ ] use a [proper library](https://github.com/moneyphp/money) to store prices and avoid manipulating floats.
- [ ] improve cart widget responsiveness.
- [ ] download pictures during product seeding.
- [ ] move texts to translation files (backend and frontend).
- [ ] integrate CI services to Github repository.

## Requirements

- a web server (tested with Apache).
- PHP >= 7.2.
- MySQL (tested with version 5.6 and 5.7).
- [composer](https://getcomposer.org/)

## Deployment

1. Download the code to an empty folder:
```bash
git clone https://github.com/madewithlove/technical-assignment-back-end-engineer-2-simondubois.git /var/www/simondubois
```
2. Create the configuration file:
```bash
cd /var/www/simondubois && cp .env.example .env
```
3. Install backend dependencies:
```bash
# EITHER
composer install --optimize-autoloader # to be able to seed fake data later
# OR
composer install --optimize-autoloader --no-dev # for production
```
4. In the `.env` file, set `APP_KEY`:
```
php artisan key:generate
```
5. In the `.env` file, set `DB_` variables to the relevant information for your MySQL server and database.
6. Migrate the database:
```
php artisan migrate
```
7. Seed the database:
```bash
# EITHER
php artisan db:seed --class ProductsTableSeeder # to only import products from the assignment file.
# OR
php artisan db:seed # to also create fake cart and cart_items
```
8. Point the web server to `/var/www/budget/public`.

## API

| Method | URI                               | Stateless | Parameters                                                                                                                                                    | Response                                                                                                                                                                                                |
|--------|-----------------------------------|-----------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| GET    | `cart/show`                       | no        | none                                                                                                                                                          | 200 cart in session, with products                                                                                                                                                                      |
| PUT    | `cart/pay`                        | no        | - `email` (required string)<br>- `name` (required string)<br>- `payment_method` (required string)                                                             | - 422 validation errors (when parameters are incorrect)<br>- 422 `EMPTY_CART` (when the cart has no product)<br>- 200 cart in session, with products (otherwise)                                        |
| POST   | cart_products                     | no        | - `product_id` (required integer)<br>- `quantity` (required integer)                                                                                          | - 422 validation errors (when parameters are incorrect)<br>- 201 created cart_product (otherwise)                                                                                                       |
| PUT    | cart_products/{cart_product}      | no        | `quantity` (required integer)                                                                                                                                 | - 403 `UNRELATED_CART_PRODUCT` (when `cart_product` does not belong to the cart in session)<br>- 422 validation errors (when parameters are incorrect)<br>- 200 updated cart_product (otherwise)        |
| DELETE | cart_products/{cart_product}      | no        | none                                                                                                                                                          | - 403 `UNRELATED_CART_PRODUCT` (when `cart_product` does not belong to the cart in session)<br>- 204 (otherwise)                                                                                        |
| GET    | api/cart_counts/product/{product} | yes       | - `end` (required datetime as `YYYY-MM-DD hh:mm:ss`)<br>- `product_ids` (required array of integer)<br>- `start` (required datetime as `YYYY-MM-DD hh:mm:ss`) | - 422 validation errors (when parameters are incorrect)<br>- 200 `{ "paid_existing_product": [...], "paid_deleted_product": [...], "unpaid_existing_product": [...], "unpaid_deleted_product": [...] }` |

## FAQ

### Why do you commit compiled frontend assets?
> Unfortunately, my hosting does not support nodejs. Versioning these files is the trade-off I accept [to give you access to a demo](https://madewithlove-test.dubandubois.com).

### Why does database seeding fail sometimes?
> Failure is due to the random datetime generation, which might pick a moment which doesn't exist because of timeshift.
> Hopefully, it should not happen too often and you can easily reset your database with `php artisan migrate:refresh --seed` (warning: ALL tables will be dropped).

### Why do you sometimes assert against Eloquent models, and sometimes against database?
> When a method returns a value, it is required to test the output, so the caller get a valid return value. Testing the database only does not guarantee that model attributes and relations are up to date. In this case, to verify database correctness, I also check the model's method `isDirty()`. Then both dataabse and Eloquent model are correct.
>
> When a method does not return any value (or has side effects beyond the return value), asserting against the database is required.

### Didn't you miss out on the assignment scope by implementing such elaborated frontend (vuejs, charts, responsiveness)?
> I understand that the focus is on the backend. However, I decided to invest my time in the frontend too because building UI:
> 1. is still expected according to the deliberately vague instructions, and I tried to not miss expectations.
> 2. might explain some decisions I took while building the backend.
> 3. helps to spot forgotten use cases.
> 4. is challenging (features vs simplicity, features vs performances).
> 5. is fun to build too.
> 6. is (hopefully) enjoyable to use and makes the rest more real.
