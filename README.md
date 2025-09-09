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

- PHP >= 8.1  
- Composer  
- Laravel 10  
- MySQL / PostgreSQL  
- Node.js & npm (for compiling assets)  
- Optional: Redis or Memcached for caching  

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/product-geo-system.git
cd product-geo-system

Install dependencies

composer install
npm install
npm run dev

Configure Environment

cp .env.example .env

Update .env with your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_geo_system
DB_USERNAME=root
DB_PASSWORD=secret

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

Create Categories → Admin can add new categories.
Create Products → Assign category, upload multiple images, and configure GEO prices.
View Products → Products displayed with images carousel, category, GEO prices, and like/dislike buttons.
Edit/Delete Products → Update info or remove product and associated images/GEO prices.
Image Upload → Separate form for uploading images to any product or category.
Likes/Dislikes → Users can like or dislike products; counts stored in DB and cached.

Notes

Multiple Images: Max 10 images per product.
GEO Prices: Stored in product_geo_prices table. Dynamic price can be applied with price_coefficients.
Caching: Redis/Memcached can be used to speed up price queries or like counts.
Error Handling: All CRUD operations use try/catch and log errors in storage/logs/laravel.log.

Future Enhancements

Dynamic price coefficient calculation based on leads
Background job to update prices every 10 minutes
Advanced image similarity detection for uploaded images
Like/dislike count cache with real-time update.

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


