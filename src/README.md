# 📦 czechgroup/eshop-api-exposer

Zero-boilerplate API exposer for Nette-based e-shops.  
Install, paste credentials, and expose secure JSON endpoints for your shop data.

---

## 🚀 Features

- 🔐 **HMAC-authenticated API** — Secure request signing out of the box
- ⚡ **Zero PHP code required** — Works with existing Nette apps
- 📦 **Drop-in integration** — No refactoring needed
- 🧩 **Pluggable data providers** — Extend with custom sources
- 📄 **JSON-only responses** — Clean REST endpoints, no templates
- 🛡️ **Safe by default** — No auth → no data

---

## 📥 Installation

```
composer require czechgroup/eshop-api-exposer
```
---
## ⚠️ Requirements

This package requires your e-shop database to have the following tables:

- `product` — Product data table
- `setting_lang` — Language settings table

If your database schema differs, you may need to implement custom data providers.

---

## ⚙️ Configuration

### 1. Register the extension

Add to your `config.neon`:

```neon
extensions:
    eshopApi: Czechgroup\EshopApiExposer\DI\EshopApiExtension

eshopApi:
    apiKey: "your-api-key"
    apiSecret: "your-api-secret"
```

### 2. Configure routing

In your `RouterFactory`, add:

```php
$router->addRoute('<locale cs|en|de|ru|sk|pl>/api/eshop/<action>', [
    'presenter' => 'EshopApi:Api',
    'action' => 'default',
]);

$router->addRoute('/api/eshop/<action>', [
    'presenter' => 'EshopApi:Api',
    'action' => 'default',
    'locale' => 'cs', // your default language
]);
```

---

## 🌐 Endpoints

Access your e-shop data via:

```
GET /api/eshop/products
GET /{locale}/api/eshop/products
```

Supported locales: `cs`, `en`, `de`, `ru`, `sk`, `pl`

---

## 📝 License

MIT