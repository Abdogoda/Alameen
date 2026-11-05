<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Al-Ameen Academy API v1 — OpenAPI 3.0.3 (Apidog Ready)</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #0f172a;
        }
        .api-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            color: #f8fafc;
            font-size: 14px;
        }
        .api-topbar-title {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .api-topbar-badge {
            background: #10b981;
            color: #064e3b;
            font-weight: 700;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 999px;
        }
        .api-topbar-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .api-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            background: #1e293b;
            color: #e2e8f0;
            border: 1px solid #334155;
            transition: background 0.15s ease;
        }
        .api-btn:hover {
            background: #334155;
        }
        .api-btn-primary {
            background: #2563eb;
            border-color: #3b82f6;
            color: #ffffff;
        }
        .api-btn-primary:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="api-topbar">
        <div class="api-topbar-title">
            <span>Al-Ameen Clinical Speech Therapy Academy — API v1</span>
            <span class="api-topbar-badge">OpenAPI 3.0.3 • Apidog Ready</span>
        </div>
        <div class="api-topbar-actions">
            <a class="api-btn" href="{{ url('/api/v1/openapi.json') }}" target="_blank" rel="noopener">
                Live URL for Apidog Import (/api/v1/openapi.json)
            </a>
            <a class="api-btn api-btn-primary" href="{{ asset('swagger.json') }}" download="alameen-apidog-openapi.json">
                Download Apidog OpenAPI (swagger.json)
            </a>
        </div>
    </div>

    <script
        id="api-reference"
        data-url="{{ url('/api/v1/openapi.json') }}"
        data-configuration='{"theme":"kepler","layout":"modern","defaultHttpClient":{"targetKey":"shell","clientKey":"curl"}}'></script>
    <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
</body>
</html>
