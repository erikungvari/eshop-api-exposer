# 📦 czechgroup/eshop-api-exposer

Zero-boilerplate API exposer for Nette-based e-shops.  
Install, paste credentials, and expose secure JSON endpoints for your shop data.

---

## 🚀 Features

- 🔐 HMAC-authenticated API
- ⚡ Zero PHP code required in the e-shop
- 📦 Works with existing Nette apps
- 🧩 Pluggable data providers
- 📄 JSON responses only (no templates)
- 🛡️ Safe by default (no auth → no data)

---

## 📥 Installation

```
composer require czechgroup/eshop-api-exposer
```

⚙️ Configuration

Register the extension and paste credentials:
```
extensions:
    eshopApi: Czechgroup\EshopApiExposer\DI\EshopApiExtension

eshopApi:
    apiKey: "your-api-key"
    apiSecret: "your-api-secret"
```
In RouterFactory \
Add:
```
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

Endpoints
```
/api/eshop/products
<locale>/api/eshop/products
```