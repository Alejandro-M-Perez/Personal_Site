[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent $PSScriptRoot
$failures = [System.Collections.Generic.List[string]]::new()

function Add-Failure([string]$Message) {
    $failures.Add($Message)
}

$required = @(
    '.gitignore',
    'wp-content/themes/amp-portfolio/style.css',
    'wp-content/themes/amp-portfolio/theme.json',
    'wp-content/themes/amp-portfolio/templates/front-page.html',
    'wp-content/themes/amp-portfolio/templates/404.html',
    'wp-content/plugins/amp-portfolio-core/amp-portfolio-core.php',
    'tools/import-content.php',
    'docs/MIGRATION.md'
)

foreach ($relativePath in $required) {
    if (-not (Test-Path -LiteralPath (Join-Path $repoRoot $relativePath))) {
        Add-Failure "Missing required file: $relativePath"
    }
}

$jsonFiles = Get-ChildItem -LiteralPath (Join-Path $repoRoot 'wp-content') -Recurse -Filter '*.json' -File
foreach ($jsonFile in $jsonFiles) {
    try {
        Get-Content -Raw -LiteralPath $jsonFile.FullName | ConvertFrom-Json | Out-Null
    } catch {
        Add-Failure "Invalid JSON: $($jsonFile.FullName)"
    }
}

$tracked = @(git -C $repoRoot ls-files)
if ($LASTEXITCODE -ne 0) {
    Add-Failure 'Unable to list tracked Git files.'
}

$forbiddenPatterns = @(
    '(^|/)\.env($|\.)',
    '(^|/)wp-config\.php$',
    '\.(sql|sqlite|sqlite3|bak|backup|log|p12|pfx|pem|key)$',
    '(^|/)wp-admin/',
    '(^|/)wp-includes/',
    '(^|/)wp-content/uploads/',
    '(^|/)node_modules/',
    '(^|/)vendor/'
)

foreach ($path in $tracked) {
    if ($path -eq '.env.example') {
        continue
    }
    foreach ($pattern in $forbiddenPatterns) {
        if ($path -match $pattern) {
            Add-Failure "Forbidden tracked path: $path"
        }
    }
}

$sourceFiles = Get-ChildItem -LiteralPath (Join-Path $repoRoot 'wp-content') -Recurse -File |
    Where-Object { $_.Extension -in @('.php', '.html', '.css', '.js', '.json', '.md') }

$secretPatterns = @(
    '-----BEGIN (RSA|OPENSSH|EC|DSA) PRIVATE KEY-----',
    'gh[pousr]_[A-Za-z0-9_]{20,}',
    'github_pat_[A-Za-z0-9_]{20,}',
    '(?i)(password|passwd|secret|api[_-]?key)\s*[=:]\s*["''][^"'']{8,}["'']'
)

foreach ($file in $sourceFiles) {
    $content = Get-Content -Raw -LiteralPath $file.FullName
    foreach ($pattern in $secretPatterns) {
        if ($content -match $pattern) {
            Add-Failure "Possible secret in $($file.FullName) matching $pattern"
        }
    }
}

$wordpressSource = $sourceFiles | Where-Object { $_.FullName -notmatch '\\legacy-php\\' }
foreach ($file in $wordpressSource) {
    $content = Get-Content -Raw -LiteralPath $file.FullName
    if ($content -match '210[\s-]?702[\s-]?9022') {
        Add-Failure "Public phone number found in WordPress source: $($file.FullName)"
    }
}

$themeCss = Get-Content -Raw -LiteralPath (Join-Path $repoRoot 'wp-content/themes/amp-portfolio/style.css')
foreach ($requiredCss in @('@media (max-width: 782px)', ':focus-visible', 'prefers-reduced-motion', 'overflow-x: hidden')) {
    if (-not $themeCss.Contains($requiredCss)) {
        Add-Failure "Required responsive/accessibility CSS is missing: $requiredCss"
    }
}

if ($failures.Count -gt 0) {
    Write-Error ("Validation failed:`n - " + ($failures -join "`n - "))
    exit 1
}

Write-Host "Validation passed: $($jsonFiles.Count) JSON files, $($sourceFiles.Count) WordPress source files, and $($tracked.Count) tracked paths checked."
