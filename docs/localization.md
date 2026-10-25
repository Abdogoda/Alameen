# Multilingual & RTL Architecture (العربية / English)

This project features a complete, active multilingual localization system supporting **Arabic (`ar`, RTL)** and **English (`en`, LTR)** with seamless switching across Web and API.

---

## 1. Core Architecture

### Architecture Layers
1. **Configuration**: [`config/localization.php`](file:///d:/WORK/LARAVEL/Alameen/config/localization.php)
   - Defines supported locales (`en`, `ar`), directional metadata (`ltr`, `rtl`), native names, and flags.
2. **Service**: [`App\Services\Localization\LocalizationService`](file:///d:/WORK/LARAVEL/Alameen/app/Services/Localization/LocalizationService.php)
   - Handles locale validation, detection hierarchy, directional logic (`isRtl()`, `getDirection()`), and multi-layer persistence (Session, Cookie, Database).
3. **Middleware**: [`App\Http\Middleware\SetLocale`](file:///d:/WORK/LARAVEL/Alameen/app/Http/Middleware/SetLocale.php)
   - Registered in both `web` and `api` middleware stacks in [`bootstrap/app.php`](file:///d:/WORK/LARAVEL/Alameen/bootstrap/app.php).
   - Sets `App::setLocale()` and `Carbon::setLocale()` for humanized dates (e.g. `منذ ساعتين`).
   - Shares `$currentLocale`, `$currentDirection`, `$isRtl`, and `$supportedLocales` with all Blade views.
   - Attaches `Content-Language` header to all HTTP responses.
4. **User Persistence**:
   - `users.locale` database column added via migration [`2026_10_25_144057_add_locale_to_users_table.php`](file:///d:/WORK/LARAVEL/Alameen/database/migrations/2026_10_25_144057_add_locale_to_users_table.php).
   - When an authenticated user switches language or updates their profile settings, their preferred language is permanently saved to their account.

---

## 2. Detection & Priority Hierarchy

### Web Requests
[`LocalizationService::detectLocale()`](file:///d:/WORK/LARAVEL/Alameen/app/Services/Localization/LocalizationService.php) resolves the active language for web requests in the following order:
1. **Explicit Query Parameter**: `?lang=ar` or `?locale=ar`
2. **Custom HTTP Header**: `X-Locale: ar` or `X-Language: ar`
3. **Authenticated User Profile**: `$user->locale`
4. **Session**: `session('locale')`
5. **Cookie**: `Cookie::get('locale')` (persists for 1 year)
6. **Browser Accept-Language Header**: `Accept-Language: ar,en-US;q=0.9`
7. **System Default**: `config('localization.default', 'en')`

### API Requests
In the API (`api/*` routes or requests expecting JSON), language detection is **strictly driven by HTTP headers, not URL paths or query parameters**:
1. **HTTP `Accept-Language` Header**: e.g., `Accept-Language: ar` or `Accept-Language: en,ar;q=0.8` (uses quality weighting).
2. **Authenticated User Profile**: `$user->locale` fallback if no header is supplied.
3. **System Default**: `config('localization.default', 'en')`.
4. URL query parameters (like `?lang=ar`) are **completely ignored** in the API to adhere to RESTful best practices.
5. All API responses automatically include the active language in the standard `Content-Language` response header.

---

## 3. Web UI & Switching

### Language Switcher Component
Located at [`resources/views/components/language-switcher.blade.php`](file:///d:/WORK/LARAVEL/Alameen/resources/views/components/language-switcher.blade.php):
- **`variant="pill"`**: Used on all Auth layouts (floating in the top corner `top-4 end-4`).
- **`variant="dropdown"`**: Used in top navigation bar and sidebar headers.
- **User Avatar Dropdown**: Submenu in [`desktop-user-menu.blade.php`](file:///d:/WORK/LARAVEL/Alameen/resources/views/components/desktop-user-menu.blade.php).
- **Profile Settings**: Segmented radio selector in [`resources/views/livewire/settings/profile.blade.php`](file:///d:/WORK/LARAVEL/Alameen/resources/views/livewire/settings/profile.blade.php).

### RTL Layout & Typography
- All layouts dynamically bind `dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"` and `lang="{{ app()->getLocale() }}"`.
- Typography includes Google's **Cairo** font with native Arabic fallbacks in [`resources/views/partials/head.blade.php`](file:///d:/WORK/LARAVEL/Alameen/resources/views/partials/head.blade.php) and [`resources/css/app.css`](file:///d:/WORK/LARAVEL/Alameen/resources/css/app.css).

---

## 4. Endpoints & API Headers

### Web Switch Route
- **Method**: `GET` / `POST`
- **URI**: `/locale/{locale}`
- **Name**: `locale.switch`
- **Behavior**: Sets locale in session, cookie, and user profile (if logged in), then redirects back.

### API Localization (Header-Driven)
- In accordance with standard REST principles, there is no URL locale switching endpoint in `/api/`.
- API clients specify their language preference via the standard **`Accept-Language`** header:
  ```http
  POST /api/v1/auth/login HTTP/1.1
  Host: example.com
  Accept: application/json
  Accept-Language: ar
  Content-Type: application/json
  ```
- The API responds with the localized payload and specifies the resolved language in the response header:
  ```http
  HTTP/1.1 422 Unprocessable Content
  Content-Type: application/json
  Content-Language: ar

  {
    "message": "بيانات الاعتماد هذه غير متطابقة مع سجلاتنا.",
    "errors": {
      "email": [
        "بيانات الاعتماد هذه غير متطابقة مع سجلاتنا."
      ]
    }
  }
  ```

---

## 5. Dictionaries & PHP Translation Structure

All translations are strictly organized as modular PHP files in `lang/{locale}/*.php`. Zero JSON files are used.

- [`App\Services\Localization\PhpFileLoader`](file:///d:/WORK/LARAVEL/Alameen/app/Services/Localization/PhpFileLoader.php):
  - Extends Laravel's `FileLoader` to load both grouped keys (e.g. `auth.failed`) and string keys (e.g. `Log in`, `Dashboard`) directly from PHP files.
- [`lang/ar/auth.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/auth.php) & [`lang/en/auth.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/auth.php): Authentication labels, forms, buttons, and credentials.
- [`lang/ar/security.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/security.php) & [`lang/en/security.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/security.php): Passkeys, 2FA, recovery codes, and password management.
- [`lang/ar/ui.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/ui.php) & [`lang/en/ui.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/ui.php): Navigation, dashboard, actions, headings, and profile settings.
- [`lang/ar/messages.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/messages.php) & [`lang/en/messages.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/messages.php): System alerts and API response messages.
- [`lang/ar/validation.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/validation.php) & [`lang/en/validation.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/validation.php): Form validation rules and localized attribute names.
- [`lang/ar/passwords.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/passwords.php) & [`lang/en/passwords.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/passwords.php): Password reset tokens.
- [`lang/ar/pagination.php`](file:///d:/WORK/LARAVEL/Alameen/lang/ar/pagination.php) & [`lang/en/pagination.php`](file:///d:/WORK/LARAVEL/Alameen/lang/en/pagination.php): Pagination controls.
