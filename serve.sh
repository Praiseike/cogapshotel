#!/usr/bin/env bash
# Dev server with raised upload limits (PHP built-in server inherits PHP_INI_SCAN_DIR).
# The leading ':' keeps the system's default ini scan dir active.
export PHP_INI_SCAN_DIR=":$(cd "$(dirname "$0")" && pwd)/.phpini"
exec php artisan serve "$@"