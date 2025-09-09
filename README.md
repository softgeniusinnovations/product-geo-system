# Product Geo System

A Laravel-based system for managing **Products**, **Categories**, **Images**, **GEO-based Pricing**, and **Like/Dislike Actions** with caching and multi-image support.

---

## Features

- CRUD for **Products** and **Categories**  
- **Multiple images per product** (stored in `images` table)  
- **GEO pricing** with delivery cost and local base price per GEO  
- **Dynamic price coefficient** for adjusting prices (planned feature)  
- **Like/Dislike system** for products or other entities  
- **Bootstrap 5 UI** with carousel for product images  
- **Redis/Memcached caching support** for performance  

---

## Requirements

- PHP >= 8.2  
- Composer  
- Laravel 12
- MySQL / PostgreSQL  
- Node.js & npm (for compiling assets)  
- Optional: Redis or Memcached for caching  

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/product-geo-system.git
cd product-geo-system

For run the project

composer install
npm install
npm run dev
npm run build

Configure Environment

cp .env.example .env

Update .env with your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_geo_system
DB_USERNAME=root
DB_PASSWORD=secret

Set Cache Driver

CACHE_DRIVER=redis
CACHE_DRIVER=file

Generate Application Key

php artisan key:generate

Run Migrations
php artisan migrate

This will create tables:
product_categories
products
images
geos
product_geo_prices
price_coefficients
likes

Link Storage
php artisan storage:link

Run the Application
php artisan serve

Open in your browser: http://localhost:8000

Folder Structure

app/
├─ Models/
│  ├─ Product.php
│  ├─ ProductCategory.php
│  ├─ ProductGeoPrice.php
│  ├─ PriceCoefficient.php
│  ├─ Image.php
│  └─ Geo.php
├─ Http/
│  ├─ Controllers/
│  │  ├─ ProductController.php
│  │  ├─ CategoryController.php
│  │  ├─ ImageController.php
│  │  └─ LikeController.php
database/
├─ migrations/
├─ seeders/
resources/
├─ views/
│  ├─ layouts/app.blade.php
│  ├─ products/
│  │  ├─ index.blade.php
│  │  ├─ create.blade.php
│  │  └─ edit.blade.php
│  ├─ categories/
│  │  ├─ index.blade.php
│  │  ├─ create.blade.php
│  │  └─ edit.blade.php
│  └─ images/
│     └─ upload.blade.php
public/
├─ storage/ (linked via `php artisan storage:link`)
routes/
├─ web.php

Usage

1. Create Categories → Admin can add new categories.
2. Create Products → Assign category, upload multiple images, and configure GEO prices.
3. View Products → Products displayed with images carousel, category, GEO prices, and like/dislike buttons.
4. Edit/Delete Products → Update info or remove product and associated images/GEO prices.
5. Image Upload → Separate form for uploading images to any product or category.
6. Likes/Dislikes → Users can like or dislike products; counts stored in DB and cached.
7. use cron job * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

Notes

1. Multiple Images: Max 10 images per product.
2. GEO Prices: Stored in product_geo_prices table. Dynamic price can be applied with price_coefficients.
3. Caching: Redis/Memcached can be used to speed up price queries or like counts.
4. Error Handling: All CRUD operations use try/catch and log errors in storage/logs/laravel.log.

Future Enhancements

1. Dynamic price coefficient calculation based on leads
2. Background job to update prices every 10 minutes
3. Advanced image similarity detection for uploaded images
4. Like/dislike count cache with real-time update.

Commands Summary

# Install dependencies
composer install
npm install
npm run dev

# Setup environment
cp .env.example .env
php artisan key:generate

# Database migrations & seed
php artisan migrate:fresh --seed

# Storage link
php artisan storage:link

# Run server
php artisan serve


